<?php

namespace Database\Factories;

use App\Models\Indicator;
use Illuminate\Database\Eloquent\Factories\Factory;

class IndicatorFactory extends Factory
{
    protected $model = Indicator::class;

    public function definition(): array
    {
        return [
            'name' => fake()->randomElement(['Code Quality', 'Meeting Deadlines', 'Documentation', 'Innovation', 'Collaboration']),
            'category' => fake()->randomElement(['Technical', 'Performance', 'Quality', 'Teamwork']),
            'description' => fake()->sentence(),
            'weight' => fake()->randomFloat(2, 0.5, 2.0),
        ];
    }
}