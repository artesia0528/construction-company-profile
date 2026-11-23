import { markInitialized } from './utils';

export function initMobileNavigation() {
    document.querySelectorAll('[data-mobile-nav]').forEach((nav) => {
        if (!markInitialized(nav, 'mobileNavInitialized')) {
            return;
        }

        const toggle = nav.querySelector('[data-mobile-nav-toggle]');
        const panel = nav.querySelector('[data-mobile-nav-panel]');

        const closeMenu = (restoreFocus = false) => {
            if (!toggle || !panel) {
                return;
            }

            toggle.setAttribute('aria-expanded', 'false');
            panel.classList.add('hidden');

            if (restoreFocus) {
                toggle.focus();
            }
        };

        const openMenu = () => {
            if (!toggle || !panel) {
                return;
            }

            toggle.setAttribute('aria-expanded', 'true');
            panel.classList.remove('hidden');
        };

        if (toggle && panel) {
            toggle.addEventListener('click', () => {
                const expanded = toggle.getAttribute('aria-expanded') === 'true';
                if (expanded) {
                    closeMenu();
                    return;
                }

                openMenu();
            });

            nav.querySelectorAll('a[href]').forEach((link) => {
                link.addEventListener('click', () => closeMenu());
            });

            document.addEventListener('click', (event) => {
                if (toggle.getAttribute('aria-expanded') !== 'true') {
                    return;
                }

                if (!nav.contains(event.target)) {
                    closeMenu();
                }
            });

            document.addEventListener('keydown', (event) => {
                if (event.key === 'Escape' && toggle.getAttribute('aria-expanded') === 'true') {
                    closeMenu(true);
                }
            });

            window.addEventListener('resize', () => {
                if (window.innerWidth >= 768) {
                    closeMenu();
                }
            });
        }

        const updateScrollState = () => {
            nav.dataset.scrolled = window.scrollY > 12 ? 'true' : 'false';
        };

        let ticking = false;
        window.addEventListener('scroll', () => {
            if (ticking) {
                return;
            }

            ticking = true;
            window.requestAnimationFrame(() => {
                updateScrollState();
                ticking = false;
            });
        }, { passive: true });

        updateScrollState();
    });
}
