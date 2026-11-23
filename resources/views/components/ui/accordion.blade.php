@props(['single' => false])

<div {{ $attributes->merge(['class' => 'space-y-3']) }} data-accordion data-accordion-single="{{ $single ? 'true' : 'false' }}">
    {{ $slot }}
</div>
