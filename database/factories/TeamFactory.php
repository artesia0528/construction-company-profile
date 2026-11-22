<?php

namespace Database\Factories;

use App\Models\Team;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Team>
 */
class TeamFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'position' => fake()->randomElement(['Project Director', 'Lead Architect', 'Site Manager', 'Civil Engineer']),
            'biography' => fake()->paragraph(),
            'profile_photo' => null,
            'display_order' => fake()->numberBetween(1, 20),
            'is_active' => true,
        ];
    }
}
