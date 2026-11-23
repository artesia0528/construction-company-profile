<?php

namespace App\Http\Controllers;

use App\Models\CompanySetting;
use App\Models\Project;
use App\Support\Seo\SchemaBuilder;
use App\Support\Seo\SeoResolver;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\View\View;

class ProjectController extends Controller
{
    public function index(Request $request, SeoResolver $seoResolver): View|JsonResponse
    {
        $data = $this->projectListing($request);

        if ($request->expectsJson()) {
            return response()->json([
                'html' => view('pages.projects.partials.listing', $data)->render(),
                'url' => $request->fullUrl(),
                'category' => $data['selectedCategory'],
            ]);
        }

        $companySetting = CompanySetting::current();
        $isFiltered = $data['selectedCategory'] !== '';

        $breadcrumbs = [
            ['label' => 'Home', 'url' => route('home')],
            ['label' => 'Projects', 'url' => route('projects.index')],
        ];

        return view('pages.projects.index', [
            ...$data,
            'companySetting' => $companySetting,
            'seo' => $seoResolver->resolve($request, $companySetting, [
                'title' => 'Projects',
                'description' => 'Browse '.$companySetting->company_name.' project portfolio across commercial, residential, and infrastructure sectors.',
                'isFilteredProjects' => $isFiltered,
                'type' => 'website',
                'breadcrumbs' => $breadcrumbs,
                'schema' => [app(SchemaBuilder::class)->webPage('CollectionPage', 'Projects', route('projects.index'), 'Browse '.$companySetting->company_name.' project portfolio across commercial, residential, and infrastructure sectors.')],
            ]),
        ]);
    }

    public function show(Request $request, Project $project, SeoResolver $seoResolver): View
    {
        abort_unless($project->is_published, 404);

        $project->load('images');
        $companySetting = CompanySetting::current();

        $breadcrumbs = [
            ['label' => 'Home', 'url' => route('home')],
            ['label' => 'Projects', 'url' => route('projects.index')],
            ['label' => $project->title, 'url' => route('projects.show', $project)],
        ];

        return view('pages.projects.show', [
            'companySetting' => $companySetting,
            'project' => $project,
            'relatedProjects' => Project::query()
                ->published()
                ->whereKeyNot($project->id)
                ->when($project->category, fn ($query) => $query->where('category', $project->category))
                ->latestCompleted()
                ->limit(3)
                ->get(),
            'seo' => $seoResolver->resolve($request, $companySetting, [
                'title' => $project->title,
                'description' => $project->short_description,
                'image' => $project->coverImageUrl(),
                'imageAlt' => $project->cover_alt_text ?: $project->title,
                'seoTitle' => $project->seo_title,
                'seoDescription' => $project->seo_description,
                'seoImage' => $project->seoImageUrl(),
                'canonicalUrl' => $project->canonical_url,
                'noindex' => (bool) $project->noindex,
                'type' => 'article',
                'breadcrumbs' => $breadcrumbs,
                'schema' => [app(SchemaBuilder::class)->project($project, route('projects.show', $project))],
            ]),
        ]);
    }

    /**
     * @return array{projects: LengthAwarePaginator, categories: Collection<int, string>, selectedCategory: string}
     */
    private function projectListing(Request $request): array
    {
        $category = $request->string('category')->toString();

        $projects = Project::query()
            ->published()
            ->when($category !== '', fn ($query) => $query->where('category', $category))
            ->latestCompleted()
            ->paginate(9)
            ->withQueryString();

        $categories = Project::query()
            ->published()
            ->whereNotNull('category')
            ->distinct()
            ->orderBy('category')
            ->pluck('category');

        return [
            'projects' => $projects,
            'categories' => $categories,
            'selectedCategory' => $category,
        ];
    }
}
