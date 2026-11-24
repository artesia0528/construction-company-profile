export function snapshotInlineStyles(el, properties = ['opacity', 'transform', 'willChange']) {
    const original = new Map(properties.map((property) => [property, el.style.getPropertyValue(property)]));

    return () => {
        original.forEach((value, property) => {
            if (value) {
                el.style.setProperty(property, value);
            } else {
                el.style.removeProperty(property);
            }
        });
    };
}

export function numberAttribute(el, name, fallback = 0) {
    const value = Number.parseFloat(el.dataset[name]);

    return Number.isFinite(value) ? value : fallback;
}

export function formatCount(el, value) {
    const original = el.dataset.motionDisplay ?? el.textContent.trim();
    const grouped = /^\d{1,3}(?:[.,]\d{3})+$/.test(original);
    const formatted = grouped ? new Intl.NumberFormat().format(Math.round(value)) : String(Math.round(value));

    return `${formatted}${el.dataset.motionSuffix ?? ''}`;
}
