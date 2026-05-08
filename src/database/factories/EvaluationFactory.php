<?php

namespace Database\Factories;

use App\Models\Evaluation;
use App\Models\Employee;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class EvaluationFactory extends Factory
{
    protected $model = Evaluation::class;

    public function definition(): array
    {
        return [
            'employee_id' => Employee::factory(),
            'evaluator_id' => User::factory(),
            'period' => fake()->randomElement(['Q1 2026', 'Q2 2026', 'Q3 2026', 'Q4 2026']),
            'status' => 'pending',
            'selected_competencies' => [],
            'selected_indicators' => [],
            'total_score' => null,
            'competency_score' => null,
            'indicator_score' => null,
            'feedback' => null,
        ];
    }

    public function completed(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'completed',
            'total_score' => fake()->randomFloat(2, 0, 100),
            'competency_score' => fake()->randomFloat(2, 0, 100),
            'indicator_score' => fake()->randomFloat(2, 0, 100),
        ]);
    }
}