<div>
    <div class="mb-10 flex flex-wrap gap-3" role="navigation" aria-label="Project categories">
        <a href="{{ route('projects.index') }}" wire:click.prevent="setCategory('')" @if($this->category === '') aria-current="true" @endif class="rounded border px-4 py-2 text-sm font-semibold uppercase tracking-wide {{ $this->category === '' ? 'border-accent-500 bg-accent-500 text-navy-950' : 'border-steel-200 text-steel-700 hover:border-accent-500' }}">All</a>
        @foreach ($this->categories as $category)
            <a href="{{ route('projects.index', ['category' => $category]) }}" wire:click.prevent="setCategory(@js($category))" @if($this->category === $category) aria-current="true" @endif class="rounded border px-4 py-2 text-sm font-semibold uppercase tracking-wide {{ $this->category === $category ? 'border-accent-500 bg-accent-500 text-navy-950' : 'border-steel-200 text-steel-700 hover:border-accent-500' }}">{{ $category }}</a>
        @endforeach
    </div>

    <p class="sr-only" aria-live="polite" wire:loading>Memuat proyek…</p>

    <div wire:loading.attr="aria-busy" wire:loading.class="pointer-events-none opacity-60" aria-busy="false" class="transition-opacity duration-200">
        <div class="grid gap-6 md:grid-cols-2 xl:grid-cols-3">
            @forelse ($this->projects as $project)
                <x-cards.project-card :project="$project" wire:key="{{ $project->id }}" />
            @empty
                <div class="rounded border border-dashed border-steel-200 p-10 text-center text-steel-500 md:col-span-2 xl:col-span-3">No projects found for this category.</div>
            @endforelse
        </div>

        <div class="livewire-pagination mt-10">{{ $this->projects->links() }}</div>

        <noscript>
            <div class="mt-10">{{ $this->projects->links('pagination::tailwind') }}</div>
        </noscript>
    </div>
</div>
