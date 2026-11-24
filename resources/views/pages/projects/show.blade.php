<x-layouts.app :title="$project->title" :description="$project->short_description" :seo="$seo">
    <x-sections.page-banner eyebrow="Project Detail" :title="$project->title" :description="$project->short_description" :breadcrumbs="[['url' => route('home'), 'label' => 'Home'], ['url' => route('projects.index'), 'label' => 'Projects'], ['label' => $project->title]]" />

    <section class="section-pad">
        <div class="container-site grid gap-12 lg:grid-cols-3">
            <div class="lg:col-span-2">
                <img src="{{ $project->coverImageUrl() ?? 'https://images.unsplash.com/photo-1504307651254-35680f356dfd?auto=format&fit=crop&w=1400&q=80' }}" alt="{{ $project->cover_alt_text ?: $project->title }}" class="h-[440px] w-full rounded object-cover" width="1400" height="933" loading="eager" fetchpriority="high" decoding="async" data-motion="reveal" data-motion-variant="fade-in">
                <div class="mt-8 space-y-5 leading-8 text-steel-700" data-motion="reveal" data-motion-delay="80">
                    @foreach (preg_split('/\n\s*\n/', (string) $project->full_description) as $paragraph)
                        @if (trim($paragraph) !== '')
                            <p>{{ trim($paragraph) }}</p>
                        @endif
                    @endforeach
                </div>

                @if ($project->images->isNotEmpty())
                    @php
                        $galleryImages = $project->images->map(fn ($image) => [
                            'src' => $image->url() ?: '',
                            'alt' => $image->alt_text ?: $project->title,
                            'caption' => $image->alt_text ?: '',
                        ])->values()->all();
                    @endphp

                    <div
                        class="mt-12"
                        x-data="{
                            open: false,
                            index: 0,
                            images: @js($galleryImages),
                            touchStartX: 0,
                            touchStartY: 0,
                            get current() { return this.images[this.index] ?? null; },
                            get many() { return this.images.length > 1; },
                            openAt(index, dialog) {
                                this.index = index;
                                this.open = true;
                                dialog.showModal();
                            },
                            close(dialog) { dialog.close(); },
                            next() {
                                if (this.many) {
                                    this.index = (this.index + 1) % this.images.length;
                                }
                            },
                            prev() {
                                if (this.many) {
                                    this.index = (this.index - 1 + this.images.length) % this.images.length;
                                }
                            },
                            onKeydown(event) {
                                if (! this.open) {
                                    return;
                                }

                                if (event.key === 'ArrowLeft') {
                                    event.preventDefault();
                                    this.prev();
                                }

                                if (event.key === 'ArrowRight') {
                                    event.preventDefault();
                                    this.next();
                                }
                            },
                            onTouchStart(event) {
                                const touch = event.changedTouches[0];
                                this.touchStartX = touch.clientX;
                                this.touchStartY = touch.clientY;
                            },
                            onTouchEnd(event) {
                                const touch = event.changedTouches[0];
                                const deltaX = touch.clientX - this.touchStartX;
                                const deltaY = touch.clientY - this.touchStartY;

                                if (Math.abs(deltaX) < 40 || Math.abs(deltaX) < Math.abs(deltaY)) {
                                    return;
                                }

                                if (deltaX > 0) {
                                    this.prev();
                                } else {
                                    this.next();
                                }
                            },
                        }"
                        @keydown.window="onKeydown($event)"
                        x-effect="document.body.classList.toggle('lightbox-open', open)"
                    >
                        <h2 class="font-display text-3xl font-semibold uppercase text-navy-950">Project Gallery</h2>
                        <div class="mt-6 grid gap-4 sm:grid-cols-2" data-motion="stagger" data-motion-delay="80">
                            @foreach ($project->images as $image)
                                <a
                                    href="{{ $image->url() }}"
                                    class="group block w-full overflow-hidden rounded focus:outline-none focus:ring-2 focus:ring-accent-500 focus:ring-offset-2"
                                    @click.prevent="openAt({{ $loop->index }}, $refs.viewer)"
                                    aria-label="Open image {{ $loop->iteration }}: {{ $image->alt_text ?: $project->title }}"
                                    data-motion-item="true"
                                >
                                    <img src="{{ $image->thumbUrl() ?: $image->url() }}" alt="{{ $image->alt_text ?: $project->title }}" class="h-64 w-full rounded object-cover transition duration-300 group-hover:scale-[1.02]" width="800" height="600" loading="lazy" decoding="async">
                                </a>
                            @endforeach
                        </div>

                        <dialog
                            x-ref="viewer"
                            class="lightbox-overlay"
                            aria-label="Image viewer"
                            @close="open = false"
                            @click.self="close($refs.viewer)"
                            @touchstart.passive="onTouchStart($event)"
                            @touchend.passive="onTouchEnd($event)"
                        >
                            <div class="lightbox-dialog" role="document">
                                <div class="lightbox-topbar">
                                    <p class="lightbox-counter" x-text="`${index + 1} / ${images.length}`"></p>
                                    <button type="button" class="lightbox-close" @click="close($refs.viewer)" aria-label="Close image viewer">
                                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                    </button>
                                </div>
                                <div class="lightbox-body">
                                    <button type="button" class="lightbox-nav lightbox-prev" @click="prev()" :disabled="!many" :class="{ 'lightbox-nav-hidden': !many }" aria-label="Previous image">
                                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                                    </button>
                                    <figure class="lightbox-figure">
                                        <img class="lightbox-image" :src="open ? current?.src : null" :alt="current?.alt || ''" width="1600" height="1200">
                                        <figcaption class="lightbox-caption" x-show="current?.caption" x-text="current?.caption"></figcaption>
                                    </figure>
                                    <button type="button" class="lightbox-nav lightbox-next" @click="next()" :disabled="!many" :class="{ 'lightbox-nav-hidden': !many }" aria-label="Next image">
                                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                                    </button>
                                </div>
                            </div>
                        </dialog>
                    </div>
                @endif
            </div>

            <aside class="space-y-6">
                <div class="rounded border border-steel-200 bg-steel-100 p-6" data-motion="reveal">
                    <p class="font-display text-2xl font-semibold uppercase text-navy-950">Project Information</p>
                    <dl class="mt-5 space-y-4 text-sm">
                        <div><dt class="font-semibold text-navy-900">Category</dt><dd class="text-steel-500">{{ $project->category }}</dd></div>
                        <div><dt class="font-semibold text-navy-900">Location</dt><dd class="text-steel-500">{{ $project->location }}</dd></div>
                        <div><dt class="font-semibold text-navy-900">Client</dt><dd class="text-steel-500">{{ $project->client_name }}</dd></div>
                        <div><dt class="font-semibold text-navy-900">Completion Year</dt><dd class="text-steel-500">{{ $project->completion_year }}</dd></div>
                        <div><dt class="font-semibold text-navy-900">Status</dt><dd class="text-steel-500">{{ str($project->status)->replace('_', ' ')->title() }}</dd></div>
                    </dl>
                </div>
                <div class="rounded bg-navy-950 p-6 text-white" data-motion="reveal" data-motion-delay="80">
                    <p class="font-display text-2xl font-semibold uppercase">Start a Similar Project</p>
                    <p class="mt-3 text-sm leading-7 text-white/70">Our team is ready to help you plan and deliver your next construction project.</p>
                    <div class="mt-5"><x-ui.button href="{{ route('contact') }}" wire:navigate>Contact Us</x-ui.button></div>
                </div>
            </aside>
        </div>

        @if ($relatedProjects->isNotEmpty())
            <div class="container-site mt-20">
                <x-ui.section-heading eyebrow="Related Projects" title="More Projects in This Category" data-motion="reveal" />
                <div class="mt-10 grid gap-6 md:grid-cols-2 xl:grid-cols-3" data-motion="stagger" data-motion-delay="80">
                    @foreach ($relatedProjects as $related)
                        <x-cards.project-card :project="$related" data-motion-item="true" />
                    @endforeach
                </div>
            </div>
        @endif
    </section>
</x-layouts.app>
