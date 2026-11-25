@props(['title', 'description', 'icon' => 'check'])

@php
    $icons = [
        'check' => 'heroicon-o-check',
        'team' => 'heroicon-o-users',
        'quality' => 'heroicon-o-shield-check',
        'schedule' => 'heroicon-o-clock',
        'safety' => 'heroicon-o-shield-exclamation',
    ];
    $iconName = $icons[$icon] ?? $icons['check'];
@endphp

<div {{ $attributes->merge(['class' => 'rounded border border-steel-200 bg-white p-7 transition duration-200 hover:-translate-y-1 hover:border-accent-500/60 hover:shadow-md']) }}>
    <div class="flex h-12 w-12 items-center justify-center rounded bg-navy-950 text-accent-500">
        <x-dynamic-component :component="$iconName" class="h-6 w-6" aria-hidden="true" />
    </div>
    <h3 class="mt-5 font-display text-2xl font-semibold uppercase text-navy-950">{{ $title }}</h3>
    <p class="mt-3 text-sm leading-7 text-steel-500">{{ $description }}</p>
</div>
