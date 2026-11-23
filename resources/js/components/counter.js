import { markInitialized, prefersReducedMotion } from './utils';

function parseCounter(element) {
    const originalText = element.textContent?.trim() || '0';
    const rawValue = element.dataset.counterValue ?? originalText;
    const match = String(rawValue).match(/-?\d+(?:[.,]\d+)?/);
    const value = match ? Number.parseFloat(match[0].replace(',', '')) : Number.NaN;
    const suffix = element.dataset.counterSuffix ?? String(rawValue).replace(match?.[0] || '', '').trim();
    const duration = Number.parseInt(element.dataset.counterDuration || '1400', 10);

    return {
        originalText,
        value,
        suffix,
        duration: Number.isNaN(duration) ? 1400 : Math.max(duration, 300),
    };
}

function formatValue(value, originalText) {
    if (/^\d{1,3}([.,]\d{3})+$/.test(originalText)) {
        return new Intl.NumberFormat().format(value);
    }

    return String(value);
}

function animateCounter(element) {
    const { originalText, value, suffix, duration } = parseCounter(element);

    if (Number.isNaN(value)) {
        element.textContent = originalText;
        return;
    }

    if (prefersReducedMotion()) {
        element.textContent = `${formatValue(Math.round(value), originalText)}${suffix}`;
        return;
    }

    const start = performance.now();
    const from = 0;

    const step = (now) => {
        const progress = Math.min((now - start) / duration, 1);
        const eased = 1 - Math.pow(1 - progress, 3);
        const current = Math.round(from + (value - from) * eased);
        element.textContent = `${formatValue(current, originalText)}${suffix}`;

        if (progress < 1) {
            window.requestAnimationFrame(step);
        }
    };

    window.requestAnimationFrame(step);
}

export function initCounters() {
    const elements = document.querySelectorAll('[data-counter]');

    if (elements.length === 0) {
        return;
    }

    const revealImmediately = prefersReducedMotion() || !('IntersectionObserver' in window);
    const observer = revealImmediately ? null : new IntersectionObserver((entries) => {
        entries.forEach((entry) => {
            if (!entry.isIntersecting) {
                return;
            }

            animateCounter(entry.target);
            observer?.unobserve(entry.target);
        });
    }, { threshold: 0.4 });

    elements.forEach((element) => {
        if (!markInitialized(element, 'counterInitialized')) {
            return;
        }

        if (revealImmediately) {
            animateCounter(element);
            return;
        }

        observer?.observe(element);
    });
}
