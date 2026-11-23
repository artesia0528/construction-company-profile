<?php

namespace App\Console\Commands;

use App\Models\CompanySetting;
use App\Models\Project;
use App\Models\ProjectImage;
use App\Models\Service;
use App\Models\Team;
use App\Models\Testimonial;
use App\Support\ImageProcessor;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class OptimizeLegacyImages extends Command
{
    protected $signature = 'images:optimize-legacy {--dry-run : Report actions without writing files or DB} {--model= : Only process a specific model} {--cleanup : Delete old files after successful migration}';

    protected $description = 'Create resized WebP originals and thumbnails for legacy uploaded images';

    public function handle(): int
    {
        if (! ImageProcessor::canProcess()) {
            $this->warn('No GD/Imagick driver available. Processing cannot run.');
        }

        $targets = $this->targets();
        $filter = $this->option('model');
        if ($filter) {
            $targets = array_filter($targets, fn (array $t): bool => strcasecmp(class_basename($t['model']), $filter) === 0 || strcasecmp($t['model'], $filter) === 0);
        }

        $summary = ['processed' => 0, 'skipped' => 0, 'failed' => 0, 'before' => 0, 'after' => 0];

        foreach ($targets as $target) {
            /** @var class-string<Model> $model */
            $model = $target['model'];
            $field = $target['field'];
            $directory = $target['directory'];

            $model::query()->whereNotNull($field)->where($field, '!=', '')->chunkById(100, function ($records) use ($field, $directory, &$summary): void {
                foreach ($records as $record) {
                    $this->processRecord($record, $field, $directory, $summary);
                }
            });
        }

        $this->table(['Processed', 'Skipped', 'Failed', 'Before bytes', 'After bytes'], [[
            $summary['processed'], $summary['skipped'], $summary['failed'], $summary['before'], $summary['after'],
        ]]);

        return self::SUCCESS;
    }

    /** @return array<int, array{model: class-string<Model>, field: string, directory: string}> */
    private function targets(): array
    {
        return [
            ['model' => CompanySetting::class, 'field' => 'company_logo', 'directory' => 'settings'],
            ['model' => CompanySetting::class, 'field' => 'hero_image', 'directory' => 'settings'],
            ['model' => Service::class, 'field' => 'thumbnail_image', 'directory' => 'services'],
            ['model' => Project::class, 'field' => 'cover_image', 'directory' => 'projects/covers'],
            ['model' => ProjectImage::class, 'field' => 'image_path', 'directory' => 'projects/gallery'],
            ['model' => Team::class, 'field' => 'profile_photo', 'directory' => 'teams'],
            ['model' => Testimonial::class, 'field' => 'client_photo', 'directory' => 'testimonials'],
        ];
    }

    /** @param array{processed:int,skipped:int,failed:int,before:int,after:int} $summary */
    private function processRecord(Model $record, string $field, string $directory, array &$summary): void
    {
        $path = $record->getAttribute($field);
        if (blank($path) || str_contains((string) $path, '://')) {
            $summary['skipped']++;

            return;
        }

        $disk = ImageProcessor::disk();
        if (! Storage::disk($disk)->exists($path)) {
            $this->warn("Missing: {$path}");
            $summary['skipped']++;

            return;
        }

        $thumb = ImageProcessor::thumbPath($path);
        $alreadyWebp = str_ends_with(strtolower($path), '.webp');
        $hasThumb = $thumb && Storage::disk($disk)->exists($thumb);
        if ($alreadyWebp && $hasThumb) {
            $summary['skipped']++;

            return;
        }

        $summary['before'] += (int) Storage::disk($disk)->size($path);

        if ($this->option('dry-run')) {
            $this->line("[dry-run] would process {$path}");
            $summary['processed']++;

            return;
        }

        if (! ImageProcessor::canProcess()) {
            $summary['skipped']++;

            return;
        }

        try {
            $tmp = tempnam(sys_get_temp_dir(), 'img');
            file_put_contents($tmp, Storage::disk($disk)->get($path));
            $upload = new UploadedFile($tmp, basename($path), Storage::disk($disk)->mimeType($path), null, true);
            $newPath = ImageProcessor::process($upload, $directory);
            @unlink($tmp);

            $record->forceFill([$field => $newPath])->saveQuietly();
            $summary['after'] += (int) Storage::disk($disk)->size($newPath);
            if ($thumb && Storage::disk($disk)->exists($thumb)) {
                $summary['after'] += (int) Storage::disk($disk)->size($thumb);
            }

            if ($this->option('cleanup') && $newPath !== $path) {
                ImageProcessor::delete($path);
            }

            $summary['processed']++;
            $this->line("Processed {$path} -> {$newPath}");
        } catch (\Throwable $e) {
            $summary['failed']++;
            $this->error("Failed {$path}: {$e->getMessage()}");
        }
    }
}
