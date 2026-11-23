<?php

namespace App\Support;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\Drivers\Gd\Driver as GdDriver;
use Intervention\Image\Drivers\Imagick\Driver as ImagickDriver;
use Intervention\Image\ImageManager;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;

class ImageProcessor
{
    public const MAX_DIMENSION = 1920;

    public const THUMB_DIMENSION = 400;

    public const WEBP_QUALITY = 80;

    public static function disk(): string
    {
        return config('filesystems.media_disk', 'public');
    }

    public static function process(UploadedFile|TemporaryUploadedFile $file, string $directory): string
    {
        $disk = static::disk();
        $extension = strtolower($file->getClientOriginalExtension() ?: $file->extension() ?: 'jpg');
        $baseName = Str::ulid()->toBase32();
        $originalPath = $directory.'/'.$baseName.'.'.$extension;

        if (! static::canProcess()) {
            Log::warning('ImageProcessor: no image driver available, storing original file.', [
                'directory' => $directory,
                'name' => $file->getClientOriginalName(),
            ]);

            return static::storeOriginal($file, $directory, $originalPath);
        }

        try {
            $manager = static::manager();
            $image = $manager->read($file->getRealPath());
            $image->scaleDown(static::MAX_DIMENSION, static::MAX_DIMENSION);

            $webpPath = $directory.'/'.$baseName.'.webp';
            $thumbPath = $directory.'/'.$baseName.'_thumb.webp';

            Storage::disk($disk)->put($webpPath, (string) $image->toWebp(static::WEBP_QUALITY), 'public');

            $thumb = $manager->read($file->getRealPath())->scaleDown(static::THUMB_DIMENSION, static::THUMB_DIMENSION);
            Storage::disk($disk)->put($thumbPath, (string) $thumb->toWebp(static::WEBP_QUALITY), 'public');

            return $webpPath;
        } catch (\Throwable $e) {
            Log::warning('ImageProcessor: failed to optimize image, storing original file.', [
                'directory' => $directory,
                'name' => $file->getClientOriginalName(),
                'error' => $e->getMessage(),
            ]);

            return static::storeOriginal($file, $directory, $originalPath);
        }
    }

    public static function thumbPath(?string $path): ?string
    {
        if (blank($path) || str_contains($path, '://')) {
            return str_contains((string) $path, '://') ? $path : null;
        }

        $path = preg_replace('#^/storage/#', '', $path);
        $info = pathinfo($path);

        if (($info['extension'] ?? '') === '') {
            return null;
        }

        return ($info['dirname'] !== '.' ? $info['dirname'].'/' : '').$info['filename'].'_thumb.webp';
    }

    public static function url(?string $path): ?string
    {
        if (blank($path)) {
            return null;
        }

        if (str_contains($path, '://')) {
            return $path;
        }

        return Storage::disk(static::disk())->url($path);
    }

    public static function thumbUrl(?string $path): ?string
    {
        if (blank($path) || str_contains($path, '://')) {
            return $path;
        }

        $thumb = static::thumbPath($path);
        $disk = static::disk();

        if ($thumb === null) {
            return static::url($path);
        }

        $driver = config("filesystems.disks.{$disk}.driver");
        if ($driver !== 'local') {
            return str_ends_with($path, '.webp') ? Storage::disk($disk)->url($thumb) : static::url($path);
        }

        return Storage::disk($disk)->exists($thumb) ? Storage::disk($disk)->url($thumb) : static::url($path);
    }

    public static function delete(?string $path): void
    {
        if (blank($path) || str_contains($path, '://')) {
            return;
        }

        $disk = static::disk();
        $paths = array_filter(array_unique([$path, static::thumbPath($path)]));

        foreach ($paths as $candidate) {
            try {
                if (Storage::disk($disk)->exists($candidate)) {
                    Storage::disk($disk)->delete($candidate);
                }
            } catch (\Throwable $e) {
                Log::warning('ImageProcessor: failed to delete image file.', [
                    'path' => $candidate,
                    'error' => $e->getMessage(),
                ]);
            }
        }
    }

    public static function canProcess(): bool
    {
        return extension_loaded('gd') || extension_loaded('imagick');
    }

    private static function manager(): ImageManager
    {
        if (extension_loaded('imagick')) {
            return new ImageManager(new ImagickDriver);
        }

        return new ImageManager(new GdDriver);
    }

    private static function storeOriginal(UploadedFile|TemporaryUploadedFile $file, string $directory, string $path): string
    {
        $disk = static::disk();
        $stored = $file->storePubliclyAs($directory, basename($path), $disk);

        return $stored ?: $path;
    }
}
