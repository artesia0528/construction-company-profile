<?php

namespace App\Models;

use App\Support\HandlesImageMedia;
use Database\Factories\CompanySettingFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class CompanySetting extends Model
{
    /** @use HasFactory<CompanySettingFactory> */
    use HandlesImageMedia;

    use HasFactory;

    protected $fillable = [
        'company_name',
        'company_logo',
        'company_description',
        'company_address',
        'phone_number',
        'email_address',
        'whatsapp_number',
        'facebook_url',
        'instagram_url',
        'linkedin_url',
        'google_maps_embed_url',
        'copyright_text',
        'hero_headline',
        'hero_description',
        'hero_image',
        'hero_alt_text',
        'hero_primary_cta_label',
        'hero_primary_cta_url',
        'hero_secondary_cta_label',
        'hero_secondary_cta_url',
        'company_intro',
        'company_history',
        'vision',
        'mission',
        'company_values',
    ];

    protected function casts(): array
    {
        return [
            'company_values' => 'array',
        ];
    }

    public static function current(): self
    {
        $cached = Cache::get('company_settings.current');

        if (is_array($cached)) {
            $attributes = $cached;
        } elseif ($cached instanceof self) {
            $attributes = $cached->getAttributes();
            Cache::forever('company_settings.current', $attributes);
        } else {
            if ($cached !== null) {
                Cache::forget('company_settings.current');
            }

            $setting = self::query()->first() ?? new self([
                'company_name' => config('app.name', 'Nusantara Build'),
            ]);
            $attributes = $setting->getAttributes();
            Cache::forever('company_settings.current', $attributes);
        }

        $setting = new self;
        $setting->setRawAttributes($attributes, true);
        $setting->exists = array_key_exists('id', $attributes) && $attributes['id'] !== null;

        return $setting;
    }

    public static function clearCache(): void
    {
        Cache::forget('company_settings.current');
    }

    public static function imageMediaFields(): array
    {
        return ['company_logo', 'hero_image'];
    }

    public function logoUrl(): ?string
    {
        return $this->imageUrl($this->company_logo);
    }

    public function heroImageUrl(): ?string
    {
        return $this->imageUrl($this->hero_image);
    }

    public function heroImageThumbUrl(): ?string
    {
        return $this->imageThumbUrl($this->hero_image);
    }

    protected static function booted(): void
    {
        static::saved(fn () => self::clearCache());
        static::deleted(fn () => self::clearCache());
    }
}
