@props(['settings'])

<section class="section-pad">
    <div class="container-site grid items-center gap-12 lg:grid-cols-2">
        <div>
            <img src="{{ $settings->heroImageUrl() ?? 'https://images.unsplash.com/photo-1590644365607-1c5fc09571e5?auto=format&fit=crop&w=1200&q=80' }}" alt="Construction team at work" class="h-[420px] w-full rounded object-cover shadow-lg" loading="lazy">
        </div>
        <div>
            <x-ui.section-heading eyebrow="About Company" :title="$settings->company_name" :description="$settings->company_intro" />
            <p class="mt-6 leading-7 text-steel-500">{{ $settings->company_description }}</p>
            <div class="mt-8 flex flex-wrap gap-4">
                <x-ui.button href="{{ route('about') }}">Learn More</x-ui.button>
                <x-ui.button href="{{ route('contact') }}" variant="outline">Contact Us</x-ui.button>
            </div>
        </div>
    </div>
</section>
