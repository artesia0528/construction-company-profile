<?php

namespace Database\Factories;

use App\Models\Project;
use App\Models\ProjectImage;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<ProjectImage>
 */
class ProjectImageFactory extends Factory
{
    public function definition(): array
    {
        return [
            'project_id' => Project::factory(),
            'image_path' => 'projects/placeholders/project-'.fake()->numberBetween(1, 6).'.jpg',
            'alt_text' => fake()->sentence(4),
            'sort_order' => fake()->numberBetween(0, 10),
        ];
    }
}
