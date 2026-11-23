<x-layouts.app :title="$project->title" :description="$project->short_description">
    <x-sections.page-banner eyebrow="Project Detail" :title="$project->title" :description="$project->short_description" :breadcrumbs="[['url' => route('home'), 'label' => 'Home'], ['url' => route('projects.index'), 'label' => 'Projects'], ['label' => $project->title]]" />

    <section class="section-pad">
        <div class="container-site grid gap-12 lg:grid-cols-3">
            <div class="lg:col-span-2">
                <img src="{{ $project->coverImageUrl() ?? 'https://images.unsplash.com/photo-1504307651254-35680f356dfd?auto=format&fit=crop&w=1400&q=80' }}" alt="{{ $project->cover_alt_text ?: $project->title }}" class="h-[440px] w-full rounded object-cover" width="1400" height="933" loading="eager" fetchpriority="high" decoding="async">
                <div class="mt-8 space-y-5 leading-8 text-steel-700">
                    @foreach (preg_split('/\n\s*\n/', (string) $project->full_description) as $paragraph)
                        @if (trim($paragraph) !== '')
                            <p>{{ trim($paragraph) }}</p>
                        @endif
                    @endforeach
                </div>

                @if ($project->images->isNotEmpty())
                    <div class="mt-12">
                        <h2 class="font-display text-3xl font-semibold uppercase text-navy-950">Project Gallery</h2>
                        <div class="mt-6 grid gap-4 sm:grid-cols-2" data-lightbox-gallery="project-gallery">
                            @foreach ($project->images as $image)
                                <button
                                    type="button"
                                    class="group block w-full overflow-hidden rounded focus:outline-none focus:ring-2 focus:ring-accent-500 focus:ring-offset-2"
                                    data-lightbox-trigger
                                    data-lightbox-src="{{ $image->url() }}"
                                    data-lightbox-alt="{{ $image->alt_text ?: $project->title }}"
                                    data-lightbox-caption="{{ $image->alt_text ?: '' }}"
                                    aria-label="Open image {{ $loop->iteration }}: {{ $image->alt_text ?: $project->title }}"
                                >
                                    <img src="{{ $image->thumbUrl() ?: $image->url() }}" alt="{{ $image->alt_text ?: $project->title }}" class="h-64 w-full rounded object-cover transition duration-300 group-hover:scale-[1.02]" width="800" height="600" loading="lazy" decoding="async">
                                </button>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>

            <aside class="space-y-6">
                <div class="rounded border border-steel-200 bg-steel-100 p-6">
                    <p class="font-display text-2xl font-semibold uppercase text-navy-950">Project Information</p>
                    <dl class="mt-5 space-y-4 text-sm">
                        <div><dt class="font-semibold text-navy-900">Category</dt><dd class="text-steel-500">{{ $project->category }}</dd></div>
                        <div><dt class="font-semibold text-navy-900">Location</dt><dd class="text-steel-500">{{ $project->location }}</dd></div>
                        <div><dt class="font-semibold text-navy-900">Client</dt><dd class="text-steel-500">{{ $project->client_name }}</dd></div>
                        <div><dt class="font-semibold text-navy-900">Completion Year</dt><dd class="text-steel-500">{{ $project->completion_year }}</dd></div>
                        <div><dt class="font-semibold text-navy-900">Status</dt><dd class="text-steel-500">{{ str($project->status)->replace('_', ' ')->title() }}</dd></div>
                    </dl>
                </div>
                <div class="rounded bg-navy-950 p-6 text-white">
                    <p class="font-display text-2xl font-semibold uppercase">Start a Similar Project</p>
                    <p class="mt-3 text-sm leading-7 text-white/70">Our team is ready to help you plan and deliver your next construction project.</p>
                    <div class="mt-5"><x-ui.button href="{{ route('contact') }}">Contact Us</x-ui.button></div>
                </div>
            </aside>
        </div>

        @if ($relatedProjects->isNotEmpty())
            <div class="container-site mt-20">
                <x-ui.section-heading eyebrow="Related Projects" title="More Projects in This Category" />
                <div class="mt-10 grid gap-6 md:grid-cols-2 xl:grid-cols-3">
                    @foreach ($relatedProjects as $related)
                        <x-cards.project-card :project="$related" />
                    @endforeach
                </div>
            </div>
        @endif
    </section>
</x-layouts.app>
