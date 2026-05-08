<?php

namespace App\Services;

use App\Models\Employee;
use App\Models\Role;
use App\Models\Position;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class EmployeeService
{
    public function getAllEmployees(Request $request)
    {
        $query = Employee::with(['role', 'position', 'evaluator', 'user']);

        if ($request && $request->has('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        return $query->get();
    }

    public function getAllRoles()
    {
        return Role::with('competencies')->get();
    }

    public function getAllPositions()
    {
        return Position::with('indicators')->get();
    }

    public function getEvaluators()
    {
        return User::whereIn('role', ['evaluator', 'administrator'])->get();
    }

    public function createEmployee(array $data): Employee
    {
        $user = User::create([
            'name' => $data['name'],
            'username' => $data['username'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'role' => $data['user_role'],
        ]);

        return Employee::create([
            'user_id' => $user->id,
            'name' => $data['name'],
            'role_id' => $data['role_id'] ?? null,
            'position_id' => $data['position_id'] ?? null,
            'evaluator_id' => $data['evaluator_id'] ?? null,
            'department' => $data['department'],
            'status' => 'active',
        ]);
    }

    public function updateEmployee(int $id, array $data): Employee
    {
        $employee = Employee::findOrFail($id);

        if (isset($data['user_id'])) {
            $userData = [
                'name' => $data['name'],
                'email' => $data['email'],
                'role' => $data['user_role'],
            ];

            if (!empty($data['password'])) {
                $userData['password'] = Hash::make($data['password']);
            }

            $employee->user->update($userData);
        }

        $employee->update([
            'name' => $data['name'],
            'role_id' => $data['role_id'] ?? null,
            'position_id' => $data['position_id'] ?? null,
            'evaluator_id' => $data['evaluator_id'] ?? null,
            'department' => $data['department'],
        ]);

        return $employee;
    }

    public function deleteEmployee(int $id): void
    {
        $employee = Employee::findOrFail($id);
        if ($employee->user) {
            $employee->user->delete();
        }
        $employee->delete();
    }
}
