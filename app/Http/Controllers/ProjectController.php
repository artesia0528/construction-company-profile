<?php

namespace App\Http\Controllers;

use App\Models\CompanySetting;
use App\Models\Project;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\View\View;

class ProjectController extends Controller
{
    public function index(Request $request): View|JsonResponse
    {
        $data = $this->projectListing($request);

        if ($request->expectsJson()) {
            return response()->json([
                'html' => view('pages.projects.partials.listing', $data)->render(),
                'url' => $request->fullUrl(),
                'category' => $data['selectedCategory'],
            ]);
        }

        return view('pages.projects.index', [
            ...$data,
            'companySetting' => CompanySetting::current(),
        ]);
    }

    public function show(Project $project): View
    {
        abort_unless($project->is_published, 404);

        $project->load('images');

        return view('pages.projects.show', [
            'companySetting' => CompanySetting::current(),
            'project' => $project,
            'relatedProjects' => Project::query()
                ->published()
                ->whereKeyNot($project->id)
                ->when($project->category, fn ($query) => $query->where('category', $project->category))
                ->latestCompleted()
                ->limit(3)
                ->get(),
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
