@props(['statistic', 'light' => false])

@php
    $raw = (string) $statistic->value;
    preg_match('/-?\d+(?:[.,]\d+)?/', $raw, $matches);
    $numericValue = $matches[0] ?? $raw;
    $suffix = trim(str_replace($matches[0] ?? '', '', $raw));
@endphp

<div {{ $attributes->merge(['class' => 'rounded border p-6 '.($light ? 'border-white/10 bg-white/5' : 'border-steel-200 bg-white')]) }}>
    <p
        class="font-display text-4xl font-semibold {{ $light ? 'text-accent-500' : 'text-navy-950' }}"
        data-counter
        data-counter-value="{{ $numericValue }}"
        data-counter-suffix="{{ $suffix }}"
        data-counter-duration="1400"
    >{{ $statistic->value }}</p>
    <p class="mt-2 text-sm font-medium uppercase tracking-wide {{ $light ? 'text-white/75' : 'text-steel-500' }}">{{ $statistic->label }}</p>
</div>
