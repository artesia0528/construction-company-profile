import { markInitialized, prefersReducedMotion } from './utils';

export function initAccordion() {
    document.querySelectorAll('[data-accordion]').forEach((accordion) => {
        if (!markInitialized(accordion, 'accordionInitialized')) {
            return;
        }

        const single = accordion.dataset.accordionSingle === 'true';
        const items = Array.from(accordion.querySelectorAll('[data-accordion-item]'));
        const reduceMotion = prefersReducedMotion();

        const setState = (item, open, animate = true) => {
            const trigger = item.querySelector('[data-accordion-trigger]');
            const panel = item.querySelector('[data-accordion-panel]');
            const icon = item.querySelector('[data-accordion-icon]');

            if (!trigger || !panel) return;

            trigger.setAttribute('aria-expanded', open ? 'true' : 'false');
            icon?.classList.toggle('rotate-180', open);

            if (reduceMotion || !animate) {
                panel.style.height = open ? 'auto' : '0px';
                panel.style.opacity = open ? '1' : '0';
                panel.style.transition = 'none';
                return;
            }

            panel.style.transition = 'height 250ms ease, opacity 250ms ease';
            panel.style.opacity = open ? '1' : '0';

            if (open) {
                const startHeight = panel.getBoundingClientRect().height;
                panel.style.height = `${startHeight}px`;
                panel.getBoundingClientRect();
                panel.style.height = `${panel.scrollHeight}px`;
                const onEnd = (event) => {
                    if (event.propertyName !== 'height') return;
                    panel.style.height = 'auto';
                    panel.removeEventListener('transitionend', onEnd);
                };
                panel.addEventListener('transitionend', onEnd);
            } else {
                panel.style.height = `${panel.scrollHeight}px`;
                panel.getBoundingClientRect();
                panel.style.height = '0px';
            }
        };

        let openItems = items.filter((item) => item.dataset.open === 'true');
        if (single && openItems.length > 1) {
            openItems = [openItems[0]];
        }

        items.forEach((item) => {
            const shouldOpen = openItems.includes(item);
            setState(item, shouldOpen, false);

            const trigger = item.querySelector('[data-accordion-trigger]');
            trigger?.addEventListener('click', () => {
                const isOpen = trigger.getAttribute('aria-expanded') === 'true';

                if (single) {
                    items.forEach((other) => setState(other, other === item ? !isOpen : false));
                    return;
                }

                setState(item, !isOpen);
            });
        });
    });
}
