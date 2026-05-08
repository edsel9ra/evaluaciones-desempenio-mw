<?php

namespace Database\Factories;

use App\Models\Employee;
use App\Models\User;
use App\Models\Role;
use App\Models\Position;
use Illuminate\Database\Eloquent\Factories\Factory;

class EmployeeFactory extends Factory
{
    protected $model = Employee::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'name' => fake()->name(),
            'role_id' => Role::factory(),
            'position_id' => Position::factory(),
            'evaluator_id' => null,
            'department' => fake()->randomElement(['IT', 'HR', 'Finance', 'Operations', 'Marketing']),
            'hire_date' => fake()->date(),
            'status' => 'active',
        ];
    }
}