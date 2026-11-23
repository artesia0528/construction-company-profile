<?php

use App\Models\Project;
use App\Models\Service;
use App\Models\User;

test('admin panel requires authentication', function () {
    $this->get('/admin')->assertRedirect('/admin/login');
});

test('authenticated user can access admin panel', function () {
    $user = User::factory()->create();

    $this->actingAs($user)->get('/admin')->assertOk();
});

test('authenticated user can access seo cms pages', function () {
    $user = User::factory()->create();
    $service = Service::factory()->create();
    $project = Project::factory()->create();

    $this->actingAs($user)->get('/admin/manage-company-setting')->assertOk();
    $this->actingAs($user)->get(route('filament.admin.resources.services.edit', $service))->assertOk();
    $this->actingAs($user)->get(route('filament.admin.resources.projects.edit', $project))->assertOk();
});
