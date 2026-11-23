import { markInitialized } from './utils';

const ACTIVE_CLASSES = ['border-accent-500', 'bg-accent-500', 'text-navy-950'];
const INACTIVE_CLASSES = ['border-steel-200', 'text-steel-700', 'hover:border-accent-500'];

function setLinkState(link, active) {
    link.classList.remove(...(active ? INACTIVE_CLASSES : ACTIVE_CLASSES));
    link.classList.add(...(active ? ACTIVE_CLASSES : INACTIVE_CLASSES));

    if (active) {
        link.setAttribute('aria-current', 'true');
    } else {
        link.removeAttribute('aria-current');
    }
}

export function initProjectFilter() {
    document.querySelectorAll('[data-project-filter]').forEach((container) => {
        if (!markInitialized(container, 'projectFilterInitialized')) {
            return;
        }

        const listing = container.querySelector('[data-project-filter-listing]');
        const status = container.querySelector('[data-project-filter-status]');
        const feedback = container.querySelector('[data-project-filter-feedback]');
        const links = Array.from(container.querySelectorAll('[data-project-filter-link]'));

        if (!listing) {
            return;
        }

        let activeController = null;

        const syncLinks = (urlString) => {
            const url = new URL(urlString, window.location.origin);
            const selectedCategory = url.searchParams.get('category') || '';

            links.forEach((link) => {
                const category = link.dataset.category || '';
                setLinkState(link, category === selectedCategory);
            });
        };

        const setLoading = (isLoading) => {
            listing.setAttribute('aria-busy', isLoading ? 'true' : 'false');
            listing.classList.toggle('opacity-60', isLoading);
            listing.classList.toggle('pointer-events-none', isLoading);

            if (status) {
                status.textContent = isLoading ? 'Loading projects...' : '';
            }
        };

        const setFeedback = (message = '') => {
            if (!feedback) {
                return;
            }

            if (message === '') {
                feedback.textContent = '';
                feedback.classList.add('hidden');
                return;
            }

            feedback.textContent = message;
            feedback.classList.remove('hidden');
        };

        const load = async (urlString, pushState = true) => {
            if (activeController) {
                activeController.abort();
            }

            activeController = new AbortController();
            setFeedback('');
            setLoading(true);

            try {
                const response = await fetch(urlString, {
                    headers: {
                        Accept: 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                    },
                    credentials: 'same-origin',
                    signal: activeController.signal,
                });

                if (!response.ok) {
                    throw new Error(`Request failed with status ${response.status}`);
                }

                const data = await response.json();

                if (typeof data.html !== 'string') {
                    throw new Error('Invalid response payload');
                }

                listing.innerHTML = data.html;
                syncLinks(data.url || urlString);

                if (pushState) {
                    window.history.pushState({ projectFilterUrl: data.url || urlString }, '', data.url || urlString);
                }

                if (status) {
                    status.textContent = 'Projects updated.';
                }
            } catch (error) {
                if (error.name === 'AbortError') {
                    return;
                }

                setFeedback('Could not load projects. Please try again or refresh the page.');
                if (status) {
                    status.textContent = 'Could not load projects.';
                }
            } finally {
                setLoading(false);
            }
        };

        container.addEventListener('click', (event) => {
            const link = event.target.closest('[data-project-filter-link], [data-project-pagination] a[href], [data-project-filter-listing] .pagination a[href]');

            if (!link || !container.contains(link)) {
                return;
            }

            if (event.metaKey || event.ctrlKey || event.shiftKey || event.altKey || link.target === '_blank') {
                return;
            }

            const url = new URL(link.href, window.location.origin);
            if (url.origin !== window.location.origin) {
                return;
            }

            event.preventDefault();

            if (url.href === window.location.href) {
                return;
            }

            load(url.href, true);
        });

        window.addEventListener('popstate', () => {
            if (window.location.pathname !== new URL(container.dataset.projectFilterUrl || window.location.href, window.location.origin).pathname) {
                return;
            }

            load(window.location.href, false);
        });
    });
}
