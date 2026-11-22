<x-layouts.app title="Projects" description="Browse Nusantara Build Construction project portfolio across commercial, residential, and infrastructure sectors.">
    <x-sections.page-banner eyebrow="Our Projects" title="Proven Results Across Every Sector" description="Explore our completed and ongoing projects delivered with quality and precision." :breadcrumbs="[['url' => route('home'), 'label' => 'Home'], ['label' => 'Projects']]" />

    <section class="section-pad">
        <div class="container-site">
            <div class="mb-10 flex flex-wrap gap-3">
                <a href="{{ route('projects.index') }}" class="rounded border px-4 py-2 text-sm font-semibold uppercase tracking-wide {{ $selectedCategory === '' ? 'border-accent-500 bg-accent-500 text-navy-950' : 'border-steel-200 text-steel-700 hover:border-accent-500' }}">All</a>
                @foreach ($categories as $category)
                    <a href="{{ route('projects.index', ['category' => $category]) }}" class="rounded border px-4 py-2 text-sm font-semibold uppercase tracking-wide {{ $selectedCategory === $category ? 'border-accent-500 bg-accent-500 text-navy-950' : 'border-steel-200 text-steel-700 hover:border-accent-500' }}">{{ $category }}</a>
                @endforeach
            </div>

            <div class="grid gap-6 md:grid-cols-2 xl:grid-cols-3">
                @forelse ($projects as $project)
                    <x-cards.project-card :project="$project" />
                @empty
                    <div class="rounded border border-dashed border-steel-200 p-10 text-center text-steel-500 md:col-span-2 xl:col-span-3">No projects found for this category.</div>
                @endforelse
            </div>

            <div class="mt-10">{{ $projects->links() }}</div>
        </div>
    </section>
</x-layouts.app>
