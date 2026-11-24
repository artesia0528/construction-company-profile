@props(['title', 'description'])

<div {{ $attributes->merge(['class' => 'rounded border border-steel-200 bg-white p-7 transition duration-200 hover:-translate-y-1 hover:border-accent-500/60 hover:shadow-md']) }}>
    <div class="flex h-12 w-12 items-center justify-center rounded bg-navy-950 text-accent-500">
        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
    </div>
    <h3 class="mt-5 font-display text-2xl font-semibold uppercase text-navy-950">{{ $title }}</h3>
    <p class="mt-3 text-sm leading-7 text-steel-500">{{ $description }}</p>
</div>
