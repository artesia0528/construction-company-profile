<?php

use App\Models\Project;

test('project index returns json listing for ajax requests', function () {
    Project::factory()->create([
        'title' => 'Warehouse Alpha',
        'category' => 'Industrial',
        'is_published' => true,
    ]);

    Project::factory()->create([
        'title' => 'Office Beta',
        'category' => 'Commercial',
        'is_published' => true,
    ]);

    $response = $this->getJson(route('projects.index', ['category' => 'Industrial']));

    $response->assertOk()
        ->assertJsonStructure(['html', 'url', 'category'])
        ->assertJsonPath('category', 'Industrial');

    expect($response->json('html'))
        ->toContain('Warehouse Alpha')
        ->not->toContain('Office Beta');
});

test('project index json preserves pagination links', function () {
    Project::factory()->count(12)->create([
        'category' => 'Industrial',
        'is_published' => true,
    ]);

    $response = $this->getJson(route('projects.index', ['category' => 'Industrial', 'page' => 2]));

    $response->assertOk();
    expect($response->json('html'))->toContain('page=1');
});
