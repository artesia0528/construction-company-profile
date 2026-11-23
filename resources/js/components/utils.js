export function prefersReducedMotion() {
    return window.matchMedia('(prefers-reduced-motion: reduce)').matches;
}

export function markInitialized(element, key) {
    if (element.dataset[key] === 'true') {
        return false;
    }

    element.dataset[key] = 'true';

    return true;
}
