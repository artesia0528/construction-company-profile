import { markInitialized, prefersReducedMotion } from './utils';

const variants = new Set(['fade-up', 'fade-in', 'fade-left', 'fade-right']);

export function initScrollReveal() {
    const elements = document.querySelectorAll('[data-reveal]');

    if (elements.length === 0) {
        return;
    }

    const reduceMotion = prefersReducedMotion();

    if (reduceMotion || !('IntersectionObserver' in window)) {
        elements.forEach((element) => {
            element.classList.add('is-revealed');
        });

        return;
    }

    const observer = new IntersectionObserver((entries) => {
        entries.forEach((entry) => {
            if (!entry.isIntersecting) {
                return;
            }

            const element = entry.target;
            const once = element.dataset.revealOnce !== 'false';
            element.classList.add('is-revealed');

            if (once) {
                observer.unobserve(element);
            }
        });
    }, { threshold: 0.15, rootMargin: '0px 0px -8% 0px' });

    elements.forEach((element) => {
        if (!markInitialized(element, 'revealInitialized')) {
            return;
        }

        const variant = variants.has(element.dataset.reveal) ? element.dataset.reveal : 'fade-up';
        const variantClass = {
            'fade-up': 'reveal-fade-up',
            'fade-in': 'reveal-fade-in',
            'fade-left': 'reveal-fade-left',
            'fade-right': 'reveal-fade-right',
        }[variant];

        element.classList.add('reveal', variantClass);

        const delay = Number.parseInt(element.dataset.revealDelay || '0', 10);
        if (!Number.isNaN(delay) && delay > 0) {
            element.style.transitionDelay = `${delay}ms`;
        }

        observer.observe(element);
    });
}
