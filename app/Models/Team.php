<?php

namespace App\Models;

use App\Support\HandlesImageMedia;
use Database\Factories\TeamFactory;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Team extends Model
{
    /** @use HasFactory<TeamFactory> */
    use HandlesImageMedia;

    use HasFactory;

    protected $fillable = [
        'name',
        'position',
        'biography',
        'profile_photo',
        'photo_alt_text',
        'display_order',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'display_order' => 'integer',
            'is_active' => 'boolean',
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

    public static function imageMediaFields(): array
    {
        return ['profile_photo'];
    }

    public function photoUrl(): ?string
    {
        return $this->imageUrl($this->profile_photo);
    }

    public function photoThumbUrl(): ?string
    {
        return $this->imageThumbUrl($this->profile_photo);
    }
}
