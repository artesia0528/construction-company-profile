<?php

namespace App\Models;

use App\Support\HandlesImageMedia;
use Database\Factories\ProjectImageFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProjectImage extends Model
{
    /** @use HasFactory<ProjectImageFactory> */
    use HandlesImageMedia;

    use HasFactory;

    protected $fillable = [
        'project_id',
        'image_path',
        'alt_text',
        'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'sort_order' => 'integer',
        ];
    }

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public static function imageMediaFields(): array
    {
        return ['image_path'];
    }

    public function url(): ?string
    {
        return $this->imageUrl($this->image_path);
    }

    public function thumbUrl(): ?string
    {
        return $this->imageThumbUrl($this->image_path);
    }
}
