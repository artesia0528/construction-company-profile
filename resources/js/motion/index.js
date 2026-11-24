import { animate, inView, stagger } from 'motion';
import { setupCounter, setupHero, setupReveal, setupStagger } from './reveal';

const reducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)');
const cleanups = new Map();

const motionConfig = {
    slow: 0.55,
    easeOut: [0.22, 1, 0.36, 1],
    stagger: 0.07,
};

function addCleanup(el, cleanup) {
    const list = cleanups.get(el) ?? [];
    list.push(cleanup);
    cleanups.set(el, list);
}

function elementsIn(root) {
    const elements = root instanceof Element && root.matches('[data-motion]') ? [root] : [];

    return [...elements, ...root.querySelectorAll('[data-motion]')];
}

function setupElement(el) {
    if (cleanups.has(el) || reducedMotion.matches) {
        return;
    }

    const setups = {
        reveal: setupReveal,
        stagger: setupStagger,
        hero: setupHero,
        counter: setupCounter,
    };

    const setup = setups[el.dataset.motion];

    if (! setup) {
        return;
    }

    setup(el, {
        motion: motionConfig,
        animate,
        inView,
        stagger,
        addCleanup: (cleanup) => addCleanup(el, cleanup),
    });
}

export function initMotion(root = document) {
    elementsIn(root).forEach(setupElement);
}

function destroyMotion(root) {
    elementsIn(root).forEach((el) => {
        (cleanups.get(el) ?? []).forEach((cleanup) => cleanup());
        cleanups.delete(el);
    });
}

export function destroyAllMotion() {
    cleanups.forEach((list) => list.forEach((cleanup) => cleanup()));
    cleanups.clear();
}

reducedMotion.addEventListener?.('change', () => {
    destroyAllMotion();

    if (! reducedMotion.matches) {
        initMotion(document);
    }
});

export function registerLivewireMotionHooks(Livewire) {
    document.addEventListener('livewire:init', () => {
        Livewire.hook('morph.added', ({ el }) => initMotion(el));
        Livewire.hook('morph.removed', ({ el }) => destroyMotion(el));
    });
}
