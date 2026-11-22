@props([
    'href' => null,
    'variant' => 'primary',
    'type' => 'button',
])

@php
    $base = 'inline-flex items-center justify-center rounded px-6 py-3 text-sm font-semibold uppercase tracking-wide transition duration-200 focus:outline-none focus:ring-2 focus:ring-accent-500 focus:ring-offset-2';
    $variants = [
        'primary' => 'bg-accent-500 text-navy-950 hover:bg-accent-600',
        'secondary' => 'border border-white/30 bg-white/5 text-white hover:bg-white hover:text-navy-950',
        'outline' => 'border border-navy-900 text-navy-900 hover:bg-navy-900 hover:text-white',
    ];
    $classes = $base.' '.($variants[$variant] ?? $variants['primary']);
@endphp

@if ($href)
    <a href="{{ $href }}" {{ $attributes->merge(['class' => $classes]) }}>{{ $slot }}</a>
@else
    <button type="{{ $type }}" {{ $attributes->merge(['class' => $classes]) }}>{{ $slot }}</button>
@endif
