<?php

namespace Database\Factories;

use App\Models\EvaluationItem;
use App\Models\Evaluation;
use Illuminate\Database\Eloquent\Factories\Factory;

class EvaluationItemFactory extends Factory
{
    protected $model = EvaluationItem::class;

    public function definition(): array
    {
        return [
            'evaluation_id' => Evaluation::factory(),
            'item_type' => fake()->randomElement(['competency', 'indicator']),
            'item_id' => fake()->numberBetween(1, 10),
            'weight' => fake()->randomFloat(2, 0.5, 2.0),
            'score' => fake()->randomFloat(2, 0, 100),
            'comments' => fake()->optional()->sentence(),
        ];
    }
}