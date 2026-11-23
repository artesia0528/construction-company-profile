<?php

namespace App\Support\Seo;

use App\Models\CompanySetting;
use App\Models\Project;
use App\Models\Service;

class SitemapBuilder
{
    /**
     * @return array<int, array{loc: string, lastmod?: string}>
     */
    public function entries(): array
    {
        $globalNoindex = (bool) CompanySetting::current()->noindex;

        $entries = $globalNoindex
            ? collect()
            : collect([
                ['loc' => route('home'), 'lastmod' => null],
                ['loc' => route('about'), 'lastmod' => null],
                ['loc' => route('services.index'), 'lastmod' => null],
                ['loc' => route('projects.index'), 'lastmod' => null],
                ['loc' => route('contact'), 'lastmod' => null],
            ]);

        $services = Service::query()
            ->active()
            ->where('noindex', false)
            ->get(['slug', 'updated_at'])
            ->map(fn (Service $service): array => [
                'loc' => route('services.show', $service),
                'lastmod' => $service->updated_at?->toAtomString(),
            ]);

        $projects = Project::query()
            ->published()
            ->where('noindex', false)
            ->get(['slug', 'updated_at'])
            ->map(fn (Project $project): array => [
                'loc' => route('projects.show', $project),
                'lastmod' => $project->updated_at?->toAtomString(),
            ]);

        return $entries
            ->concat($services)
            ->concat($projects)
            ->filter(fn (array $entry): bool => filled($entry['loc'] ?? null))
            ->values()
            ->all();
    }
}
