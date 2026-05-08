<?php

namespace Database\Factories;

use App\Models\Competency;
use Illuminate\Database\Eloquent\Factories\Factory;

class CompetencyFactory extends Factory
{
    protected $model = Competency::class;

    public function definition(): array
    {
        return [
            'name' => fake()->randomElement(['Communication', 'Leadership', 'Teamwork', 'Problem Solving', 'Adaptability']),
            'category' => fake()->randomElement(['Technical', 'Soft Skills', 'Leadership', 'Innovation']),
            'description' => fake()->sentence(),
            'weight' => fake()->randomFloat(2, 0.5, 2.0),
        ];
    }
}