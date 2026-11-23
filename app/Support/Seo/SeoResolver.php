<?php

namespace App\Support\Seo;

use App\Models\CompanySetting;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class SeoResolver
{
    public function __construct(private SchemaBuilder $schemaBuilder) {}

    /**
     * @var array<int, string>
     */
    private const TRACKING_PARAMS = [
        'fbclid',
        'gclid',
        'mc_cid',
        'mc_eid',
        'ref',
        'utm_campaign',
        'utm_content',
        'utm_medium',
        'utm_source',
        'utm_term',
    ];

    /**
     * @param  array<string, mixed>  $overrides
     */
    public function resolve(Request $request, CompanySetting $settings, array $overrides = []): SeoMeta
    {
        $siteName = $this->string($overrides['siteName'] ?? null) ?? $this->string($settings->company_name) ?? config('app.name', 'Laravel');
        $rawTitle = $this->string($overrides['seoTitle'] ?? null) ?? $this->string($overrides['title'] ?? null);
        $fullTitle = $this->resolveTitle($rawTitle, $siteName, (bool) ($overrides['fullTitle'] ?? false));

        $description = $this->resolveDescription($overrides['seoDescription'] ?? $overrides['description'] ?? null, $settings, $siteName);
        $canonical = $this->resolveCanonical($request, $overrides['canonical'] ?? $overrides['canonicalUrl'] ?? null, (bool) ($overrides['isFilteredProjects'] ?? false));
        $robots = $this->resolveRobots($request, $overrides['robots'] ?? null, (bool) ($overrides['isFilteredProjects'] ?? false), (bool) ($overrides['noindex'] ?? false));

        $image = $this->absoluteUrl($this->string($overrides['seoImage'] ?? null))
            ?? $this->absoluteUrl($this->string($overrides['image'] ?? null))
            ?? $this->absoluteUrl($settings->seoImageUrl())
            ?? $this->absoluteUrl($settings->heroImageUrl())
            ?? $this->absoluteUrl($settings->logoUrl());

        $imageAlt = $this->string($overrides['imageAlt'] ?? null)
            ?? $this->string($settings->hero_alt_text)
            ?? $siteName;

        $breadcrumbs = is_array($overrides['breadcrumbs'] ?? null) ? $overrides['breadcrumbs'] : [];
        $schema = is_array($overrides['schema'] ?? null) ? $overrides['schema'] : [];
        $schema = $this->mergeGlobalSchema($schema, $settings, (bool) ($overrides['includeGlobalSchema'] ?? true));

        if ($breadcrumbs !== [] && ! $this->hasSchemaType($schema, 'BreadcrumbList')) {
            $breadcrumbSchema = $this->schemaBuilder->breadcrumbList($breadcrumbs);
            if ($breadcrumbSchema !== null) {
                $schema[] = $breadcrumbSchema;
            }
        }

        return new SeoMeta(
            title: $fullTitle,
            description: $description,
            canonical: $canonical,
            robots: $robots,
            type: $this->string($overrides['type'] ?? null) ?? 'website',
            image: $image,
            imageAlt: $imageAlt,
            siteName: $siteName,
            locale: str_replace('_', '-', app()->getLocale()),
            themeColor: $this->string($overrides['themeColor'] ?? null) ?? '#0f172a',
            breadcrumbs: $breadcrumbs,
            schema: $schema,
        );
    }

    private function resolveTitle(?string $title, string $siteName, bool $fullTitle): string
    {
        if ($title === null) {
            return $siteName;
        }

        if ($fullTitle || Str::contains($title, $siteName)) {
            return $title;
        }

        return $title.' | '.$siteName;
    }

    private function resolveDescription(mixed $override, CompanySetting $settings, string $siteName): string
    {
        $description = $this->string($override)
            ?? $this->string($settings->company_description)
            ?? $siteName.' company profile.';

        $description = preg_replace('/\s+/u', ' ', $description) ?? $description;
        $description = trim($description);

        return Str::limit($description, 160, '');
    }

    private function resolveCanonical(Request $request, mixed $override, bool $isFilteredProjects): string
    {
        $override = $this->string($override);
        if ($override !== null) {
            return $this->absoluteUrl($override) ?? $override;
        }

        if ($isFilteredProjects) {
            return $this->absoluteUrl(route('projects.index', [], false)) ?? url('/projects');
        }

        $query = collect($request->query())
            ->except(self::TRACKING_PARAMS)
            ->sortKeys()
            ->all();

        if ($request->routeIs('projects.index')) {
            $query = collect($query)->only(['page'])->filter(fn ($value) => filled($value) && (string) $value !== '1')->all();
        }

        $base = $request->url();
        $queryString = http_build_query($query);

        return $queryString === '' ? $base : $base.'?'.$queryString;
    }

    private function resolveRobots(Request $request, mixed $override, bool $isFilteredProjects, bool $noindex = false): string
    {
        $override = $this->string($override);
        if ($override !== null) {
            return $override;
        }

        if ($isFilteredProjects || $noindex) {
            return 'noindex,follow';
        }

        if ($request->routeIs('projects.index') && filled($request->query('category'))) {
            return 'noindex,follow';
        }

        return 'index,follow';
    }

    private function absoluteUrl(?string $url): ?string
    {
        $url = $this->string($url);
        if ($url === null) {
            return null;
        }

        if (Str::startsWith($url, ['data:'])) {
            return $url;
        }

        if (Str::startsWith($url, '//')) {
            return 'https:'.$url;
        }

        $base = rtrim((string) (config('app.url') ?: url('/')), '/');

        if (Str::startsWith($url, ['http://', 'https://'])) {
            $appHost = parse_url($base, PHP_URL_HOST);
            $urlHost = parse_url($url, PHP_URL_HOST);
            $localHosts = [$appHost, parse_url((string) url('/'), PHP_URL_HOST), 'localhost', '127.0.0.1', '::1'];

            if (in_array($urlHost, array_filter($localHosts), true)) {
                $path = parse_url($url, PHP_URL_PATH) ?: '/';
                $query = parse_url($url, PHP_URL_QUERY);
                $fragment = parse_url($url, PHP_URL_FRAGMENT);

                return $base.$path.($query ? '?'.$query : '').($fragment ? '#'.$fragment : '');
            }

            return $url;
        }

        return $base.'/'.ltrim($url, '/');
    }

    /**
     * @param  array<int, array<string, mixed>>  $schema
     * @return array<int, array<string, mixed>>
     */
    private function mergeGlobalSchema(array $schema, CompanySetting $settings, bool $includeGlobal): array
    {
        if (! $includeGlobal) {
            return array_values($schema);
        }

        $global = array_filter([
            $this->schemaBuilder->organization($settings),
            $this->schemaBuilder->website($settings),
        ]);

        foreach ($global as $item) {
            $type = $item['@type'] ?? null;
            if (is_string($type) && $this->hasSchemaType($schema, $type)) {
                continue;
            }
            $schema[] = $item;
        }

        return array_values($schema);
    }

    /**
     * @param  array<int, array<string, mixed>>  $schema
     */
    private function hasSchemaType(array $schema, string $type): bool
    {
        foreach ($schema as $item) {
            if (($item['@type'] ?? null) === $type) {
                return true;
            }
        }

        return false;
    }

    private function string(mixed $value): ?string
    {
        if (! is_string($value)) {
            return null;
        }

        $value = trim(strip_tags($value));

        return $value === '' ? null : $value;
    }
}
