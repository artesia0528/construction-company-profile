<?php

use App\Models\Project;
use App\Models\Service;

test('home page uses motion attributes for hero, navigation, reveals, counters, and alpine carousel', function () {
    $this->seed();

    $service = Service::query()->active()->ordered()->firstOrFail();
    $project = Project::query()->published()->featured()->firstOrFail();

    $this->get(route('home'))
        ->assertOk()
        ->assertSee($service->name)
        ->assertSee($project->title)
        ->assertDontSee('<x-cards.service-card', false)
        ->assertDontSee('<x-cards.project-card', false)
        ->assertDontSee('<x-cards.feature-card', false)
        ->assertSee('data-motion="hero"', false)
        ->assertSee('data-motion-hero-item', false)
        ->assertSee('data-motion="stagger"', false)
        ->assertSee('data-motion-item="true"', false)
        ->assertSee('data-motion="reveal"', false)
        ->assertSee('data-motion="counter"', false)
        ->assertSee('data-motion-duration="260"', false)
        ->assertSee('wire:transition.navigate', false)
        ->assertSee('wire:transition.navigate="match-element"', false)
        ->assertSee('wire:navigate', false)
        ->assertSee('aria-current="page"', false)
        ->assertSee('focus:ring-accent-500 md:hidden"', false)
        ->assertSee('after:scale-x-100', false)
        ->assertSee('hover:border-accent-500/60', false)
        ->assertSee('Skip to content', false)
        ->assertSee('id="main-content"', false)
        ->assertSee('data-carousel', false)
        ->assertSee('syncReduceMotion', false)
        ->assertSee('x-for=', false)
        ->assertDontSee('x-reveal', false)
        ->assertDontSee('x-count', false);
});

test('inner pages use shared page banner transitions and reveals', function () {
    $this->seed();

    $this->get(route('about'))
        ->assertOk()
        ->assertSee('wire:transition.navigate', false)
        ->assertSee('wire:transition.navigate="match-element"', false)
        ->assertSee('wire:navigate', false)
        ->assertSee('data-motion="stagger"', false)
        ->assertSee('data-motion="reveal"', false);
});

test('contact page uses a native details accordion', function () {
    $this->seed();

    $this->get(route('contact'))
        ->assertOk()
        ->assertSee('<details', false)
        ->assertSee('name="contact-faq"', false)
        ->assertSee('<summary', false)
        ->assertDontSee('data-accordion-trigger', false);
});
