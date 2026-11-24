@props(['projects'])

<section class="section-pad">
    <div class="container-site">
        <div class="mb-12 flex flex-col gap-6 md:flex-row md:items-end md:justify-between">
            <x-ui.section-heading eyebrow="Our Portfolio" title="Featured Projects" description="A selection of projects that reflect our commitment to quality, safety, and reliable delivery." />
            <x-ui.button href="{{ route('projects.index') }}" variant="outline" wire:navigate>View All Projects</x-ui.button>
        </div>
        <div class="grid gap-6 md:grid-cols-2 xl:grid-cols-3" data-motion="stagger" data-motion-delay="80">
            @foreach ($projects as $project)
                <div data-motion-item="true">
                    <x-cards.project-card :project="$project" />
                </div>
            @endforeach
        </div>
    </div>
</section>
