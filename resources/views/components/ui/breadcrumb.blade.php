@props(['items' => []])

<nav aria-label="Breadcrumb" {{ $attributes->merge(['class' => 'text-sm text-white/70']) }}>
    <ol class="flex flex-wrap items-center gap-2">
        @foreach ($items as $item)
            <li class="flex items-center gap-2">
                @if (! $loop->first)<span>/</span>@endif
                @if (isset($item['url']))
                    <a href="{{ $item['url'] }}" wire:navigate class="hover:text-accent-500">{{ $item['label'] }}</a>
                @else
                    <span class="text-white">{{ $item['label'] }}</span>
                @endif
            </li>
        @endforeach
    </ol>
</nav>
