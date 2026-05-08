<?php

namespace App\Services;

use App\Models\Evaluation;
use App\Models\Employee;
use Illuminate\Support\Facades\Auth;

class ReportService
{
    public function getAllEmployeesForReport()
    {
        $user = Auth::user();
        
        if ($user->role === 'evaluator') {
            return Employee::where('evaluator_id', $user->id)
                ->with(['role', 'position'])
                ->get();
        }
        
        return Employee::with(['role', 'position'])->get();
    }

    public function generateIndividualReport(int $employeeId)
    {
        $employee = Employee::with(['role', 'position'])->findOrFail($employeeId);
        $evaluations = Evaluation::where('employee_id', $employeeId)
            ->where('status', 'completed')
            ->orderBy('evaluator_completed_date')
            ->get();

        $scores = $evaluations->pluck('total_score')->toArray();
        $avgScore = count($scores) > 0 ? array_sum($scores) / count($scores) : 0;

        return [
            'employee' => $employee,
            'evaluations' => $evaluations,
            'averageScore' => round($avgScore, 2),
            'highestScore' => count($scores) > 0 ? max($scores) : 0,
            'lowestScore' => count($scores) > 0 ? min($scores) : 0,
        ];
    }

    public function generateGroupReport(array $filters = [])
    {
        $user = Auth::user();
        $query = Evaluation::where('status', 'completed')
            ->with(['employee', 'employee.role']);

        if ($user->role === 'evaluator') {
            $query->where('evaluator_id', $user->id);
        }

        if (isset($filters['department'])) {
            $query->whereHas('employee', function ($q) use ($filters) {
                $q->where('department', $filters['department']);
            });
        }

        $evaluations = $query->get();

        $groupedByEmployee = $evaluations->groupBy('employee_id');
        $employeeStats = [];

        foreach ($groupedByEmployee as $employeeId => $evals) {
            $scores = $evals->pluck('total_score')->toArray();
            $employeeStats[] = [
                'employee_id' => $employeeId,
                'employee_name' => $evals->first()->employee->name,
                'total_evaluations' => count($evals),
                'average_score' => round(array_sum($scores) / count($scores), 2),
            ];
        }

        return collect($employeeStats)->sortByDesc('average_score')->values()->all();
    }

    public function generateTrendsReport()
    {
        $user = Auth::user();
        $query = Evaluation::where('status', 'completed')
            ->whereNotNull('evaluator_completed_date')
            ->orderBy('evaluator_completed_date');

        if ($user->role === 'evaluator') {
            $query->where('evaluator_id', $user->id);
        }

        $evaluations = $query->get();

        $groupedByPeriod = $evaluations->groupBy('period');
        $trends = [];

        foreach ($groupedByPeriod as $period => $evals) {
            $scores = $evals->pluck('total_score')->toArray();
            $trends[] = [
                'period' => $period,
                'total_evaluations' => count($evals),
                'average_score' => round(array_sum($scores) / count($scores), 2),
            ];
        }

        return $trends;
    }
}
