<?php

namespace Database\Factories;

use App\Models\Position;
use Illuminate\Database\Eloquent\Factories\Factory;

class PositionFactory extends Factory
{
    protected $model = Position::class;

    public function definition(): array
    {
        return [
            'name' => fake()->randomElement(['Senior Developer', 'Junior Developer', 'Tech Lead', 'Product Manager', 'UX Designer']),
            'level' => fake()->randomElement(['Junior', 'Mid', 'Senior', 'Lead']),
            'salary_range' => '$' . fake()->numberBetween(30000, 100000) . ' - $' . fake()->numberBetween(50000, 150000),
            'description' => fake()->sentence(),
        ];
    }
}