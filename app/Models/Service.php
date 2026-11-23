<?php

namespace App\Models;

use App\Support\HandlesImageMedia;
use Database\Factories\ServiceFactory;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Service extends Model
{
    /** @use HasFactory<ServiceFactory> */
    use HandlesImageMedia;

    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'short_description',
        'seo_title',
        'seo_description',
        'seo_image',
        'canonical_url',
        'noindex',
        'full_description',
        'icon',
        'thumbnail_image',
        'thumbnail_alt_text',
        'display_order',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'display_order' => 'integer',
            'noindex' => 'boolean',
        ];
    }

    #[Scope]
    protected function active(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    #[Scope]
    protected function ordered(Builder $query): Builder
    {
        return $query->orderBy('display_order')->orderBy('name');
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public static function imageMediaFields(): array
    {
        return ['thumbnail_image', 'seo_image'];
    }

    public function thumbnailUrl(): ?string
    {
        return $this->imageUrl($this->thumbnail_image);
    }

    public function thumbnailThumbUrl(): ?string
    {
        return $this->imageThumbUrl($this->thumbnail_image);
    }

    public function seoImageUrl(): ?string
    {
        return $this->imageUrl($this->seo_image);
    }

    protected static function booted(): void
    {
        static::saving(function (Service $service): void {
            if (blank($service->slug) && filled($service->name)) {
                $service->slug = Str::slug($service->name);
            }
        });
    }
}
