<?php

namespace App\Http\Controllers;

use App\Models\CompanySetting;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProjectController extends Controller
{
    public function index(Request $request): View
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

        return view('pages.projects.index', [
            'companySetting' => CompanySetting::current(),
            'projects' => $projects,
            'categories' => $categories,
            'selectedCategory' => $category,
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
}
