<?php

use App\Livewire\Projects\ProjectIndex;
use App\Models\Project;
use Livewire\Livewire;

test('project index filters published projects by category', function () {
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
    Project::factory()->create([
        'title' => 'Hidden Gamma',
        'category' => 'Industrial',
        'is_published' => false,
    ]);

    Livewire::test(ProjectIndex::class)
        ->assertSee('Warehouse Alpha')
        ->assertSee('Office Beta')
        ->assertSee('wire:click.prevent="setCategory(\'Industrial\')"', false)
        ->assertSee('wire:transition="projects-grid"', false)
        ->call('setCategory', 'Industrial')
        ->assertSet('category', 'Industrial')
        ->assertSee('Warehouse Alpha')
        ->assertDontSee('Office Beta')
        ->assertDontSee('Hidden Gamma');
});

test('project index reads the category from the url', function () {
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

    Livewire::withQueryParams(['category' => 'Industrial'])
        ->test(ProjectIndex::class)
        ->assertSet('category', 'Industrial')
        ->assertSee('Warehouse Alpha')
        ->assertDontSee('Office Beta');
});

test('changing category resets pagination', function () {
    foreach (range(2001, 2012) as $year) {
        Project::factory()->create([
            'title' => "Industrial {$year}",
            'category' => 'Industrial',
            'completion_year' => $year,
            'is_published' => true,
        ]);
        Project::factory()->create([
            'title' => "Commercial {$year}",
            'category' => 'Commercial',
            'completion_year' => $year,
            'is_published' => true,
        ]);
    }

    Livewire::withQueryParams(['category' => 'Industrial', 'page' => 2])
        ->test(ProjectIndex::class)
        ->assertSet('category', 'Industrial')
        ->assertSee('Industrial 2003')
        ->assertDontSee('Industrial 2012')
        ->call('setCategory', 'Commercial')
        ->assertSee('Commercial 2012')
        ->assertDontSee('Commercial 2003');
});

test('project index shows the empty state when no published projects match', function () {
    Project::factory()->create([
        'category' => 'Industrial',
        'is_published' => false,
    ]);

    Livewire::test(ProjectIndex::class)
        ->assertSee('No projects found for this category.')
        ->assertSee('wire:transition="project-empty"', false);
});

test('projects page server renders the livewire listing', function () {
    Project::factory()->create([
        'title' => 'Warehouse Alpha',
        'is_published' => true,
    ]);

    $this->get(route('projects.index'))
        ->assertOk()
        ->assertSee('Warehouse Alpha');
});
