@props(['eyebrow' => null, 'title', 'description' => null, 'light' => false, 'align' => 'left'])

@php
    $alignClass = $align === 'center' ? 'mx-auto text-center' : '';
    $titleColor = $light ? 'text-white' : 'text-navy-950';
    $descColor = $light ? 'text-white/75' : 'text-steel-500';
@endphp

<div {{ $attributes->merge(['class' => 'max-w-3xl '.$alignClass]) }}>
    @if ($eyebrow)
        <p class="font-display text-sm font-semibold uppercase tracking-[0.25em] text-accent-500">{{ $eyebrow }}</p>
    @endif
    <h2 class="mt-3 font-display text-4xl font-semibold uppercase leading-tight md:text-5xl {{ $titleColor }}">{{ $title }}</h2>
    @if ($description)
        <p class="mt-4 text-base leading-7 {{ $descColor }}">{{ $description }}</p>
    @endif
</div>
