<?php

namespace App\Http\Controllers;

use App\Services\RoleService;
use Illuminate\Http\Request;
use Inertia\Inertia;

class RoleController extends Controller
{
    protected RoleService $roleService;

    public function __construct(RoleService $roleService)
    {
        $this->roleService = $roleService;
    }

    public function index()
    {
        return Inertia::render('ManageEmployees', [
            'roles' => $this->roleService->getAllRoles(),
        ]);
    }

    public function store(Request $request)
    {
        $this->roleService->createRole($request->all());
        return back()->with('success', 'Role created successfully');
    }

    public function update(Request $request, int $id)
    {
        $this->roleService->updateRole($id, $request->all());
        return back()->with('success', 'Role updated successfully');
    }

    public function destroy(int $id)
    {
        $this->roleService->deleteRole($id);
        return back()->with('success', 'Role deleted successfully');
    }
}
