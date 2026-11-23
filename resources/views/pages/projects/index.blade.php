<x-layouts.app title="Projects" description="Browse Nusantara Build Construction project portfolio across commercial, residential, and infrastructure sectors." :seo="$seo">
    <x-sections.page-banner eyebrow="Our Projects" title="Proven Results Across Every Sector" description="Explore our completed and ongoing projects delivered with quality and precision." :breadcrumbs="[['url' => route('home'), 'label' => 'Home'], ['label' => 'Projects']]" />

    <section class="section-pad">
        <div class="container-site" data-project-filter data-project-filter-url="{{ route('projects.index') }}">
            <div class="mb-10 flex flex-wrap gap-3" role="navigation" aria-label="Project categories">
                <a href="{{ route('projects.index') }}" data-project-filter-link data-category="" @if($selectedCategory === '') aria-current="true" @endif class="rounded border px-4 py-2 text-sm font-semibold uppercase tracking-wide {{ $selectedCategory === '' ? 'border-accent-500 bg-accent-500 text-navy-950' : 'border-steel-200 text-steel-700 hover:border-accent-500' }}">All</a>
                @foreach ($categories as $category)
                    <a href="{{ route('projects.index', ['category' => $category]) }}" data-project-filter-link data-category="{{ $category }}" @if($selectedCategory === $category) aria-current="true" @endif class="rounded border px-4 py-2 text-sm font-semibold uppercase tracking-wide {{ $selectedCategory === $category ? 'border-accent-500 bg-accent-500 text-navy-950' : 'border-steel-200 text-steel-700 hover:border-accent-500' }}">{{ $category }}</a>
                @endforeach
            </div>

            <p class="sr-only" aria-live="polite" data-project-filter-status></p>
            <div class="mb-4 hidden rounded border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700" data-project-filter-feedback role="status"></div>

            <div data-project-filter-listing aria-busy="false">
                @include('pages.projects.partials.listing')
            </div>
        </div>
    </section>
</x-layouts.app>
