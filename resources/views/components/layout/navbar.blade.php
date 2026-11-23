@props(['settings'])

@php
    $links = [
        ['label' => 'Home', 'route' => 'home'],
        ['label' => 'About', 'route' => 'about'],
        ['label' => 'Services', 'route' => 'services.index'],
        ['label' => 'Projects', 'route' => 'projects.index'],
        ['label' => 'Contact', 'route' => 'contact'],
    ];
@endphp

<header class="sticky top-0 z-50 border-b border-white/10 bg-navy-950/95 text-white backdrop-blur" data-mobile-nav>
    <div class="container-site flex h-20 items-center justify-between gap-6">
        <a href="{{ route('home') }}" class="flex items-center gap-3">
            @if ($settings->logoUrl())
                <img src="{{ $settings->logoUrl() }}" alt="{{ $settings->company_name }}" class="h-11 w-auto" width="160" height="44" loading="eager" fetchpriority="high" decoding="async">
            @else
                <div class="flex h-11 w-11 items-center justify-center rounded bg-accent-500 font-display text-xl font-bold text-navy-950">NB</div>
            @endif
            <div>
                <p class="font-display text-2xl font-semibold leading-none">{{ $settings->company_name }}</p>
                <p class="text-xs uppercase tracking-[0.2em] text-steel-200">Construction Company</p>
            </div>
        </a>

        <nav class="hidden items-center gap-8 md:flex" aria-label="Primary navigation">
            @foreach ($links as $link)
                <a href="{{ route($link['route']) }}"
                   class="text-sm font-semibold uppercase tracking-wide transition {{ request()->routeIs($link['route'].'*') ? 'text-accent-500' : 'text-white/90 hover:text-accent-500' }}">
                    {{ $link['label'] }}
                </a>
            @endforeach
        </nav>

        <div class="hidden md:block">
            <x-ui.button href="{{ route('contact') }}" variant="primary">Get a Quote</x-ui.button>
        </div>

        <button type="button" class="inline-flex items-center justify-center rounded border border-white/20 p-2 md:hidden" data-mobile-nav-toggle aria-label="Toggle menu" aria-expanded="false" aria-controls="mobile-nav">
            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
        </button>
    </div>

    <div id="mobile-nav" class="hidden border-t border-white/10 bg-navy-900 md:hidden" data-mobile-nav-panel>
        <nav class="container-site flex flex-col py-4" aria-label="Mobile navigation">
            @foreach ($links as $link)
                <a href="{{ route($link['route']) }}" class="py-3 text-sm font-semibold uppercase tracking-wide {{ request()->routeIs($link['route'].'*') ? 'text-accent-500' : 'text-white/90' }}">{{ $link['label'] }}</a>
            @endforeach
            <div class="pt-3"><x-ui.button href="{{ route('contact') }}" variant="primary" class="w-full">Get a Quote</x-ui.button></div>
        </nav>
    </div>
</header>
