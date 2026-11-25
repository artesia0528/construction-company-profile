@props(['service'])

@php
    $icons = [
        'building-office-2' => 'heroicon-o-building-office-2',
        'home-modern' => 'heroicon-o-home-modern',
        'road' => 'heroicon-o-globe-alt',
        'wrench-screwdriver' => 'heroicon-o-wrench-screwdriver',
        'pencil-square' => 'heroicon-o-pencil-square',
        'clipboard-document-check' => 'heroicon-o-clipboard-document-check',
    ];
    $iconName = $icons[$service->icon] ?? $icons['building-office-2'];
@endphp

<article {{ $attributes->merge(['class' => 'group flex h-full flex-col rounded border border-steel-200 bg-white p-8 transition hover:-translate-y-1 hover:border-accent-500 hover:shadow-lg']) }}>
    <div class="flex h-14 w-14 items-center justify-center rounded bg-accent-500/10 text-accent-600">
        <x-dynamic-component :component="$iconName" class="h-7 w-7" aria-hidden="true" />
    </div>
    <h3 class="mt-6 font-display text-2xl font-semibold uppercase text-navy-950">{{ $service->name }}</h3>
    <p class="mt-3 flex-1 text-sm leading-7 text-steel-500">{{ $service->short_description }}</p>
    <a href="{{ route('services.show', $service) }}" wire:navigate class="mt-6 inline-flex items-center text-sm font-semibold uppercase tracking-wide text-accent-600 hover:text-accent-500">
        View Service Details
    </a>
</article>
