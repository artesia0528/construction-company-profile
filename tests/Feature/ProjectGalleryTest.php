<?php

use App\Models\Project;
use App\Models\ProjectImage;

test('project detail renders an alpine gallery dialog', function () {
    $project = Project::factory()->create(['is_published' => true]);
    ProjectImage::factory()->create([
        'project_id' => $project->id,
        'image_path' => 'projects/gallery/sample.webp',
        'alt_text' => 'Sample gallery image',
        'sort_order' => 1,
    ]);

    $response = $this->get(route('projects.show', $project));

    $response->assertOk()
        ->assertSee('@click.prevent="openAt(0, $refs.viewer)"', false)
        ->assertSee('x-ref="viewer"', false)
        ->assertSee('aria-label="Image viewer"', false)
        ->assertSee('showModal()', false)
        ->assertSee('Open image 1: Sample gallery image', false)
        ->assertDontSee('data-lightbox-gallery', false)
        ->assertDontSee('data-lightbox-trigger', false);
});
