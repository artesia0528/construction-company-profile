@props(['service'])

<article {{ $attributes->merge(['class' => 'group flex h-full flex-col rounded border border-steel-200 bg-white p-8 transition hover:-translate-y-1 hover:border-accent-500 hover:shadow-lg']) }}>
    <div class="flex h-14 w-14 items-center justify-center rounded bg-accent-500/10 text-accent-600">
        <svg class="h-7 w-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M3 21h18M5 21V7l8-4 8 4v14M9 10h.01M15 10h.01M9 14h.01M15 14h.01"/></svg>
    </div>
    <h3 class="mt-6 font-display text-2xl font-semibold uppercase text-navy-950">{{ $service->name }}</h3>
    <p class="mt-3 flex-1 text-sm leading-7 text-steel-500">{{ $service->short_description }}</p>
    <a href="{{ route('services.show', $service) }}" wire:navigate class="mt-6 inline-flex items-center text-sm font-semibold uppercase tracking-wide text-accent-600 hover:text-accent-500">
        Learn More <span class="ml-2">→</span>
    </a>
</article>
