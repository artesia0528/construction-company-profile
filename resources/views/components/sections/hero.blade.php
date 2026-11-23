@props(['settings'])

<section class="relative overflow-hidden bg-navy-950 text-white">
    <img src="{{ $settings->heroImageUrl() ?? 'https://images.unsplash.com/photo-1503387762-592deb58ef4e?auto=format&fit=crop&w=1600&q=80' }}" alt="{{ $settings->hero_alt_text ?: ($settings->company_name.' hero') }}" class="absolute inset-0 h-full w-full object-cover" width="1600" height="900" loading="eager" fetchpriority="high" decoding="async">
    <div class="absolute inset-0 bg-[linear-gradient(to_right,rgba(11,18,32,.92),rgba(11,18,32,.45))]"></div>
    <div class="container-site relative grid min-h-[78vh] items-center gap-12 py-24 lg:grid-cols-2">
        <div x-reveal.fade-up>
            <p class="font-display text-sm font-semibold uppercase tracking-[0.3em] text-accent-500">Professional Construction Partner</p>
            <h1 class="mt-5 max-w-3xl font-display text-5xl font-semibold uppercase leading-[0.95] md:text-7xl">{{ $settings->hero_headline }}</h1>
            <p class="mt-6 max-w-2xl text-base leading-8 text-white/75 md:text-lg">{{ $settings->hero_description }}</p>
            <div class="mt-10 flex flex-wrap gap-4">
                <x-ui.button href="{{ route('contact') }}">{{ $settings->hero_primary_cta_label ?: 'Contact Us' }}</x-ui.button>
                <x-ui.button href="{{ route('projects.index') }}" variant="secondary">{{ $settings->hero_secondary_cta_label ?: 'View Our Projects' }}</x-ui.button>
            </div>
        </div>
        <div class="hidden lg:block" x-reveal.fade-left.120>
            <div class="ml-auto max-w-md rounded border border-white/10 bg-white/5 p-8 backdrop-blur">
                <p class="font-display text-2xl font-semibold uppercase">Trusted Construction Excellence</p>
                <p class="mt-4 text-sm leading-7 text-white/70">From concept to completion, we deliver safe, efficient, and high-quality construction solutions for businesses and communities.</p>
                <div class="mt-6 grid grid-cols-2 gap-4 text-sm">
                    <div class="rounded bg-navy-900/80 p-4"><p class="font-display text-3xl text-accent-500">250+</p><p class="mt-1 text-white/70">Projects Delivered</p></div>
                    <div class="rounded bg-navy-900/80 p-4"><p class="font-display text-3xl text-accent-500">15+</p><p class="mt-1 text-white/70">Years Experience</p></div>
                </div>
            </div>
        </div>
    </div>
</section>
