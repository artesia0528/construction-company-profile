<x-layouts.app :title="$service->name" :description="$service->short_description" :seo="$seo">
    <x-sections.page-banner eyebrow="Service Detail" :title="$service->name" :description="$service->short_description" :breadcrumbs="[['url' => route('home'), 'label' => 'Home'], ['url' => route('services.index'), 'label' => 'Services'], ['label' => $service->name]]" />

    <section class="section-pad">
        <div class="container-site grid gap-12 lg:grid-cols-3">
            <div class="lg:col-span-2">
                <img src="{{ $service->thumbnailUrl() ?? 'https://images.unsplash.com/photo-1504307651254-35680f356dfd?auto=format&fit=crop&w=1400&q=80' }}" alt="{{ $service->thumbnail_alt_text ?: $service->name }}" class="h-[420px] w-full rounded object-cover" width="1400" height="933" loading="eager" fetchpriority="high" decoding="async" data-motion="reveal" data-motion-variant="fade-in">
                <div class="mt-8 space-y-5 leading-8 text-steel-700" data-motion="reveal" data-motion-delay="80">
                    @foreach (preg_split('/\n\s*\n/', (string) $service->full_description) as $paragraph)
                        @if (trim($paragraph) !== '')
                            <p>{{ trim($paragraph) }}</p>
                        @endif
                    @endforeach
                </div>
            </div>
            <aside class="space-y-6">
                <div class="rounded border border-steel-200 bg-steel-100 p-6" data-motion="reveal">
                    <p class="font-display text-2xl font-semibold uppercase text-navy-950">Other Services</p>
                    <ul class="mt-4 space-y-3">
                        @foreach ($otherServices as $other)
                            <li><a href="{{ route('services.show', $other) }}" wire:navigate class="text-sm font-medium text-steel-700 hover:text-accent-600">{{ $other->name }}</a></li>
                        @endforeach
                    </ul>
                </div>
                <div class="rounded bg-navy-950 p-6 text-white" data-motion="reveal" data-motion-delay="80">
                    <p class="font-display text-2xl font-semibold uppercase">Need This Service?</p>
                    <p class="mt-3 text-sm leading-7 text-white/70">Discuss your project requirements with our construction specialists.</p>
                    <div class="mt-5"><x-ui.button href="{{ route('contact') }}" wire:navigate>Contact Us</x-ui.button></div>
                </div>
            </aside>
        </div>
    </section>
</x-layouts.app>
