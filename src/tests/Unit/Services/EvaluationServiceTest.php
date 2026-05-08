<?php

namespace Tests\Unit\Services;

use Tests\TestCase;
use App\Models\Evaluation;
use App\Models\EvaluationItem;
use App\Models\Employee;
use App\Models\User;
use App\Models\Competency;
use App\Models\Indicator;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Mockery;

class EvaluationServiceTest extends TestCase
{
    use RefreshDatabase;

    protected EvaluationService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new \App\Services\EvaluationService();
    }

    public function test_get_all_evaluations_returns_empty_when_no_user(): void
    {
        Auth::shouldReceive('user')->andReturn(null)->once();
        
        $result = $this->service->getAllEvaluations();
        
        $this->assertEmpty($result);
    }

    public function test_get_all_evaluations_filters_by_evaluator_role(): void
    {
        $user = User::factory()->create(['role' => 'evaluator']);
        Auth::login($user);

        $evaluator = User::factory()->create(['role' => 'evaluator']);
        $employee = Employee::factory()->create();
        
        Evaluation::factory()->create([
            'evaluator_id' => $evaluator->id,
            'employee_id' => $employee->id,
            'status' => 'pending'
        ]);

        $otherEvaluation = Evaluation::factory()->create([
            'evaluator_id' => $user->id,
            'employee_id' => $employee->id,
            'status' => 'pending'
        ]);

        $result = $this->service->getAllEvaluations();

        $this->assertCount(1, $result);
        $this->assertEquals($user->id, $result->first()->evaluator_id);
    }

    public function test_create_evaluation_returns_error_when_no_employees_selected(): void
    {
        $user = User::factory()->create();
        Auth::login($user);

        $result = $this->service->createEvaluation([
            'employee_ids' => [],
            'period' => 'Q1 2026'
        ]);

        $this->assertFalse($result['success']);
        $this->assertEquals('No employees selected', $result['message']);
    }

    public function test_create_evaluation_creates_evaluations_for_all_employees(): void
    {
        $user = User::factory()->create();
        Auth::login($user);

        $employee1 = Employee::factory()->create();
        $employee2 = Employee::factory()->create();

        $result = $this->service->createEvaluation([
            'employee_ids' => [$employee1->id, $employee2->id],
            'period' => 'Q1 2026',
            'selected_competencies' => [],
            'selected_indicators' => []
        ]);

        $this->assertTrue($result['success']);
        $this->assertEquals(2, Evaluation::count());
    }

    public function test_calculate_total_score_returns_zero_when_no_items(): void
    {
        $employee = Employee::factory()->create();
        $evaluation = Evaluation::factory()->create([
            'employee_id' => $employee->id
        ]);

        $result = $this->service->calculateTotalScore($evaluation->id);

        $this->assertEquals(0, $result['competency_score']);
        $this->assertEquals(0, $result['indicator_score']);
        $this->assertEquals(0, $result['final_score']);
    }

    public function test_calculate_total_score_calculates_correctly(): void
    {
        $employee = Employee::factory()->create();
        $evaluation = Evaluation::factory()->create([
            'employee_id' => $employee->id
        ]);

        EvaluationItem::create([
            'evaluation_id' => $evaluation->id,
            'item_type' => 'competency',
            'item_id' => 1,
            'score' => 80
        ]);

        EvaluationItem::create([
            'evaluation_id' => $evaluation->id,
            'item_type' => 'indicator',
            'item_id' => 1,
            'score' => 90
        ]);

        $result = $this->service->calculateTotalScore($evaluation->id);

        $this->assertEquals(80, $result['competency_score']);
        $this->assertEquals(90, $result['indicator_score']);
        $expectedFinal = (80 * 0.3) + (90 * 0.7);
        $this->assertEquals($expectedFinal, $result['final_score']);
    }

    public function test_get_my_evaluations_returns_empty_when_no_employee(): void
    {
        $user = User::factory()->create();
        Auth::login($user);

        $result = $this->service->getMyEvaluations();

        $this->assertEmpty($result);
    }

    public function test_get_employees_for_evaluation_returns_all_for_admin(): void
    {
        $admin = User::factory()->create(['role' => 'administrator']);
        Auth::login($admin);

        Employee::factory()->count(3)->create();

        $result = $this->service->getEmployeesForEvaluation();

        $this->assertCount(3, $result);
    }

    public function test_get_evaluation_with_items_handles_missing_competencies(): void
    {
        $employee = Employee::factory()->create();
        $evaluation = Evaluation::factory()->create([
            'employee_id' => $employee->id,
            'selected_competencies' => [['id' => 999]],
            'selected_indicators' => [['id' => 999]]
        ]);

        $result = $this->service->getEvaluationWithItems($evaluation->id);

        $this->assertNotNull($result);
        $this->assertNull($result->selected_competencies[0]['competency']);
        $this->assertNull($result->selected_indicators[0]['indicator']);
    }

    public function test_get_periods_returns_expected_values(): void
    {
        $result = $this->service->getPeriods();

        $this->assertArrayHasKey('Q1 2026', $result);
        $this->assertArrayHasKey('Q2 2026', $result);
        $this->assertArrayHasKey('Annual 2026', $result);
        $this->assertEquals('Q1 2026 (Jan-Mar)', $result['Q1 2026']);
    }
}