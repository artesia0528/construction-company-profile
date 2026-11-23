<x-layouts.app title="Projects" description="Browse Nusantara Build Construction project portfolio across commercial, residential, and infrastructure sectors." :seo="$seo">
    <x-sections.page-banner eyebrow="Our Projects" title="Proven Results Across Every Sector" description="Explore our completed and ongoing projects delivered with quality and precision." :breadcrumbs="[['url' => route('home'), 'label' => 'Home'], ['label' => 'Projects']]" />

    <section class="section-pad">
        <div class="container-site">
            <livewire:projects.project-index />
        </div>
    </section>
</x-layouts.app>
