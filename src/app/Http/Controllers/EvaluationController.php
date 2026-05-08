<?php

namespace App\Http\Controllers;

use App\Services\EvaluationService;
use Illuminate\Http\Request;
use Inertia\Inertia;

class EvaluationController extends Controller
{
    protected EvaluationService $evaluationService;

    public function __construct(EvaluationService $evaluationService)
    {
        $this->evaluationService = $evaluationService;
    }

    public function index(Request $request)
    {
        $evaluations = $this->evaluationService->getAllEvaluations($request);
        return Inertia::render('ViewEvaluations', ['evaluations' => $evaluations]);
    }

    public function create(Request $request)
    {
        $employees = $this->evaluationService->getEmployeesForEvaluation();
        $periods = $this->evaluationService->getPeriods();
        $items = $this->evaluationService->getCompetenciesAndIndicators();
        return Inertia::render('CreateEvaluation', [
            'employees' => $employees,
            'periods' => $periods,
            'competencies' => $items['competencies'],
            'indicators' => $items['indicators'],
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'period' => 'required|string',
            'employee_ids' => 'required|array',
            'employee_ids.*' => 'required|integer',
            'selected_competencies' => 'nullable|array',
            'selected_indicators' => 'nullable|array',
        ]);

        $result = $this->evaluationService->createEvaluation($validated);
        
        if ($result['success']) {
            return redirect()->route('view-evaluations')->with('success', 'Evaluation created successfully');
        }
        
        return back()->with('error', $result['message']);
    }

    public function show(int $id)
    {
        $evaluation = $this->evaluationService->getEvaluationWithItems($id);
        $items = $this->evaluationService->getCompetenciesAndIndicators();
        return Inertia::render('PerformEvaluation', [
            'evaluation' => $evaluation,
            'competencies' => $items['competencies'],
            'indicators' => $items['indicators'],
        ]);
    }

    public function update(Request $request, int $id)
    {
        $data = $request->all();
        
        if (isset($data['items']) && is_string($data['items'])) {
            $data['items'] = json_decode($data['items'], true) ?? [];
        }
        
        if (($data['action'] ?? '') === 'complete') {
            $result = $this->evaluationService->completeEvaluation($id, $data);
            if ($result['success']) {
                return redirect()->route('view-evaluations')->with('success', 'Evaluation completed successfully!');
            }
        } else {
            $result = $this->evaluationService->saveEvaluationProgress($id, $data);
            if ($result['success']) {
                return back()->with('success', 'Progress saved successfully!');
            }
        }
        
        return back()->with('error', $result['message']);
    }

    public function getEvaluationWithItemsForEdit(int $id)
    {
        $evaluation = $this->evaluationService->getEvaluationWithItems($id);
        $items = $this->evaluationService->getCompetenciesAndIndicators();
        return Inertia::render('PerformEvaluation', [
            'evaluation' => $evaluation,
            'competencies' => $items['competencies'],
            'indicators' => $items['indicators'],
        ]);
    }

    public function myEvaluations()
    {
        $evaluations = $this->evaluationService->getMyEvaluations();
        return Inertia::render('MyEvaluations', ['evaluations' => $evaluations]);
    }
}
