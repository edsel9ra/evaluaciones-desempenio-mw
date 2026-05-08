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
            ->with(['items', 'evaluator'])
            ->orderBy('evaluator_completed_date')
            ->get();

        $allCompetencies = \App\Models\Competency::all();
        $allIndicators = \App\Models\Indicator::all();

        $evaluationsWithItems = $evaluations->map(function ($eval) use ($allCompetencies, $allIndicators) {
            $competencyData = [];
            $indicatorData = [];

            foreach ($eval->items ?? [] as $item) {
                if ($item->item_type === 'competency') {
                    $comp = $allCompetencies->find($item->item_id);
                    if ($comp) {
                        $competencyData[] = [
                            'id' => $comp->id,
                            'name' => $comp->name,
                            'score' => $item->score,
                        ];
                    }
                } else {
                    $ind = $allIndicators->find($item->item_id);
                    if ($ind) {
                        $indicatorData[] = [
                            'id' => $ind->id,
                            'name' => $ind->name,
                            'score' => $item->score,
                        ];
                    }
                }
            }

            return [
                'id' => $eval->id,
                'period' => $eval->period,
                'status' => $eval->status,
                'total_score' => $eval->total_score,
                'competency_score' => $eval->competency_score,
                'indicator_score' => $eval->indicator_score,
                'created_at' => $eval->created_at,
                'evaluator_completed_date' => $eval->evaluator_completed_date,
                'evaluator' => $eval->evaluator,
                'competencies' => $competencyData,
                'indicators' => $indicatorData,
            ];
        });

        $scores = $evaluations->pluck('total_score')->toArray();
        $avgScore = count($scores) > 0 ? array_sum($scores) / count($scores) : 0;

        return [
            'employee' => $employee,
            'evaluations' => $evaluationsWithItems,
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
