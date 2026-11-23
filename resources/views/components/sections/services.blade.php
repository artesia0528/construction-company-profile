@props(['services', 'title' => 'Our Services', 'description' => 'Comprehensive construction solutions tailored to commercial, residential, and infrastructure needs.'])

<section class="section-pad bg-steel-100">
    <div class="container-site">
        <div class="mb-12 flex flex-col gap-6 md:flex-row md:items-end md:justify-between">
            <x-ui.section-heading eyebrow="What We Do" :title="$title" :description="$description" />
            <x-ui.button href="{{ route('services.index') }}" variant="outline">All Services</x-ui.button>
        </div>
        <div class="grid gap-6 md:grid-cols-2 xl:grid-cols-3">
            @foreach ($services as $service)
                <div x-reveal.fade-up.{{ $loop->index * 80 }}>
                    <x-cards.service-card :service="$service" />
                </div>
            @endforeach
        </div>
    </div>
</section>
