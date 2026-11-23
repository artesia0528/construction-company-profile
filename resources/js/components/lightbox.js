import { markInitialized, prefersReducedMotion } from './utils';

let activeLightbox = null;

function buildLightbox() {
    const overlay = document.createElement('div');
    overlay.className = 'lightbox-overlay hidden';
    overlay.setAttribute('data-lightbox-overlay', '');
    overlay.setAttribute('role', 'dialog');
    overlay.setAttribute('aria-modal', 'true');
    overlay.setAttribute('aria-label', 'Image viewer');
    overlay.setAttribute('aria-hidden', 'true');

    overlay.innerHTML = `
        <div class="lightbox-dialog" role="document">
            <div class="lightbox-topbar">
                <p class="lightbox-counter" data-lightbox-counter></p>
                <button type="button" class="lightbox-close" data-lightbox-close aria-label="Close image viewer">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
            <div class="lightbox-body">
                <button type="button" class="lightbox-nav lightbox-prev" data-lightbox-prev aria-label="Previous image">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                </button>
                <figure class="lightbox-figure">
                    <img class="lightbox-image" data-lightbox-image src="" alt="" width="1600" height="1200">
                    <figcaption class="lightbox-caption" data-lightbox-caption></figcaption>
                </figure>
                <button type="button" class="lightbox-nav lightbox-next" data-lightbox-next aria-label="Next image">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                </button>
            </div>
        </div>
    `;

    document.body.appendChild(overlay);

    return overlay;
}

function getFocusable(container) {
    return Array.from(container.querySelectorAll('button:not([disabled]), [href], input, select, textarea, [tabindex]:not([tabindex="-1"])'));
}

export function initLightbox() {
    const galleries = document.querySelectorAll('[data-lightbox-gallery]');

    if (galleries.length === 0) {
        return;
    }

    const overlay = document.querySelector('[data-lightbox-overlay]') || buildLightbox();
    const image = overlay.querySelector('[data-lightbox-image]');
    const caption = overlay.querySelector('[data-lightbox-caption]');
    const counter = overlay.querySelector('[data-lightbox-counter]');
    const closeButton = overlay.querySelector('[data-lightbox-close]');
    const prevButton = overlay.querySelector('[data-lightbox-prev]');
    const nextButton = overlay.querySelector('[data-lightbox-next]');

    let items = [];
    let currentIndex = 0;
    let lastFocus = null;

    const render = () => {
        const item = items[currentIndex];
        if (!item) {
            return;
        }

        image.src = item.src;
        image.alt = item.alt || '';
        caption.textContent = item.caption || '';
        caption.classList.toggle('hidden', !item.caption);
        counter.textContent = `${currentIndex + 1} / ${items.length}`;

        const many = items.length > 1;
        prevButton.disabled = !many;
        nextButton.disabled = !many;
        prevButton.classList.toggle('lightbox-nav-hidden', !many);
        nextButton.classList.toggle('lightbox-nav-hidden', !many);
    };

    const close = () => {
        if (overlay.classList.contains('hidden')) {
            return;
        }

        overlay.classList.add('hidden');
        overlay.setAttribute('aria-hidden', 'true');
        document.body.classList.remove('lightbox-open');
        image.removeAttribute('src');
        activeLightbox = null;

        if (lastFocus instanceof HTMLElement) {
            lastFocus.focus();
        }
    };

    const open = (galleryItems, index, trigger) => {
        items = galleryItems;
        currentIndex = index;
        lastFocus = trigger;
        activeLightbox = { close, next, prev };
        render();
        overlay.classList.remove('hidden');
        overlay.setAttribute('aria-hidden', 'false');
        document.body.classList.add('lightbox-open');
        closeButton.focus();
    };

    const prev = () => {
        if (items.length < 2) {
            return;
        }
        currentIndex = (currentIndex - 1 + items.length) % items.length;
        render();
    };

    const next = () => {
        if (items.length < 2) {
            return;
        }
        currentIndex = (currentIndex + 1) % items.length;
        render();
    };

    if (!overlay.dataset.lightboxBound) {
        overlay.dataset.lightboxBound = 'true';

        closeButton.addEventListener('click', close);
        prevButton.addEventListener('click', prev);
        nextButton.addEventListener('click', next);

        overlay.addEventListener('click', (event) => {
            if (event.target === overlay) {
                close();
            }
        });

        document.addEventListener('keydown', (event) => {
            if (overlay.classList.contains('hidden')) {
                return;
            }

            if (event.key === 'Escape') {
                event.preventDefault();
                close();
                return;
            }

            if (event.key === 'ArrowLeft') {
                event.preventDefault();
                prev();
                return;
            }

            if (event.key === 'ArrowRight') {
                event.preventDefault();
                next();
                return;
            }

            if (event.key === 'Tab') {
                const focusable = getFocusable(overlay).filter((el) => !el.classList.contains('lightbox-nav-hidden') && !el.disabled);
                if (focusable.length === 0) {
                    event.preventDefault();
                    return;
                }

                const first = focusable[0];
                const last = focusable[focusable.length - 1];

                if (event.shiftKey && document.activeElement === first) {
                    event.preventDefault();
                    last.focus();
                } else if (!event.shiftKey && document.activeElement === last) {
                    event.preventDefault();
                    first.focus();
                }
            }
        });

        let touchStartX = 0;
        let touchStartY = 0;
        overlay.addEventListener('touchstart', (event) => {
            if (overlay.classList.contains('hidden')) return;
            const touch = event.changedTouches[0];
            touchStartX = touch.clientX;
            touchStartY = touch.clientY;
        }, { passive: true });

        overlay.addEventListener('touchend', (event) => {
            if (overlay.classList.contains('hidden')) return;
            const touch = event.changedTouches[0];
            const dx = touch.clientX - touchStartX;
            const dy = touch.clientY - touchStartY;
            if (Math.abs(dx) < 40 || Math.abs(dx) < Math.abs(dy)) return;
            if (dx > 0) prev();
            else next();
        }, { passive: true });
    }

    galleries.forEach((gallery) => {
        if (!markInitialized(gallery, 'lightboxInitialized')) {
            return;
        }

        const triggers = Array.from(gallery.querySelectorAll('[data-lightbox-trigger]'));
        const galleryItems = triggers.map((trigger) => ({
            src: trigger.dataset.lightboxSrc || trigger.getAttribute('href') || trigger.querySelector('img')?.src || '',
            alt: trigger.dataset.lightboxAlt || trigger.querySelector('img')?.alt || '',
            caption: trigger.dataset.lightboxCaption || '',
        }));

        triggers.forEach((trigger, index) => {
            trigger.addEventListener('click', (event) => {
                if (trigger.tagName === 'A') {
                    event.preventDefault();
                }

                open(galleryItems, index, trigger);
            });
        });
    });
}
