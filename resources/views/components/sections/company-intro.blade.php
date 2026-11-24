@props(['settings'])

<section class="section-pad">
    <div class="container-site grid items-center gap-12 lg:grid-cols-2">
        <div data-motion="reveal" data-motion-variant="fade-in">
            <img src="{{ $settings->heroImageUrl() ?? 'https://images.unsplash.com/photo-1590644365607-1c5fc09571e5?auto=format&fit=crop&w=1200&q=80' }}" alt="{{ $settings->hero_alt_text ?: ($settings->company_name.' team') }}" class="h-[420px] w-full rounded object-cover shadow-lg" width="1200" height="800" loading="lazy" decoding="async">
        </div>
        <div data-motion="stagger">
            <x-ui.section-heading eyebrow="About Company" :title="$settings->company_name" :description="$settings->company_intro" data-motion-item="true" />
            <p class="mt-6 leading-7 text-steel-500" data-motion-item="true">{{ $settings->company_description }}</p>
            <div class="mt-8 flex flex-wrap gap-4" data-motion-item="true">
                <x-ui.button href="{{ route('about') }}">Learn More</x-ui.button>
                <x-ui.button href="{{ route('contact') }}" variant="outline">Contact Us</x-ui.button>
            </div>
        </div>
    </div>
</section>
