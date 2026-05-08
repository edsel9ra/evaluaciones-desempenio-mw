<?php

namespace App\Http\Controllers;

use App\Http\Requests\EmployeeRequest;
use App\Services\EmployeeService;
use App\Services\RoleService;
use App\Services\PositionService;
use Illuminate\Http\Request;
use Inertia\Inertia;

class EmployeeController extends Controller
{
    protected EmployeeService $employeeService;
    protected RoleService $roleService;
    protected PositionService $positionService;

    public function __construct(EmployeeService $employeeService, RoleService $roleService, PositionService $positionService)
    {
        $this->employeeService = $employeeService;
        $this->roleService = $roleService;
        $this->positionService = $positionService;
    }

    public function index(Request $request)
    {
        $employees = $this->employeeService->getAllEmployees($request);
        $roles = $this->employeeService->getAllRoles();
        $positions = $this->employeeService->getAllPositions();
        $evaluators = $this->employeeService->getEvaluators();
        
        return Inertia::render('ManageEmployees', [
            'employees' => $employees,
            'roles' => $roles,
            'positions' => $positions,
            'evaluators' => $evaluators,
        ]);
    }

    public function store(EmployeeRequest $request)
    {
        $this->employeeService->createEmployee($request->validated());
        return back()->with('success', 'Employee created successfully');
    }

    public function update(EmployeeRequest $request, int $id)
    {
        $this->employeeService->updateEmployee($id, $request->validated());
        return back()->with('success', 'Employee updated successfully');
    }

    public function destroy(int $id)
    {
        $this->employeeService->deleteEmployee($id);
        return back()->with('success', 'Employee deleted successfully');
    }

    // Role methods
    public function storeRole(Request $request)
    {
        $this->roleService->createRole($request->all());
        return back()->with('success', 'Role created successfully');
    }

    public function updateRole(Request $request, int $id)
    {
        $this->roleService->updateRole($id, $request->all());
        return back()->with('success', 'Role updated successfully');
    }

    public function destroyRole(int $id)
    {
        $this->roleService->deleteRole($id);
        return back()->with('success', 'Role deleted successfully');
    }

    // Position methods
    public function storePosition(Request $request)
    {
        $this->positionService->createPosition($request->all());
        return back()->with('success', 'Position created successfully');
    }

    public function updatePosition(Request $request, int $id)
    {
        $this->positionService->updatePosition($id, $request->all());
        return back()->with('success', 'Position updated successfully');
    }

    public function destroyPosition(int $id)
    {
        $this->positionService->deletePosition($id);
        return back()->with('success', 'Position deleted successfully');
    }
}
