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

<header
    x-data="{
        open: false,
        scrolled: window.scrollY > 12,
        close(restoreFocus = false) {
            this.open = false;

            if (restoreFocus) {
                this.$refs.toggle.focus();
            }
        },
    }"
    @scroll.window="scrolled = window.scrollY > 12"
    @resize.window="if (window.innerWidth >= 768) { open = false }"
    @keydown.escape.window="if (open) { close(true) }"
    data-scrolled="false"
    :data-scrolled="scrolled ? 'true' : 'false'"
    class="site-header sticky top-0 z-50 border-b border-white/10 bg-navy-950/95 text-white backdrop-blur transition-[background-color,box-shadow,border-color] duration-300 data-[scrolled=true]:border-white/5 data-[scrolled=true]:bg-navy-950/98 data-[scrolled=true]:shadow-lg"
>
    <div class="container-site flex h-20 items-center justify-between gap-6" data-motion="stagger" data-motion-duration="260">
        <a href="{{ route('home') }}" class="flex items-center gap-3 rounded focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-accent-500 focus-visible:ring-offset-2 focus-visible:ring-offset-navy-950" data-motion-item="true">
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

        <nav class="hidden items-center gap-8 md:flex" aria-label="Primary navigation" data-motion-item="true">
            @foreach ($links as $link)
                <a href="{{ route($link['route']) }}" wire:navigate
                   @if (request()->routeIs($link['route'].'*')) aria-current="page" @endif
                   class="relative text-sm font-semibold uppercase tracking-wide transition-colors after:absolute after:-bottom-1 after:left-0 after:h-0.5 after:w-full after:origin-left after:scale-x-0 after:bg-accent-500 after:transition-transform after:duration-200 hover:text-accent-500 hover:after:scale-x-100 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-accent-500 focus-visible:ring-offset-2 focus-visible:ring-offset-navy-950 {{ request()->routeIs($link['route'].'*') ? 'text-accent-500 after:scale-x-100' : 'text-white/90' }}">
                    {{ $link['label'] }}
                </a>
            @endforeach
        </nav>

        <div class="hidden md:block" data-motion-item="true">
            <x-ui.button href="{{ route('contact') }}" wire:navigate variant="primary">Get a Quote</x-ui.button>
        </div>

        <button
            type="button"
            x-ref="toggle"
            @click="open = ! open"
            @click.away="open = false"
            class="inline-flex items-center justify-center rounded border border-white/20 p-2 transition-colors hover:border-accent-500 hover:text-accent-500 focus:outline-none focus:ring-2 focus:ring-accent-500 md:hidden"
            aria-label="Toggle menu"
            :aria-label="open ? 'Close menu' : 'Open menu'"
            aria-controls="mobile-nav"
            :aria-expanded="open ? 'true' : 'false'"
            data-motion-item="true"
        >
            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
        </button>
    </div>

    <div
        id="mobile-nav"
        x-cloak
        x-show="open"
        x-transition:enter="transition duration-200 ease-out"
        x-transition:enter-start="opacity-0 -translate-y-2"
        x-transition:enter-end="opacity-100 translate-y-0"
        x-transition:leave="transition duration-150 ease-in"
        x-transition:leave-start="opacity-100 translate-y-0"
        x-transition:leave-end="opacity-0 -translate-y-2"
        class="mobile-nav-panel absolute inset-x-0 top-full max-h-[calc(100vh-5rem)] overflow-y-auto border-t border-white/10 bg-navy-900 shadow-xl md:hidden"
    >
        <nav class="container-site flex flex-col py-4" aria-label="Mobile navigation">
            @foreach ($links as $link)
                <a href="{{ route($link['route']) }}" wire:navigate class="border-l-2 border-transparent py-3 pl-3 text-sm font-semibold uppercase tracking-wide transition-colors hover:border-accent-500/60 hover:text-accent-500 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-accent-500 focus-visible:ring-offset-2 focus-visible:ring-offset-navy-900 {{ request()->routeIs($link['route'].'*') ? 'border-accent-500 text-accent-500' : 'text-white/90' }}" @if (request()->routeIs($link['route'].'*')) aria-current="page" @endif>{{ $link['label'] }}</a>
            @endforeach
            <div class="pt-3"><x-ui.button href="{{ route('contact') }}" wire:navigate variant="primary" class="w-full">Get a Quote</x-ui.button></div>
        </nav>
    </div>
</header>
