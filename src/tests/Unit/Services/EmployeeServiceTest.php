<?php

namespace Tests\Unit\Services;

use Tests\TestCase;
use App\Models\Employee;
use App\Models\User;
use App\Models\Role;
use App\Models\Position;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;

class EmployeeServiceTest extends TestCase
{
    use RefreshDatabase;

    protected \App\Services\EmployeeService $employeeService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->employeeService = new \App\Services\EmployeeService();
    }

    public function test_create_employee_creates_user_and_employee(): void
    {
        $role = Role::factory()->create();
        
        $data = [
            'name' => 'John Doe',
            'username' => 'johndoe',
            'email' => 'john@example.com',
            'password' => 'password123',
            'user_role' => 'employee',
            'role_id' => $role->id,
            'position_id' => null,
            'evaluator_id' => null,
            'department' => 'IT'
        ];

        $employee = $this->service->createEmployee($data);

        $this->assertDatabaseHas('users', ['username' => 'johndoe']);
        $this->assertDatabaseHas('employees', ['name' => 'John Doe']);
        $this->assertEquals('employee', $employee->user->role);
    }

    public function test_update_employee_updates_user_and_employee(): void
    {
        $employee = Employee::factory()->create();
        $user = $employee->user;
        
        $newRole = Role::factory()->create();

        $data = [
            'name' => 'Updated Name',
            'email' => 'updated@example.com',
            'user_role' => 'evaluator',
            'role_id' => $newRole->id,
            'department' => 'HR'
        ];

        $updated = $this->service->updateEmployee($employee->id, $data);

        $this->assertEquals('Updated Name', $updated->name);
        $this->assertEquals('HR', $updated->department);
        $this->assertEquals('updated@example.com', $user->fresh()->email);
    }

    public function test_update_employee_updates_password_when_provided(): void
    {
        $employee = Employee::factory()->create();
        
        $data = [
            'name' => 'Test Name',
            'email' => 'test@example.com',
            'user_role' => 'employee',
            'password' => 'newpassword',
            'department' => 'IT'
        ];

        $this->service->updateEmployee($employee->id, $data);

        $user = $employee->user->fresh();
        $this->assertTrue(Hash::check('newpassword', $user->password));
    }

    public function test_update_employee_does_not_update_password_when_empty(): void
    {
        $employee = Employee::factory()->create();
        $oldPassword = $employee->user->password;
        
        $data = [
            'name' => 'Test Name',
            'email' => 'test@example.com',
            'user_role' => 'employee',
            'password' => '',
            'department' => 'IT'
        ];

        $this->service->updateEmployee($employee->id, $data);

        $this->assertEquals($oldPassword, $employee->user->fresh()->password);
    }

    public function test_delete_employee_deletes_user_and_employee(): void
    {
        $employee = Employee::factory()->create();
        $userId = $employee->user_id;
        $employeeId = $employee->id;

        $this->service->deleteEmployee($employeeId);

        $this->assertNull(Employee::find($employeeId));
        $this->assertNull(User::find($userId));
    }

    public function test_get_all_employees_searches_by_name(): void
    {
        Employee::factory()->create(['name' => 'John Doe']);
        Employee::factory()->create(['name' => 'Jane Smith']);

        $request = new \Illuminate\Http\Request(['search' => 'John']);
        $result = $this->service->getAllEmployees($request);

        $this->assertCount(1, $result);
        $this->assertEquals('John Doe', $result->first()->name);
    }

    public function test_get_all_employees_returns_all_when_no_search(): void
    {
        Employee::factory()->count(5)->create();

        $result = $this->service->getAllEmployees();

        $this->assertCount(5, $result);
    }

    public function test_get_evaluators_returns_evaluators_and_admins(): void
    {
        User::factory()->create(['role' => 'employee']);
        User::factory()->create(['role' => 'evaluator']);
        User::factory()->create(['role' => 'administrator']);

        $result = $this->service->getEvaluators();

        $this->assertCount(2, $result);
    }

    public function test_get_all_roles_includes_competencies(): void
    {
        $role = Role::factory()->create();

        $result = $this->service->getAllRoles();

        $this->assertTrue($result->first()->relationLoaded('competencies'));
    }

    public function test_get_all_positions_includes_indicators(): void
    {
        $position = Position::factory()->create();

        $result = $this->service->getAllPositions();

        $this->assertTrue($result->first()->relationLoaded('indicators'));
    }
}