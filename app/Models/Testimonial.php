<?php

namespace App\Models;

use App\Support\HandlesImageMedia;
use Database\Factories\TestimonialFactory;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Testimonial extends Model
{
    /** @use HasFactory<TestimonialFactory> */
    use HandlesImageMedia;

    use HasFactory;

    protected $fillable = [
        'client_name',
        'client_position',
        'company_name',
        'testimonial_content',
        'client_photo',
        'photo_alt_text',
        'rating',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'rating' => 'integer',
            'is_active' => 'boolean',
        ];
    }

    #[Scope]
    protected function active(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    public static function imageMediaFields(): array
    {
        return ['client_photo'];
    }

    public function photoUrl(): ?string
    {
        return $this->imageUrl($this->client_photo);
    }

    public function photoThumbUrl(): ?string
    {
        return $this->imageThumbUrl($this->client_photo);
    }
}
