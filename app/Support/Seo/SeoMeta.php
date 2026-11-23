<?php

namespace App\Support\Seo;

class SeoMeta
{
    /**
     * @param  array<int, array{name: string, url?: string|null}>  $breadcrumbs
     * @param  array<string, mixed>  $schema
     */
    public function __construct(
        public string $title,
        public string $description,
        public string $canonical,
        public string $robots = 'index,follow',
        public string $type = 'website',
        public ?string $image = null,
        public ?string $imageAlt = null,
        public ?string $siteName = null,
        public ?string $locale = null,
        public ?string $themeColor = '#0f172a',
        public array $breadcrumbs = [],
        public array $schema = [],
    ) {}
}
