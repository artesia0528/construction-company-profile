<?php

use App\Models\Project;
use App\Models\ProjectImage;

test('project detail renders gallery lightbox triggers', function () {
    $project = Project::factory()->create(['is_published' => true]);
    ProjectImage::factory()->create([
        'project_id' => $project->id,
        'image_path' => 'projects/gallery/sample.webp',
        'alt_text' => 'Sample gallery image',
        'sort_order' => 1,
    ]);

    $response = $this->get(route('projects.show', $project));

    $response->assertOk()
        ->assertSee('data-lightbox-gallery="project-gallery"', false)
        ->assertSee('data-lightbox-trigger', false)
        ->assertSee('data-lightbox-src=', false)
        ->assertSee('Open image 1: Sample gallery image', false);
});
