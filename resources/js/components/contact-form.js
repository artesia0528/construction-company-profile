import { markInitialized } from './utils';

export function initContactForm() {
    document.querySelectorAll('[data-contact-form]').forEach((form) => {
        if (!(form instanceof HTMLFormElement) || !markInitialized(form, 'contactFormInitialized')) {
            return;
        }

        form.setAttribute('novalidate', '');

        const submitButton = form.querySelector('[data-contact-form-submit]');
        const successBox = form.parentElement?.querySelector('[data-contact-form-success]');
        const errorBox = form.parentElement?.querySelector('[data-contact-form-error]');
        const fieldErrors = Array.from(form.querySelectorAll('[data-field-error]'));

        const setSubmitting = (isSubmitting) => {
            form.setAttribute('aria-busy', isSubmitting ? 'true' : 'false');
            if (submitButton instanceof HTMLButtonElement) {
                submitButton.disabled = isSubmitting;
                submitButton.textContent = isSubmitting ? 'Sending...' : 'Send Message';
            }
        };

        const clearErrors = () => {
            fieldErrors.forEach((element) => {
                element.textContent = '';
                element.classList.add('hidden');
                const fieldName = element.getAttribute('data-field-error');
                if (!fieldName) return;
                const input = form.querySelector(`[name="${CSS.escape(fieldName)}"]`);
                input?.removeAttribute('aria-invalid');
            });
            if (errorBox) {
                errorBox.textContent = '';
                errorBox.classList.add('hidden');
            }
        };

        const showError = (message) => {
            if (!errorBox) return;
            errorBox.textContent = message;
            errorBox.classList.remove('hidden');
        };

        const showSuccess = (message) => {
            if (!successBox) return;
            successBox.textContent = message;
            successBox.classList.remove('hidden');
            successBox.focus?.();
        };

        form.addEventListener('submit', async (event) => {
            event.preventDefault();
            if (form.dataset.submitting === 'true') return;

            clearErrors();
            if (successBox) {
                successBox.textContent = '';
                successBox.classList.add('hidden');
            }

            form.dataset.submitting = 'true';
            setSubmitting(true);

            try {
                const response = await fetch(form.action, {
                    method: 'POST',
                    headers: {
                        Accept: 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                    },
                    body: new FormData(form),
                    credentials: 'same-origin',
                });

                if (response.ok) {
                    const data = await response.json().catch(() => ({}));
                    form.reset();
                    showSuccess(data.message || 'Thank you for your message. Our team will contact you shortly.');
                    return;
                }

                if (response.status === 422) {
                    const data = await response.json().catch(() => ({}));
                    const errors = data.errors || {};
                    let firstInvalid = null;

                    Object.entries(errors).forEach(([name, messages]) => {
                        const errorElement = form.querySelector(`[data-field-error="${CSS.escape(name)}"]`);
                        const input = form.querySelector(`[name="${CSS.escape(name)}"]`);
                        const message = Array.isArray(messages) ? messages[0] : String(messages);

                        if (errorElement) {
                            errorElement.textContent = message;
                            errorElement.classList.remove('hidden');
                        }

                        if (input) {
                            input.setAttribute('aria-invalid', 'true');
                            if (!firstInvalid) firstInvalid = input;
                        }
                    });

                    showError(data.message || 'Please correct the highlighted fields.');
                    if (firstInvalid instanceof HTMLElement) firstInvalid.focus();
                    return;
                }

                showError('Something went wrong. Please try again.');
            } catch (error) {
                showError('Unable to send your message right now. Please check your connection and try again.');
            } finally {
                form.dataset.submitting = 'false';
                setSubmitting(false);
            }
        });
    });
}
