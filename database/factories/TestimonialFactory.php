<?php

namespace Database\Factories;

use App\Models\Testimonial;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Testimonial>
 */
class TestimonialFactory extends Factory
{
    public function definition(): array
    {
        return [
            'client_name' => fake()->name(),
            'client_position' => fake()->jobTitle(),
            'company_name' => fake()->company(),
            'testimonial_content' => fake()->paragraph(),
            'client_photo' => null,
            'rating' => fake()->numberBetween(4, 5),
            'is_active' => true,
        ];
    }
}
