@props(['project'])

<article {{ $attributes->merge(['class' => 'group overflow-hidden rounded border border-steel-200 bg-white shadow-sm transition hover:-translate-y-1 hover:shadow-lg']) }}>
    <a href="{{ route('projects.show', $project) }}" class="block overflow-hidden">
        <img src="{{ $project->coverImageThumbUrl() ?? 'https://images.unsplash.com/photo-1504307651254-35680f356dfd?auto=format&fit=crop&w=1200&q=80' }}" alt="{{ $project->cover_alt_text ?: $project->title }}" class="h-64 w-full object-cover transition duration-500 group-hover:scale-105" width="1200" height="800" loading="lazy" decoding="async">
    </a>
    <div class="p-6">
        <p class="text-xs font-semibold uppercase tracking-[0.2em] text-accent-600">{{ $project->category }} @if($project->location) • {{ $project->location }} @endif</p>
        <h3 class="mt-3 font-display text-2xl font-semibold uppercase text-navy-950">
            <a href="{{ route('projects.show', $project) }}" class="hover:text-accent-600">{{ $project->title }}</a>
        </h3>
        <p class="mt-3 text-sm leading-7 text-steel-500">{{ $project->short_description }}</p>
        <div class="mt-5 flex items-center justify-between text-sm text-steel-500">
            <span>{{ $project->completion_year }}</span>
            <a href="{{ route('projects.show', $project) }}" class="font-semibold uppercase tracking-wide text-navy-900 hover:text-accent-600">View Details</a>
        </div>
    </div>
</article>
