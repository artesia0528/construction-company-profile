<?php

namespace Database\Factories;

use App\Models\Project;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Project>
 */
class ProjectFactory extends Factory
{
    public function definition(): array
    {
        $title = fake()->unique()->sentence(3);

        return [
            'title' => $title,
            'slug' => str($title)->slug()->toString(),
            'short_description' => fake()->sentence(16),
            'full_description' => fake()->paragraphs(4, true),
            'category' => fake()->randomElement(['Commercial', 'Residential', 'Infrastructure', 'Renovation']),
            'location' => fake()->city().', Indonesia',
            'client_name' => fake()->company(),
            'completion_year' => fake()->numberBetween(2018, 2026),
            'status' => fake()->randomElement(['completed', 'in_progress']),
            'cover_image' => null,
            'is_featured' => fake()->boolean(30),
            'is_published' => true,
        ];
    }
}
