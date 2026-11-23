@props(['team'])

<article {{ $attributes->merge(['class' => 'overflow-hidden rounded border border-steel-200 bg-white']) }}>
    <img src="{{ $team->photoThumbUrl() ?? 'https://images.unsplash.com/photo-1500648767791-00dcc994a43e?auto=format&fit=crop&w=900&q=80' }}" alt="{{ $team->photo_alt_text ?: $team->name }}" class="h-72 w-full object-cover" width="900" height="1200" loading="lazy" decoding="async">
    <div class="p-6">
        <h3 class="font-display text-2xl font-semibold uppercase text-navy-950">{{ $team->name }}</h3>
        <p class="mt-1 text-sm font-medium uppercase tracking-wide text-accent-600">{{ $team->position }}</p>
        <p class="mt-4 text-sm leading-7 text-steel-500">{{ $team->biography }}</p>
    </div>
</article>
