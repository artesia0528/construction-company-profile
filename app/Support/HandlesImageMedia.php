<?php

namespace App\Support;

use App\Models\CompanySetting;
use App\Models\Project;
use App\Models\ProjectImage;
use App\Models\Service;
use App\Models\Team;
use App\Models\Testimonial;
use Illuminate\Database\Eloquent\Model;

trait HandlesImageMedia
{
    protected static function bootHandlesImageMedia(): void
    {
        static::deleted(function (Model $model): void {
            foreach (static::imageMediaFields() as $field) {
                $path = $model->getAttribute($field);
                if (filled($path) && ! static::imagePathReferenced($model, $field, $path)) {
                    ImageProcessor::delete($path);
                }
            }
        });

        static::updated(function (Model $model): void {
            foreach (static::imageMediaFields() as $field) {
                if (! $model->wasChanged($field)) {
                    continue;
                }

                $original = $model->getOriginal($field);
                $current = $model->getAttribute($field);

                if (filled($original) && $original !== $current && ! static::imagePathReferenced($model, $field, $original)) {
                    ImageProcessor::delete($original);
                }
            }
        });
    }

    /** @return array<int, string> */
    abstract public static function imageMediaFields(): array;

    protected static function imagePathReferenced(Model $model, string $field, string $path): bool
    {
        $checks = static::imageReferenceChecks();

        foreach ($checks as [$class, $column]) {
            $query = $class::query()->where($column, $path);
            if ($class === $model::class) {
                $query->whereKeyNot($model->getKey());
            }
            if ($query->exists()) {
                return true;
            }
        }

        return false;
    }

    /** @return array<int, array{0: class-string<Model>, 1: string}> */
    protected static function imageReferenceChecks(): array
    {
        return [
            [CompanySetting::class, 'company_logo'],
            [CompanySetting::class, 'hero_image'],
            [Service::class, 'thumbnail_image'],
            [Project::class, 'cover_image'],
            [ProjectImage::class, 'image_path'],
            [Team::class, 'profile_photo'],
            [Testimonial::class, 'client_photo'],
        ];
    }

    protected function imageUrl(?string $path): ?string
    {
        return ImageProcessor::url($path);
    }

    protected function imageThumbUrl(?string $path): ?string
    {
        return ImageProcessor::thumbUrl($path);
    }
}
