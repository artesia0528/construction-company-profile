<?php

use App\Models\Project;
use App\Models\Service;

test('home page uses alpine directives for reveals, counters, and carousel', function () {
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
        ->assertSee('x-reveal.fade-up', false)
        ->assertSee('x-count=', false)
        ->assertSee('data-carousel', false)
        ->assertSee('x-for=', false)
        ->assertDontSee('data-reveal=', false)
        ->assertDontSee('data-counter', false);
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
