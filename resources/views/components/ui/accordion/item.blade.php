@props(['title', 'open' => false])

@php
    $id = 'accordion-'.\Illuminate\Support\Str::uuid();
@endphp

<div class="rounded border border-steel-200 bg-white" data-accordion-item data-open="{{ $open ? 'true' : 'false' }}">
    <h3>
        <button
            type="button"
            class="flex w-full items-center justify-between gap-4 px-6 py-5 text-left font-display text-xl font-semibold uppercase text-navy-950 transition hover:text-accent-600 focus:outline-none focus:ring-2 focus:ring-accent-500 focus:ring-inset"
            data-accordion-trigger
            id="{{ $id }}-trigger"
            aria-expanded="{{ $open ? 'true' : 'false' }}"
            aria-controls="{{ $id }}-panel"
        >
            <span>{{ $title }}</span>
            <svg class="h-5 w-5 shrink-0 text-accent-600 transition-transform duration-200 {{ $open ? 'rotate-180' : '' }}" data-accordion-icon fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
        </button>
    </h3>
    <div
        id="{{ $id }}-panel"
        role="region"
        aria-labelledby="{{ $id }}-trigger"
        data-accordion-panel
        class="overflow-hidden"
    >
        <div class="px-6 pb-5 text-sm leading-7 text-steel-700">
            {{ $slot }}
        </div>
    </div>
</div>
