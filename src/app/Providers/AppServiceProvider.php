<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(\App\Services\AuthService::class);
        $this->app->singleton(\App\Services\DashboardService::class);
        $this->app->singleton(\App\Services\EmployeeService::class);
        $this->app->singleton(\App\Services\RoleService::class);
        $this->app->singleton(\App\Services\PositionService::class);
        $this->app->singleton(\App\Services\ItemService::class);
        $this->app->singleton(\App\Services\EvaluationService::class);
        $this->app->singleton(\App\Services\ReportService::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
