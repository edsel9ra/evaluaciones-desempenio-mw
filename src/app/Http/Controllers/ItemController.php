<?php

namespace App\Http\Controllers;

use App\Services\ItemService;
use App\Services\EmployeeService;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ItemController extends Controller
{
    protected ItemService $itemService;
    protected EmployeeService $employeeService;

    public function __construct(ItemService $itemService, EmployeeService $employeeService)
    {
        $this->itemService = $itemService;
        $this->employeeService = $employeeService;
    }

    public function index()
    {
        $competencies = $this->itemService->getAllCompetencies();
        $indicators = $this->itemService->getAllIndicators();
        $roles = $this->employeeService->getAllRoles();
        $positions = $this->employeeService->getAllPositions();
        
        return Inertia::render('ManageItems', [
            'competencies' => $competencies,
            'indicators' => $indicators,
            'roles' => $roles,
            'positions' => $positions,
        ]);
    }

    public function storeCompetency(Request $request)
    {
        $this->itemService->createCompetency($request->all());
        return back()->with('success', 'Competency created successfully');
    }

    public function updateCompetency(Request $request, int $id)
    {
        $this->itemService->updateCompetency($id, $request->all());
        return back()->with('success', 'Competency updated successfully');
    }

    public function destroyCompetency(int $id)
    {
        $this->itemService->deleteCompetency($id);
        return back()->with('success', 'Competency deleted successfully');
    }

    public function storeIndicator(Request $request)
    {
        $this->itemService->createIndicator($request->all());
        return back()->with('success', 'Indicator created successfully');
    }

    public function updateIndicator(Request $request, int $id)
    {
        $this->itemService->updateIndicator($id, $request->all());
        return back()->with('success', 'Indicator updated successfully');
    }

    public function destroyIndicator(int $id)
    {
        $this->itemService->deleteIndicator($id);
        return back()->with('success', 'Indicator deleted successfully');
    }

    public function attachRoleCompetency(Request $request)
    {
        $this->itemService->attachRoleCompetency($request->role_id, $request->competency_id);
        return back()->with('success', 'Competency attached to role successfully');
    }

    public function detachRoleCompetency(Request $request)
    {
        $this->itemService->detachRoleCompetency($request->role_id, $request->competency_id);
        return back()->with('success', 'Competency detached from role successfully');
    }

    public function attachPositionIndicator(Request $request)
    {
        $this->itemService->attachPositionIndicator($request->position_id, $request->indicator_id);
        return back()->with('success', 'Indicator attached to position successfully');
    }

    public function detachPositionIndicator(Request $request)
    {
        $this->itemService->detachPositionIndicator($request->position_id, $request->indicator_id);
        return back()->with('success', 'Indicator detached from position successfully');
    }
}
