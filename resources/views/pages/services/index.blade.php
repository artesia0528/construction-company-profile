<x-layouts.app title="Services" description="Explore Nusantara Build Construction services for building, infrastructure, renovation, and project management.">
    <x-sections.page-banner eyebrow="Our Services" title="Construction Services Built for Performance" description="From planning to delivery, we provide reliable construction services for diverse project needs." :breadcrumbs="[['url' => route('home'), 'label' => 'Home'], ['label' => 'Services']]" />
    <section class="section-pad">
        <div class="container-site">
            <div class="grid gap-6 md:grid-cols-2 xl:grid-cols-3">
                @foreach ($services as $service)
                    <x-cards.service-card :service="$service" />
                @endforeach
            </div>
        </div>
    </section>
    <x-sections.cta :settings="$companySetting" />
</x-layouts.app>
