<?php

namespace App\Services;

use App\Models\User;
use App\Models\Employee;
use App\Models\Evaluation;
use App\Models\Role;
use App\Models\Position;

class DashboardService
{
    public function getDashboardData(User $user): array
    {
        if ($user->role === 'administrator')
        {
            return $this->getAdministratorData();
        } elseif ($user->role === 'evaluator')
        {
            return $this->getEvaluatorData($user);
        } else {
            return $this->getEmployeeData($user);
        }
    }

    protected function getAdministratorData(): array
    {
        $users = User::all();
        $employees = Employee::all();
        $evaluations = Evaluation::all();
        $roles = Role::all();
        $positions = Position::all();

        return [
            'user' => null,
            'users' => $users,
            'totalUsers' => $users->count(),
            'totalEmployees' => $employees->count(),
            'totalEvaluations' => $evaluations->count(),
            'totalRoles' => $roles->count(),
            'totalPositions' => $positions->count(),
            'completedEvaluations' => $evaluations->where('status', 'completed')->count(),
            'dashboardType' => 'administrator',
        ];
    }

    protected function getUserData(User $user): array
    {
        return [
            'user' => $user,
        ];
    }

    protected function getEvaluatorData(User $user): array
    {
        $myEmployees = Employee::where('evaluator_id', $user->id)->get();
        $myEvaluations = Evaluation::where('evaluator_id', $user->id)->get();
        $completedEvaluations = $myEvaluations->where('status', 'completed')->count();

        return [
            'user' => $user,
            'myEmployees' => $myEmployees,
            'myEvaluations' => $myEvaluations,
            'assignedEmployeesCount' => $myEmployees->count(),
            'totalEvaluationsCount' => $myEvaluations->count(),
            'completedEvaluations' => $completedEvaluations,
            'dashboardType' => 'evaluator',
        ];
    }

    protected function getEmployeeData(User $user): array
    {
        $employee = Employee::where('user_id', $user->id)->first();
        $myEvaluations = $employee 
            ? Evaluation::where('employee_id', $employee->id)->get()
            : collect();

        return [
            'user' => $user,
            'employee' => $employee,
            'myEvaluations' => $myEvaluations,
            'dashboardType' => 'employee',
        ];
    }
}
