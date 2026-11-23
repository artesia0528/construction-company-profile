<div class="grid gap-6 md:grid-cols-2 xl:grid-cols-3" data-project-grid>
    @forelse ($projects as $project)
        <x-cards.project-card :project="$project" />
    @empty
        <div class="rounded border border-dashed border-steel-200 p-10 text-center text-steel-500 md:col-span-2 xl:col-span-3">No projects found for this category.</div>
    @endforelse
</div>

<div class="mt-10" data-project-pagination>{{ $projects->links() }}</div>
