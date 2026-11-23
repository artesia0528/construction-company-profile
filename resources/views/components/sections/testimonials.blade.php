@props(['testimonials'])

<section class="section-pad bg-steel-100">
    <div class="container-site">
        <x-ui.section-heading eyebrow="Testimonials" title="What Our Clients Say" description="Trusted by developers, businesses, and public sector partners across Indonesia." align="center" class="mx-auto" />

        <div class="mt-12" data-carousel data-carousel-autoplay="6000" aria-roledescription="carousel" aria-label="Client testimonials">
            <div class="mb-6 flex items-center justify-end gap-3">
                <p class="sr-only" aria-live="polite" data-carousel-status></p>
                <button type="button" class="inline-flex h-11 w-11 items-center justify-center rounded border border-steel-200 bg-white text-navy-950 transition hover:border-accent-500 hover:text-accent-600 focus:outline-none focus:ring-2 focus:ring-accent-500 disabled:cursor-not-allowed disabled:opacity-40" data-carousel-prev aria-label="Previous testimonials">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                </button>
                <button type="button" class="inline-flex h-11 w-11 items-center justify-center rounded border border-steel-200 bg-white text-navy-950 transition hover:border-accent-500 hover:text-accent-600 focus:outline-none focus:ring-2 focus:ring-accent-500 disabled:cursor-not-allowed disabled:opacity-40" data-carousel-next aria-label="Next testimonials">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                </button>
            </div>

            <div class="overflow-hidden" data-carousel-viewport>
                <div class="flex gap-6 transition-transform duration-500 ease-out" data-carousel-track>
                    @foreach ($testimonials as $testimonial)
                        <div class="w-full shrink-0 md:w-[calc(50%-0.75rem)] xl:w-[calc(33.333%-1rem)]" data-carousel-slide role="group" aria-roledescription="slide" aria-label="Testimonial {{ $loop->iteration }} of {{ $loop->count }}">
                            <x-cards.testimonial-card :testimonial="$testimonial" />
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="mt-6 flex justify-center gap-2" data-carousel-dots></div>
        </div>
    </div>
</section>
