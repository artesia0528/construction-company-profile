import { formatCount, numberAttribute, snapshotInlineStyles } from './utils';

const variants = {
    'fade-up': { x: 0, y: 24, scale: 1 },
    'fade-in': { x: 0, y: 0, scale: 0.985 },
    'fade-left': { x: 24, y: 0, scale: 1 },
    'fade-right': { x: -24, y: 0, scale: 1 },
};

function variantFor(el) {
    return variants[el.dataset.motionVariant] ?? variants['fade-up'];
}

function setInitialStyles(el, from) {
    el.style.opacity = '0';
    el.style.transform = `translate(${from.x}px, ${from.y}px) scale(${from.scale})`;
    el.style.willChange = 'opacity, transform';
}

function keyframes(from) {
    return {
        opacity: [0, 1],
        x: [from.x, 0],
        y: [from.y, 0],
        scale: [from.scale, 1],
    };
}

function timing(el, ctx, fallback = ctx.motion.slow) {
    return {
        duration: numberAttribute(el, 'motionDuration', fallback * 1000) / 1000,
        delay: numberAttribute(el, 'motionDelay', 0) / 1000,
        ease: ctx.motion.easeOut,
    };
}

export function setupReveal(el, ctx) {
    const from = variantFor(el);
    const restore = snapshotInlineStyles(el);

    setInitialStyles(el, from);
    ctx.addCleanup(restore);

    const stop = ctx.inView(el, () => {
        const animation = ctx.animate(el, keyframes(from), timing(el, ctx));

        ctx.addCleanup(() => animation.stop());
        animation.finished.then(restore).catch(() => {});
    }, { amount: 0.15, margin: '0px 0px -8% 0px' });

    ctx.addCleanup(stop);
}

export function setupStagger(el, ctx) {
    const marked = [...el.querySelectorAll('[data-motion-item]')];
    const targets = marked.length ? marked : [...el.children];

    if (! targets.length) {
        return;
    }

    const from = variantFor(el);
    const restores = targets.map((target) => snapshotInlineStyles(target));

    targets.forEach((target) => setInitialStyles(target, from));
    ctx.addCleanup(() => restores.forEach((restore) => restore()));

    const stop = ctx.inView(el, () => {
        const options = timing(el, ctx);
        const animation = ctx.animate(targets, keyframes(from), {
            ...options,
            delay: ctx.stagger(ctx.motion.stagger, { start: options.delay }),
        });

        ctx.addCleanup(() => animation.stop());
        animation.finished.then(() => restores.forEach((restore) => restore())).catch(() => {});
    }, { amount: 0.15, margin: '0px 0px -8% 0px' });

    ctx.addCleanup(stop);
}

export function setupHero(el, ctx) {
    const targets = [...el.querySelectorAll('[data-motion-hero-item]')];

    if (! targets.length) {
        return;
    }

    const restores = targets.map((target) => snapshotInlineStyles(target));

    targets.forEach((target) => setInitialStyles(target, variants['fade-up']));
    ctx.addCleanup(() => restores.forEach((restore) => restore()));

    const animation = ctx.animate(targets, keyframes(variants['fade-up']), {
        duration: numberAttribute(el, 'motionDuration', ctx.motion.slow * 1000) / 1000,
        delay: ctx.stagger(0.09, { start: numberAttribute(el, 'motionDelay', 0) / 1000 }),
        ease: ctx.motion.easeOut,
    });

    ctx.addCleanup(() => animation.stop());
    animation.finished.then(() => restores.forEach((restore) => restore())).catch(() => {});
}

export function setupCounter(el, ctx) {
    const original = el.textContent.trim();
    const target = numberAttribute(el, 'motionValue', Number.NaN);

    if (! Number.isFinite(target)) {
        return;
    }

    el.dataset.motionDisplay = original;
    ctx.addCleanup(() => {
        el.textContent = original;
    });

    const stop = ctx.inView(el, () => {
        const animation = ctx.animate(0, target, {
            duration: numberAttribute(el, 'motionDuration', 1400) / 1000,
            ease: 'easeOut',
            onUpdate: (latest) => {
                el.textContent = formatCount(el, latest);
            },
        });

        ctx.addCleanup(() => animation.stop());
        animation.finished.then(() => {
            el.textContent = original;
        }).catch(() => {});
    }, { amount: 0.4 });

    ctx.addCleanup(stop);
}
