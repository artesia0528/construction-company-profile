@props(['eyebrow' => null, 'title', 'description' => null, 'breadcrumbs' => []])

<section class="relative overflow-hidden bg-navy-950 py-24 text-white">
    <div class="absolute inset-0 bg-[linear-gradient(to_right,rgba(11,18,32,.95),rgba(11,18,32,.7)),url('https://images.unsplash.com/photo-1541888946425-d81bb19240f5?auto=format&fit=crop&w=1600&q=80')] bg-cover bg-center"></div>
    <div class="container-site relative" data-motion="stagger" data-motion-duration="260">
        @if ($eyebrow)<p class="font-display text-sm font-semibold uppercase tracking-[0.25em] text-accent-500" data-motion-item="true">{{ $eyebrow }}</p>@endif
        <h1 class="mt-3 max-w-4xl font-display text-5xl font-semibold uppercase leading-none md:text-7xl" data-motion-item="true" wire:transition.navigate="match-element">{{ $title }}</h1>
        @if ($description)<p class="mt-5 max-w-2xl text-base leading-7 text-white/75" data-motion-item="true">{{ $description }}</p>@endif
        @if ($breadcrumbs)
            <x-ui.breadcrumb :items="$breadcrumbs" class="mt-8" data-motion-item="true" />
        @endif
    </div>
</section>
