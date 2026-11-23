import './bootstrap';
import { initAccordion } from './components/accordion';
import { initContactForm } from './components/contact-form';
import { initCounters } from './components/counter';
import { initLightbox } from './components/lightbox';
import { initMobileNavigation } from './components/mobile-navigation';
import { initProjectFilter } from './components/project-filter';
import { initScrollReveal } from './components/scroll-reveal';
import { initTestimonialCarousel } from './components/testimonial-carousel';

function init() {
    initMobileNavigation();
    initScrollReveal();
    initCounters();
    initProjectFilter();
    initLightbox();
    initTestimonialCarousel();
    initAccordion();
    initContactForm();
}

if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', init, { once: true });
} else {
    init();
}
