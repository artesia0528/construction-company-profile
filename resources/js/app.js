import { Livewire, Alpine } from '../../vendor/livewire/livewire/dist/livewire.esm';
import './bootstrap';

const prefersReducedMotion = () => window.matchMedia('(prefers-reduced-motion: reduce)').matches;
const revealVariants = ['fade-up', 'fade-in', 'fade-left', 'fade-right'];

Alpine.directive('reveal', (el, { modifiers }, { cleanup }) => {
    const variant = modifiers.find((modifier) => revealVariants.includes(modifier)) ?? 'fade-up';
    const delay = Number(modifiers.find((modifier) => /^\d+$/.test(modifier)) ?? 0);

    el.classList.add('reveal', `reveal-${variant}`);

    if (delay > 0) {
        el.style.transitionDelay = `${delay}ms`;
    }

    if (prefersReducedMotion() || ! ('IntersectionObserver' in window)) {
        el.classList.add('is-revealed');
        return;
    }

    const observer = new IntersectionObserver((entries) => {
        if (! entries.some((entry) => entry.isIntersecting)) {
            return;
        }

        el.classList.add('is-revealed');
        observer.unobserve(el);
    }, { threshold: 0.15, rootMargin: '0px 0px -8% 0px' });

    observer.observe(el);
    cleanup(() => observer.disconnect());
});

Alpine.directive('count', (el, { value }, { cleanup }) => {
    const originalText = el.textContent?.trim() || '0';
    const config = value !== null && typeof value === 'object' ? value : { value };
    const match = String(config.value ?? originalText).match(/-?\d+(?:[.,]\d+)?/);
    const target = match ? Number.parseFloat(match[0].replace(',', '')) : Number.NaN;
    const suffix = config.suffix ?? '';
    const duration = Math.max(Number(config.duration ?? 1400), 300);
    const format = (number) => /^\d{1,3}([.,]\d{3})+$/.test(originalText)
        ? new Intl.NumberFormat().format(number)
        : String(number);

    if (Number.isNaN(target)) {
        return;
    }

    if (prefersReducedMotion() || ! ('IntersectionObserver' in window)) {
        el.textContent = `${format(Math.round(target))}${suffix}`;
        return;
    }

    let frame = 0;

    const animate = () => {
        const start = performance.now();

        const step = (now) => {
            const progress = Math.min((now - start) / duration, 1);
            const eased = 1 - Math.pow(1 - progress, 3);

            el.textContent = `${format(Math.round(target * eased))}${suffix}`;

            if (progress < 1) {
                frame = window.requestAnimationFrame(step);
            }
        };

        frame = window.requestAnimationFrame(step);
    };

    const observer = new IntersectionObserver((entries) => {
        if (! entries.some((entry) => entry.isIntersecting)) {
            return;
        }

        animate();
        observer.unobserve(el);
    }, { threshold: 0.4 });

    observer.observe(el);
    cleanup(() => {
        observer.disconnect();
        window.cancelAnimationFrame(frame);
    });
});

Livewire.start();
