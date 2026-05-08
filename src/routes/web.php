<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\EvaluationController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\ItemController;

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Rutas solo para administrador
    Route::middleware(['role:administrator'])->group(function () {
        // Gestión de empleados
        Route::get('/manage-employees', [EmployeeController::class, 'index'])->name('manage-employees');
        Route::post('/employees', [EmployeeController::class, 'store'])->name('employees.store');
        Route::put('/employees/{id}', [EmployeeController::class, 'update'])->name('employees.update');
        Route::delete('/employees/{id}', [EmployeeController::class, 'destroy'])->name('employees.destroy');

        // Roles y posiciones
        Route::post('/roles', [EmployeeController::class, 'storeRole'])->name('roles.store');
        Route::put('/roles/{id}', [EmployeeController::class, 'updateRole'])->name('roles.update');
        Route::delete('/roles/{id}', [EmployeeController::class, 'destroyRole'])->name('roles.destroy');

        Route::post('/positions', [EmployeeController::class, 'storePosition'])->name('positions.store');
        Route::put('/positions/{id}', [EmployeeController::class, 'updatePosition'])->name('positions.update');
        Route::delete('/positions/{id}', [EmployeeController::class, 'destroyPosition'])->name('positions.destroy');

        // Gestión de items
        Route::get('/manage-items', [ItemController::class, 'index'])->name('manage-items');
        Route::post('/competencies', [ItemController::class, 'storeCompetency'])->name('competencies.store');
        Route::put('/competencies/{id}', [ItemController::class, 'updateCompetency'])->name('competencies.update');
        Route::delete('/competencies/{id}', [ItemController::class, 'destroyCompetency'])->name('competencies.destroy');
        Route::post('/indicators', [ItemController::class, 'storeIndicator'])->name('indicators.store');
        Route::put('/indicators/{id}', [ItemController::class, 'updateIndicator'])->name('indicators.update');
        Route::delete('/indicators/{id}', [ItemController::class, 'destroyIndicator'])->name('indicators.destroy');

        // Asignaciones
        Route::post('/role-competency/attach', [ItemController::class, 'attachRoleCompetency'])->name('role-competency.attach');
        Route::post('/role-competency/detach', [ItemController::class, 'detachRoleCompetency'])->name('role-competency.detach');
        Route::post('/position-indicator/attach', [ItemController::class, 'attachPositionIndicator'])->name('position-indicator.attach');
        Route::post('/position-indicator/detach', [ItemController::class, 'detachPositionIndicator'])->name('position-indicator.detach');

        // Reportes grupales (solo admin)
        Route::get('/reports/group', [ReportController::class, 'group'])->name('reports.group');
    });

    // Rutas compartidas para administrator y evaluator
    Route::middleware(['role:administrator,evaluator'])->group(function () {
        Route::get('/view-evaluations', [EvaluationController::class, 'index'])->name('view-evaluations');
        Route::get('/reports', [ReportController::class, 'index'])->name('reports');
        Route::get('/reports/individual', [ReportController::class, 'individual'])->name('reports.individual');
        Route::get('/reports/trends', [ReportController::class, 'trends'])->name('reports.trends');
    });

    // Rutas solo para evaluator
    Route::middleware(['role:evaluator'])->group(function () {
        Route::get('/create-evaluation', [EvaluationController::class, 'create'])->name('create-evaluation');
        Route::post('/evaluations', [EvaluationController::class, 'store'])->name('evaluations.store');
    });

    // Rutas para empleado y evaluator (ver sus propias evaluaciones)
    Route::middleware(['role:employee,evaluator'])->group(function () {
        Route::get('/my-evaluations', [EvaluationController::class, 'myEvaluations'])->name('my-evaluations');
    });

    // Rutas de evaluaciones accesibles para todos los roles autenticados
    Route::get('/evaluations/{id}', [EvaluationController::class, 'show'])->name('evaluations.show');
    Route::put('/evaluations/{id}', [EvaluationController::class, 'update'])->name('evaluations.update');
});

Route::get('/', function () {
    return redirect('/login');
});