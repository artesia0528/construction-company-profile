import './bootstrap';

document.querySelectorAll('[data-mobile-nav]').forEach((nav) => {
    const toggle = nav.querySelector('[data-mobile-nav-toggle]');
    const panel = nav.querySelector('[data-mobile-nav-panel]');

    if (!toggle || !panel) {
        return;
    }

    toggle.addEventListener('click', () => {
        const expanded = toggle.getAttribute('aria-expanded') === 'true';
        toggle.setAttribute('aria-expanded', String(!expanded));
        panel.classList.toggle('hidden');
    });
});
