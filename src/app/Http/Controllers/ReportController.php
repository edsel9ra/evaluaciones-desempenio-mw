<?php

namespace App\Http\Controllers;

use App\Services\ReportService;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ReportController extends Controller
{
    protected ReportService $reportService;

    public function __construct(ReportService $reportService)
    {
        $this->reportService = $reportService;
    }

    public function index(Request $request)
    {
        $employees = $this->reportService->getAllEmployeesForReport();
        $employeeId = $request->employee_id;

        $report = null;
        if ($employeeId) {
            $report = $this->reportService->generateIndividualReport($employeeId);
        }

        return Inertia::render('Reports', [
            'employees' => $employees,
            'individualReport' => $report,
        ]);
    }

    public function individual(Request $request)
    {
        $employees = $this->reportService->getAllEmployeesForReport();
        $employeeId = $request->employee_id;
        $report = $employeeId ? $this->reportService->generateIndividualReport($employeeId) : null;
        return Inertia::render('Reports', [
            'employees' => $employees,
            'individualReport' => $report,
        ]);
    }

    public function group(Request $request)
    {
        $report = $this->reportService->generateGroupReport($request->all());
        return Inertia::render('Reports', ['groupReport' => $report]);
    }

    public function trends()
    {
        $report = $this->reportService->generateTrendsReport();
        return Inertia::render('Reports', ['trendsReport' => $report]);
    }
}
