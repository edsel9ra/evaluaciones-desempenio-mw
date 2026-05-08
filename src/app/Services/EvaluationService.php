<?php

namespace App\Services;

use App\Models\Evaluation;
use App\Models\EvaluationItem;
use App\Models\Employee;
use Illuminate\Support\Facades\Auth;

class EvaluationService
{
    public function getAllEvaluations($request = null)
    {
        $user = Auth::user();
        $query = Evaluation::with(['employee', 'evaluator', 'items']);

        if ($user->role === 'evaluator') {
            $query->where('evaluator_id', $user->id);
        }

        return $query->orderByDesc('created_at')->get();
    }

    public function getMyEvaluations()
    {
        $user = Auth::user();
        $employee = Employee::where('user_id', $user->id)->first();
        
        if (!$employee) {
            return [];
        }

        return Evaluation::with(['evaluator'])
            ->where('employee_id', $employee->id)
            ->orderByDesc('created_at')
            ->get();
    }

    public function getEmployeesForEvaluation()
    {
        $user = Auth::user();

        if ($user->role === 'administrator') {
            return Employee::with(['role', 'position'])->get();
        }

        return Employee::where('evaluator_id', $user->id)
            ->with(['role', 'position'])
            ->get();
    }

    public function getPeriods(): array
    {
        return [
            'Q1 2026' => 'Q1 2026 (Jan-Mar)',
            'Q2 2026' => 'Q2 2026 (Apr-Jun)',
            'Q3 2026' => 'Q3 2026 (Jul-Sep)',
            'Q4 2026' => 'Q4 2026 (Oct-Dec)',
            'Annual 2025' => 'Annual 2025',
            'Annual 2026' => 'Annual 2026',
            'Mid-Year 2025' => 'Mid-Year 2025',
            'Year-End 2025' => 'Year-End 2025',
        ];
    }

    public function createEvaluation(array $data): array
    {
        try {
            $evaluatorId = Auth::id();
            $employeeIds = $data['employee_ids'] ?? [];
            $period = $data['period'] ?? null;
            $selectedCompetencies = $data['selected_competencies'] ?? [];
            $selectedIndicators = $data['selected_indicators'] ?? [];

            if (empty($employeeIds)) {
                return ['success' => false, 'message' => 'No employees selected'];
            }

            foreach ($employeeIds as $employeeId) {
                $employee = Employee::with(['role', 'position'])->find($employeeId);
                
                $employeeCompetencies = [];
                if (!empty($selectedCompetencies) && $employee && $employee->role) {
                    $roleId = $employee->role_id;
                    $employeeCompetencies = array_filter($selectedCompetencies, function ($comp) use ($roleId) {
                        $competency = \App\Models\Competency::find($comp['id']);
                        if ($competency && $competency->roles) {
                            return in_array($roleId, array_column($competency->roles->toArray(), 'id'));
                        }
                        return false;
                    });
                    $employeeCompetencies = array_values($employeeCompetencies);
                }

                $employeeIndicators = [];
                if (!empty($selectedIndicators) && $employee && $employee->position) {
                    $positionId = $employee->position_id;
                    $employeeIndicators = array_filter($selectedIndicators, function ($ind) use ($positionId) {
                        $indicator = \App\Models\Indicator::find($ind['id']);
                        if ($indicator && $indicator->positions) {
                            return in_array($positionId, array_column($indicator->positions->toArray(), 'id'));
                        }
                        return false;
                    });
                    $employeeIndicators = array_values($employeeIndicators);
                }

                Evaluation::create([
                    'employee_id' => $employeeId,
                    'evaluator_id' => $evaluatorId,
                    'period' => $period,
                    'status' => 'pending',
                    'selected_competencies' => $employeeCompetencies,
                    'selected_indicators' => $employeeIndicators,
                ]);
            }

            return ['success' => true, 'message' => 'Evaluations created'];
        } catch (\Exception $e) {
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }

    public function getCompetenciesAndIndicators()
    {
        return [
            'competencies' => \App\Models\Competency::with('roles')->get(),
            'indicators' => \App\Models\Indicator::with('positions')->get(),
        ];
    }

    public function getEvaluationWithItems(int $id)
    {
        $evaluation = Evaluation::with(['employee', 'evaluator', 'items'])->findOrFail($id);
        
        $selectedCompetencies = $evaluation->selected_competencies ?? [];
        $selectedIndicators = $evaluation->selected_indicators ?? [];
        
        $competencies = \App\Models\Competency::whereIn('id', array_column($selectedCompetencies, 'id'))->get();
        $indicators = \App\Models\Indicator::whereIn('id', array_column($selectedIndicators, 'id'))->get();
        
        $evaluation->selected_competencies = collect($selectedCompetencies)->map(function ($item) use ($competencies) {
            $comp = $competencies->find($item['id']);
            return [
                'id' => $item['id'],
                'competency' => $comp,
            ];
        })->all();
        
        $evaluation->selected_indicators = collect($selectedIndicators)->map(function ($item) use ($indicators) {
            $ind = $indicators->find($item['id']);
            return [
                'id' => $item['id'],
                'indicator' => $ind,
            ];
        })->all();
        
        $evaluation->competency_score = $evaluation->competency_score ?? 0;
        $evaluation->indicator_score = $evaluation->indicator_score ?? 0;
        $evaluation->total_score = $evaluation->total_score ?? 0;
        
        return $evaluation;
    }

    public function saveEvaluationProgress(int $id, array $data): array
    {
        try {
            $evaluation = Evaluation::findOrFail($id);

            $items = $data['items'] ?? [];
            $feedback = $data['feedback'] ?? null;

            foreach ($items as $itemData) {
                if (isset($itemData['score'])) {
                    EvaluationItem::updateOrCreate(
                        [
                            'evaluation_id' => $evaluation->id,
                            'item_type' => $itemData['item_type'],
                            'item_id' => $itemData['item_id'],
                        ],
                        [
                            'score' => $itemData['score'],
                            'comments' => $itemData['comments'] ?? null,
                        ]
                    );
                }
            }

            $scoringData = $this->calculateTotalScore($evaluation->id);

            $newStatus = 'in_progress';
            if ($evaluation->status === 'pending') {
                $newStatus = 'in_progress';
            }

            $evaluation->update([
                'status' => $newStatus,
                'feedback' => $feedback,
                'total_score' => $scoringData['final_score'],
                'competency_score' => $scoringData['competency_score'],
                'indicator_score' => $scoringData['indicator_score'],
            ]);

            return [
                'success' => true,
                'total_score' => $scoringData['final_score'],
                'competency_score' => $scoringData['competency_score'],
                'indicator_score' => $scoringData['indicator_score'],
                'status' => $newStatus,
                'message' => 'Progress saved'
            ];
        } catch (\Exception $e) {
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }

    public function completeEvaluation(int $id, array $data): array
    {
        try {
            $evaluation = Evaluation::findOrFail($id);

            $items = $data['items'] ?? [];
            $feedback = $data['feedback'] ?? null;

            foreach ($items as $itemData) {
                if (isset($itemData['score'])) {
                    EvaluationItem::updateOrCreate(
                        [
                            'evaluation_id' => $evaluation->id,
                            'item_type' => $itemData['item_type'],
                            'item_id' => $itemData['item_id'],
                        ],
                        [
                            'score' => $itemData['score'],
                            'comments' => $itemData['comments'] ?? null,
                        ]
                    );
                }
            }

            $scoringData = $this->calculateTotalScore($evaluation->id);

            $evaluation->update([
                'status' => 'completed',
                'feedback' => $feedback,
                'total_score' => $scoringData['final_score'],
                'competency_score' => $scoringData['competency_score'],
                'indicator_score' => $scoringData['indicator_score'],
                'evaluator_completed_date' => now()->toDateString(),
            ]);

            return [
                'success' => true,
                'total_score' => $scoringData['final_score'],
                'competency_score' => $scoringData['competency_score'],
                'indicator_score' => $scoringData['indicator_score'],
                'status' => 'completed',
                'message' => 'Evaluation completed'
            ];
        } catch (\Exception $e) {
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }

    public function calculateTotalScore(int $evaluationId): array
    {
        $items = EvaluationItem::where('evaluation_id', $evaluationId)->get();

        $competencyScores = [];
        $indicatorScores = [];

        foreach ($items as $item) {
            if ($item->item_type === 'competency') {
                $competencyScores[] = $item->score ?? 0;
            } else {
                $indicatorScores[] = $item->score ?? 0;
            }
        }

        $competencyScore = count($competencyScores) > 0 
            ? array_sum($competencyScores) / count($competencyScores) 
            : 0;

        $indicatorScore = count($indicatorScores) > 0 
            ? array_sum($indicatorScores) / count($indicatorScores) 
            : 0;

        $finalScore = ($competencyScore * 0.3) + ($indicatorScore * 0.7);

        return [
            'competency_score' => $competencyScore,
            'indicator_score' => $indicatorScore,
            'final_score' => $finalScore,
            'competency_count' => count($competencyScores),
            'indicator_count' => count($indicatorScores),
        ];
    }
}
