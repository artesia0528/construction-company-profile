@props(['title', 'open' => false, 'name' => null])

<details
    class="group rounded border border-steel-200 bg-white"
    @if ($name !== null) name="{{ $name }}" @endif
    @if ($open) open @endif
>
    <summary class="flex cursor-pointer list-none items-center justify-between gap-4 px-6 py-5 font-display text-xl font-semibold uppercase text-navy-950 transition hover:text-accent-600 focus:outline-none focus:ring-2 focus:ring-accent-500 focus:ring-inset [&::-webkit-details-marker]:hidden">
        <span>{{ $title }}</span>
        <x-heroicon-o-chevron-down class="h-5 w-5 shrink-0 text-accent-600 transition-transform duration-200 group-open:rotate-180" aria-hidden="true" />
    </summary>
    <div class="px-6 pb-5 text-sm leading-7 text-steel-700">
        {{ $slot }}
    </div>
</details>
