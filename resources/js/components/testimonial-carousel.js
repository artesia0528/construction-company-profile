import { markInitialized, prefersReducedMotion } from './utils';

function getSlidesPerView() {
    if (window.matchMedia('(min-width: 1280px)').matches) return 3;
    if (window.matchMedia('(min-width: 768px)').matches) return 2;
    return 1;
}

export function initTestimonialCarousel() {
    document.querySelectorAll('[data-carousel]').forEach((carousel) => {
        if (!markInitialized(carousel, 'carouselInitialized')) {
            return;
        }

        const viewport = carousel.querySelector('[data-carousel-viewport]');
        const track = carousel.querySelector('[data-carousel-track]');
        const slides = Array.from(carousel.querySelectorAll('[data-carousel-slide]'));
        const prevButton = carousel.querySelector('[data-carousel-prev]');
        const nextButton = carousel.querySelector('[data-carousel-next]');
        const dotsContainer = carousel.querySelector('[data-carousel-dots]');
        const status = carousel.querySelector('[data-carousel-status]');

        if (!viewport || !track || slides.length === 0) {
            return;
        }

        carousel.setAttribute('data-carousel-ready', 'true');

        let currentIndex = 0;
        let slidesPerView = getSlidesPerView();
        let autoplayTimer = null;
        let isHovering = false;
        let hasFocus = false;
        let touchStartX = 0;
        let touchStartY = 0;

        const autoplayDelay = Number.parseInt(carousel.dataset.carouselAutoplay || '0', 10);
        const reduceMotion = prefersReducedMotion();
        const autoplayEnabled = autoplayDelay > 0 && !reduceMotion && slides.length > slidesPerView;

        const maxIndex = () => Math.max(0, slides.length - slidesPerView);

        const updateControls = () => {
            const max = maxIndex();
            const atStart = currentIndex <= 0;
            const atEnd = currentIndex >= max;

            if (prevButton) prevButton.disabled = atStart;
            if (nextButton) nextButton.disabled = atEnd;

            if (dotsContainer) {
                dotsContainer.querySelectorAll('[data-carousel-dot]').forEach((dot, index) => {
                    const active = index === currentIndex;
                    dot.setAttribute('aria-current', active ? 'true' : 'false');
                    dot.classList.toggle('bg-accent-500', active);
                    dot.classList.toggle('border-accent-500', active);
                    dot.classList.toggle('bg-transparent', !active);
                    dot.classList.toggle('border-steel-500', !active);
                });
            }

            if (status) {
                const start = Math.min(currentIndex + 1, slides.length);
                const end = Math.min(currentIndex + slidesPerView, slides.length);
                status.textContent = `Showing testimonials ${start} to ${end} of ${slides.length}`;
            }
        };

        const render = (animate = true) => {
            const max = maxIndex();
            currentIndex = Math.min(Math.max(currentIndex, 0), max);
            const slideWidth = slides[0].getBoundingClientRect().width;
            const gap = Number.parseFloat(window.getComputedStyle(track).columnGap || window.getComputedStyle(track).gap || '0') || 0;
            const offset = currentIndex * (slideWidth + gap);
            track.style.transition = animate && !reduceMotion ? 'transform 450ms ease' : 'none';
            track.style.transform = `translateX(-${offset}px)`;
            updateControls();
        };

        const stopAutoplay = () => {
            if (autoplayTimer) {
                window.clearInterval(autoplayTimer);
                autoplayTimer = null;
            }
        };

        const startAutoplay = () => {
            if (!autoplayEnabled || autoplayTimer || isHovering || hasFocus || document.hidden) {
                return;
            }

            autoplayTimer = window.setInterval(() => {
                const max = maxIndex();
                currentIndex = currentIndex >= max ? 0 : currentIndex + 1;
                render(true);
            }, autoplayDelay);
        };

        const buildDots = () => {
            if (!dotsContainer) return;
            dotsContainer.innerHTML = '';
            const max = maxIndex();
            for (let i = 0; i <= max; i += 1) {
                const dot = document.createElement('button');
                dot.type = 'button';
                dot.className = 'h-2.5 w-2.5 rounded-full border border-steel-500 bg-transparent transition focus:outline-none focus:ring-2 focus:ring-accent-500 focus:ring-offset-2';
                dot.setAttribute('data-carousel-dot', '');
                dot.setAttribute('aria-label', `Go to testimonial group ${i + 1}`);
                dot.addEventListener('click', () => {
                    currentIndex = i;
                    render(true);
                });
                dotsContainer.appendChild(dot);
            }
        };

        const onResize = () => {
            const nextSlidesPerView = getSlidesPerView();
            if (nextSlidesPerView === slidesPerView) {
                render(false);
                return;
            }

            slidesPerView = nextSlidesPerView;
            buildDots();
            render(false);
            stopAutoplay();
            startAutoplay();
        };

        prevButton?.addEventListener('click', () => {
            currentIndex -= 1;
            render(true);
        });

        nextButton?.addEventListener('click', () => {
            currentIndex += 1;
            render(true);
        });

        carousel.addEventListener('mouseenter', () => { isHovering = true; stopAutoplay(); });
        carousel.addEventListener('mouseleave', () => { isHovering = false; startAutoplay(); });
        carousel.addEventListener('focusin', () => { hasFocus = true; stopAutoplay(); });
        carousel.addEventListener('focusout', (event) => {
            if (!carousel.contains(event.relatedTarget)) {
                hasFocus = false;
                startAutoplay();
            }
        });

        document.addEventListener('visibilitychange', () => {
            if (document.hidden) stopAutoplay();
            else startAutoplay();
        });

        viewport.addEventListener('touchstart', (event) => {
            const touch = event.changedTouches[0];
            touchStartX = touch.clientX;
            touchStartY = touch.clientY;
            stopAutoplay();
        }, { passive: true });

        viewport.addEventListener('touchend', (event) => {
            const touch = event.changedTouches[0];
            const dx = touch.clientX - touchStartX;
            const dy = touch.clientY - touchStartY;
            if (Math.abs(dx) > 40 && Math.abs(dx) > Math.abs(dy)) {
                currentIndex += dx > 0 ? -1 : 1;
                render(true);
            }
            startAutoplay();
        }, { passive: true });

        let resizeTimer;
        window.addEventListener('resize', () => {
            window.clearTimeout(resizeTimer);
            resizeTimer = window.setTimeout(onResize, 100);
        });

        buildDots();
        render(false);
        startAutoplay();
    });
}
