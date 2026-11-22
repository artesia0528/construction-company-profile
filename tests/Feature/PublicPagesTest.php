<?php

use App\Models\Project;
use App\Models\Service;

test('public pages are accessible', function () {
    $this->seed();

    $service = Service::query()->where('is_active', true)->firstOrFail();
    $project = Project::query()->where('is_published', true)->firstOrFail();

    $this->get(route('home'))->assertOk();
    $this->get(route('about'))->assertOk();
    $this->get(route('services.index'))->assertOk();
    $this->get(route('services.show', $service))->assertOk();
    $this->get(route('projects.index'))->assertOk();
    $this->get(route('projects.show', $project))->assertOk();
    $this->get(route('contact'))->assertOk();
});
