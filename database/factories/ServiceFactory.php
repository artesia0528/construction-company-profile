<?php

namespace Database\Factories;

use App\Models\Service;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Service>
 */
class ServiceFactory extends Factory
{
    public function definition(): array
    {
        $name = fake()->unique()->words(3, true);

        return [
            'name' => str($name)->title()->toString(),
            'slug' => str($name)->slug()->toString(),
            'short_description' => fake()->sentence(14),
            'full_description' => fake()->paragraphs(3, true),
            'icon' => 'building-office',
            'thumbnail_image' => null,
            'display_order' => fake()->numberBetween(1, 20),
            'is_active' => true,
        ];
    }
}
