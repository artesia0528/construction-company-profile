<?php

namespace Database\Factories;

use App\Models\Statistic;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Statistic>
 */
class StatisticFactory extends Factory
{
    public function definition(): array
    {
        return [
            'label' => fake()->words(2, true),
            'value' => (string) fake()->numberBetween(10, 500),
            'icon' => 'chart-bar',
            'display_order' => fake()->numberBetween(1, 10),
            'is_active' => true,
        ];
    }
}
