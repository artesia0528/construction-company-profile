<?php

namespace App\Models;

use App\Support\HandlesImageMedia;
use Database\Factories\ProjectFactory;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Project extends Model
{
    /** @use HasFactory<ProjectFactory> */
    use HandlesImageMedia;

    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'short_description',
        'seo_title',
        'seo_description',
        'seo_image',
        'canonical_url',
        'noindex',
        'full_description',
        'category',
        'location',
        'client_name',
        'completion_year',
        'status',
        'cover_image',
        'cover_alt_text',
        'is_featured',
        'is_published',
    ];

    protected function casts(): array
    {
        return [
            'completion_year' => 'integer',
            'is_featured' => 'boolean',
            'is_published' => 'boolean',
            'noindex' => 'boolean',
        ];
    }

    public function images(): HasMany
    {
        return $this->hasMany(ProjectImage::class)->orderBy('sort_order');
    }

    #[Scope]
    protected function published(Builder $query): Builder
    {
        return $query->where('is_published', true);
    }

    #[Scope]
    protected function featured(Builder $query): Builder
    {
        return $query->where('is_featured', true);
    }

    #[Scope]
    protected function latestCompleted(Builder $query): Builder
    {
        return $query->orderByDesc('completion_year')->orderByDesc('created_at');
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public static function imageMediaFields(): array
    {
        return ['cover_image', 'seo_image'];
    }

    public function coverImageUrl(): ?string
    {
        return $this->imageUrl($this->cover_image);
    }

    public function coverImageThumbUrl(): ?string
    {
        return $this->imageThumbUrl($this->cover_image);
    }

    public function seoImageUrl(): ?string
    {
        return $this->imageUrl($this->seo_image);
    }

    protected static function booted(): void
    {
        static::saving(function (Project $project): void {
            if (blank($project->slug) && filled($project->title)) {
                $project->slug = Str::slug($project->title);
            }
        });

        static::deleting(function (Project $project): void {
            $project->loadMissing('images');
            $project->images->each->delete();
        });
    }
}
