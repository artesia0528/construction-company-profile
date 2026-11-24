@props(['testimonials'])

<section class="section-pad bg-steel-100">
    <div class="container-site">
        <x-ui.section-heading eyebrow="Testimonials" title="What Our Clients Say" description="Trusted by developers, businesses, and public sector partners across Indonesia." align="center" class="mx-auto" data-motion="reveal" />

        <div
            class="mt-12"
            data-motion="reveal"
            data-motion-delay="120"
            data-carousel
            aria-roledescription="carousel"
            aria-label="Client testimonials"
            x-data="{
                index: 0,
                total: {{ $testimonials->count() }},
                perView: window.innerWidth >= 1280 ? 3 : window.innerWidth >= 768 ? 2 : 1,
                reduceMotion: window.matchMedia('(prefers-reduced-motion: reduce)').matches,
                media: null,
                syncReduceMotion: null,
                timer: null,
                paused: false,
                get maxIndex() { return Math.max(0, this.total - this.perView); },
                get dots() { return Array.from({ length: this.maxIndex + 1 }, (_, i) => i); },
                step() {
                    const track = this.$refs.track;
                    const slide = track?.firstElementChild;

                    if (! slide) {
                        return 1;
                    }

                    const styles = window.getComputedStyle(track);
                    const gap = Number.parseFloat(styles.columnGap || styles.gap) || 0;

                    return slide.getBoundingClientRect().width + gap;
                },
                goTo(index) {
                    this.index = Math.min(Math.max(index, 0), this.maxIndex);
                    this.$refs.viewport.scrollTo({
                        left: this.index * this.step(),
                        behavior: this.reduceMotion ? 'auto' : 'smooth',
                    });
                },
                next() { this.goTo(this.index >= this.maxIndex ? 0 : this.index + 1); },
                prev() { this.goTo(this.index - 1); },
                syncFromScroll() {
                    this.index = Math.min(Math.max(Math.round(this.$refs.viewport.scrollLeft / this.step()), 0), this.maxIndex);
                },
                onResize() {
                    this.perView = window.innerWidth >= 1280 ? 3 : window.innerWidth >= 768 ? 2 : 1;
                    this.$refs.viewport.scrollLeft = this.index * this.step();
                },
                pause() { this.paused = true; this.stopAutoplay(); },
                resume() { this.paused = false; this.startAutoplay(); },
                stopAutoplay() {
                    if (this.timer) {
                        window.clearInterval(this.timer);
                        this.timer = null;
                    }
                },
                startAutoplay() {
                    if (this.timer || this.paused || document.hidden || this.reduceMotion || this.total <= this.perView) {
                        return;
                    }

                    this.timer = window.setInterval(() => this.next(), 6000);
                },
                init() {
                    this.media = window.matchMedia('(prefers-reduced-motion: reduce)');
                    this.syncReduceMotion = () => {
                        this.reduceMotion = this.media.matches;

                        if (this.reduceMotion) {
                            this.stopAutoplay();
                        } else {
                            this.startAutoplay();
                        }
                    };

                    this.media.addEventListener?.('change', this.syncReduceMotion);
                    this.startAutoplay();

                    return () => this.media?.removeEventListener?.('change', this.syncReduceMotion);
                },
            }"
            x-init="$el.dataset.carouselReady = 'true'; init()"
            @resize.window="onResize()"
            @visibilitychange.document="document.hidden ? stopAutoplay() : startAutoplay()"
            @mouseenter="pause()"
            @mouseleave="resume()"
            @focusin="pause()"
            @focusout="if (! $el.contains($event.relatedTarget)) resume()"
        >
            <div class="mb-6 flex items-center justify-end gap-3">
                <p class="sr-only" aria-live="polite" x-text="`Showing testimonials ${Math.min(index + 1, total)} to ${Math.min(index + perView, total)} of ${total}`"></p>
                <button type="button" class="inline-flex h-11 w-11 items-center justify-center rounded border border-steel-200 bg-white text-navy-950 transition hover:border-accent-500 hover:text-accent-600 focus:outline-none focus:ring-2 focus:ring-accent-500 disabled:cursor-not-allowed disabled:opacity-40" data-carousel-prev @click="prev()" :disabled="index === 0" aria-label="Previous testimonials">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                </button>
                <button type="button" class="inline-flex h-11 w-11 items-center justify-center rounded border border-steel-200 bg-white text-navy-950 transition hover:border-accent-500 hover:text-accent-600 focus:outline-none focus:ring-2 focus:ring-accent-500 disabled:cursor-not-allowed disabled:opacity-40" data-carousel-next @click="next()" :disabled="index >= maxIndex" aria-label="Next testimonials">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                </button>
            </div>

            <div
                class="testimonial-viewport snap-x snap-mandatory overflow-x-auto"
                data-carousel-viewport
                x-ref="viewport"
                tabindex="0"
                @scroll.throttle="syncFromScroll()"
                @touchstart.passive="pause()"
                @touchend.passive="resume()"
            >
                <div class="flex gap-6" data-carousel-track x-ref="track">
                    @foreach ($testimonials as $testimonial)
                        <div class="w-full shrink-0 snap-start md:w-[calc(50%-0.75rem)] xl:w-[calc(33.333%-1rem)]" data-carousel-slide role="group" aria-roledescription="slide" aria-label="Testimonial {{ $loop->iteration }} of {{ $loop->count }}">
                            <x-cards.testimonial-card :testimonial="$testimonial" />
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="mt-6 flex justify-center gap-2" data-carousel-dots>
                <template x-for="dot in dots" :key="dot">
                    <button
                        type="button"
                        data-carousel-dot
                        class="h-2.5 w-2.5 rounded-full border transition focus:outline-none focus:ring-2 focus:ring-accent-500 focus:ring-offset-2"
                        :class="dot === index ? 'border-accent-500 bg-accent-500' : 'border-steel-500 bg-transparent'"
                        :aria-current="dot === index ? 'true' : 'false'"
                        :aria-label="'Go to testimonial group ' + (dot + 1)"
                        @click="goTo(dot)"
                    ></button>
                </template>
            </div>
        </div>
    </div>
</section>
