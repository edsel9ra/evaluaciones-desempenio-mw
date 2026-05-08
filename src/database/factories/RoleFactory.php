<?php

namespace Database\Factories;

use App\Models\Role;
use Illuminate\Database\Eloquent\Factories\Factory;

class RoleFactory extends Factory
{
    protected $model = Role::class;

    public function definition(): array
    {
        return [
            'name' => fake()->randomElement(['Manager', 'Developer', 'Designer', 'Analyst', 'Director']),
            'description' => fake()->sentence(),
            'department' => fake()->randomElement(['IT', 'HR', 'Finance', 'Operations', 'Marketing']),
        ];
    }
}