<?php

namespace App\Http\Controllers;

use App\Models\CompanySetting;
use App\Models\Project;
use App\Support\Seo\SchemaBuilder;
use App\Support\Seo\SeoResolver;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    public function index(Request $request, SeoResolver $seoResolver, SchemaBuilder $schemaBuilder): View
    {
        $companySetting = CompanySetting::current();
        $description = 'Browse '.$companySetting->company_name.' project portfolio across commercial, residential, and infrastructure sectors.';

        return view('pages.projects.index', [
            'companySetting' => $companySetting,
            'seo' => $seoResolver->resolve($request, $companySetting, [
                'title' => 'Projects',
                'description' => $description,
                'isFilteredProjects' => $request->string('category')->toString() !== '',
                'noindex' => (bool) $companySetting->noindex,
                'type' => 'website',
                'breadcrumbs' => [
                    ['label' => 'Home', 'url' => route('home')],
                    ['label' => 'Projects', 'url' => route('projects.index')],
                ],
                'schema' => [$schemaBuilder->webPage('CollectionPage', 'Projects', route('projects.index'), $description)],
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
}
