<template>
  <div class="space-y-6 animate-fade-in">
    <!-- Administrator Dashboard -->
    <div v-if="data.dashboardType === 'administrator'">
      <div class="flex items-center justify-between mb-6">
        <div>
          <h1 class="page-title">Bienvenido, {{ data.user?.name }}</h1>
          <p class="page-subtitle">Panel de administración del sistema</p>
        </div>
        <div class="badge badge-primary text-sm px-3 py-1.5">
          Administrador
        </div>
      </div>

      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-5">
        <div class="stat-card card-hover group stagger-1"
          style="animation: slideUp 0.4s ease-out forwards; opacity: 0;">
          <div class="flex items-start justify-between">
            <div>
              <p class="text-surface-500 text-sm font-medium mb-1">Total Usuarios</p>
              <p class="text-3xl font-bold text-surface-900">{{ data.totalUsers }}</p>
            </div>
            <div
              class="w-12 h-12 rounded-xl bg-primary-50 flex items-center justify-center group-hover:scale-110 transition-transform">
              <svg class="w-6 h-6 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z">
                </path>
              </svg>
            </div>
          </div>
          <div class="mt-4 flex items-center gap-1 text-emerald-600 text-sm">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
            </svg>
            Sistema activo
          </div>
        </div>

        <div class="stat-card card-hover group stagger-2"
          style="animation: slideUp 0.4s ease-out forwards; opacity: 0;">
          <div class="flex items-start justify-between">
            <div>
              <p class="text-surface-500 text-sm font-medium mb-1">Empleados</p>
              <p class="text-3xl font-bold text-surface-900">{{ data.totalEmployees }}</p>
            </div>
            <div
              class="w-12 h-12 rounded-xl bg-emerald-50 flex items-center justify-center group-hover:scale-110 transition-transform">
              <svg class="w-6 h-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z">
                </path>
              </svg>
            </div>
          </div>
          <div class="mt-4 text-surface-500 text-sm">En la organización</div>
        </div>

        <div class="stat-card card-hover group stagger-3"
          style="animation: slideUp 0.4s ease-out forwards; opacity: 0;">
          <div class="flex items-start justify-between">
            <div>
              <p class="text-surface-500 text-sm font-medium mb-1">Evaluaciones</p>
              <p class="text-3xl font-bold text-surface-900">{{ data.totalEvaluations }}</p>
            </div>
            <div
              class="w-12 h-12 rounded-xl bg-amber-50 flex items-center justify-center group-hover:scale-110 transition-transform">
              <svg class="w-6 h-6 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                </path>
              </svg>
            </div>
          </div>
          <div class="mt-4 text-surface-500 text-sm">Registradas</div>
        </div>

        <div class="stat-card card-hover group stagger-4"
          style="animation: slideUp 0.4s ease-out forwards; opacity: 0;">
          <div class="flex items-start justify-between">
            <div>
              <p class="text-surface-500 text-sm font-medium mb-1">Roles & Posiciones</p>
              <p class="text-3xl font-bold text-surface-900">{{ data.totalRoles + data.totalPositions }}</p>
            </div>
            <div
              class="w-12 h-12 rounded-xl bg-violet-50 flex items-center justify-center group-hover:scale-110 transition-transform">
              <svg class="w-6 h-6 text-violet-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4">
                </path>
              </svg>
            </div>
          </div>
          <div class="mt-4 text-surface-500 text-sm">Definidos</div>
        </div>
      </div>

      <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mt-6">
        <div class="lg:col-span-2">
          <div class="card">
            <div class="card-header flex justify-between items-center">
              <h3 class="font-semibold text-surface-900">Acciones Rápidas</h3>
            </div>
            <div class="card-body">
              <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                <Link href="/manage-employees"
                  class="group p-4 rounded-xl border border-surface-200 hover:border-primary-300 hover:bg-primary-50/50 transition-all duration-200 text-center">
                  <div
                    class="w-12 h-12 mx-auto mb-3 rounded-xl bg-primary-100 flex items-center justify-center group-hover:scale-110 group-hover:bg-primary-200 transition-all">
                    <svg class="w-6 h-6 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z">
                      </path>
                    </svg>
                  </div>
                  <span class="text-sm font-medium text-surface-700 group-hover:text-primary-700">Empleados</span>
                </Link>
                <Link href="/manage-items"
                  class="group p-4 rounded-xl border border-surface-200 hover:border-emerald-300 hover:bg-emerald-50/50 transition-all duration-200 text-center">
                  <div
                    class="w-12 h-12 mx-auto mb-3 rounded-xl bg-emerald-100 flex items-center justify-center group-hover:scale-110 group-hover:bg-emerald-200 transition-all">
                    <svg class="w-6 h-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01">
                      </path>
                    </svg>
                  </div>
                  <span class="text-sm font-medium text-surface-700 group-hover:text-emerald-700">Items</span>
                </Link>
                <Link href="/reports"
                  class="group p-4 rounded-xl border border-surface-200 hover:border-violet-300 hover:bg-violet-50/50 transition-all duration-200 text-center">
                  <div
                    class="w-12 h-12 mx-auto mb-3 rounded-xl bg-violet-100 flex items-center justify-center group-hover:scale-110 group-hover:bg-violet-200 transition-all">
                    <svg class="w-6 h-6 text-violet-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z">
                      </path>
                    </svg>
                  </div>
                  <span class="text-sm font-medium text-surface-700 group-hover:text-violet-700">Reportes</span>
                </Link>
              </div>
            </div>
          </div>
        </div>

        <div>
          <div class="card">
            <div class="card-header">
              <h3 class="font-semibold text-surface-900">Actividad Reciente</h3>
            </div>
            <div class="card-body space-y-4">
              <div class="flex items-start gap-3">
                <div class="w-8 h-8 rounded-lg bg-primary-100 flex items-center justify-center flex-shrink-0">
                  <svg class="w-4 h-4 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                  </svg>
                </div>
                <div>
                  <p class="text-sm font-medium text-surface-900">Sistema inicializado</p>
                  <p class="text-xs text-surface-400">Ahora</p>
                </div>
              </div>
              <div class="flex items-start gap-3">
                <div class="w-8 h-8 rounded-lg bg-emerald-100 flex items-center justify-center flex-shrink-0">
                  <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                  </svg>
                </div>
                <div>
                  <p class="text-sm font-medium text-surface-900">{{ data.totalEvaluations }} evaluaciones registradas
                  </p>
                  <p class="text-xs text-surface-400">Total en sistema</p>
                </div>
              </div>
              <div class="flex items-start gap-3">
                <div class="w-8 h-8 rounded-lg bg-violet-100 flex items-center justify-center flex-shrink-0">
                  <svg class="w-4 h-4 text-violet-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z">
                    </path>
                  </svg>
                </div>
                <div>
                  <p class="text-sm font-medium text-surface-900">Acceso de administrador</p>
                  <p class="text-xs text-surface-400">Permisos completos</p>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Evaluator Dashboard -->
    <div v-else-if="data.dashboardType === 'evaluator'">
      <div class="flex items-center justify-between mb-6">
        <div>
          <h1 class="page-title">Bienvenido, {{ data.user?.name }}</h1>
          <p class="page-subtitle">Panel de evaluador</p>
        </div>
        <div class="badge badge-info text-sm px-3 py-1.5">
          Evaluador
        </div>
      </div>

      <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
        <div class="stat-card card-hover stagger-1" style="animation: slideUp 0.4s ease-out forwards; opacity: 0;">
          <div class="flex items-start justify-between">
            <div>
              <p class="text-surface-500 text-sm font-medium mb-1">Empleados Asignados</p>
              <p class="text-3xl font-bold text-surface-900">{{ data.assignedEmployeesCount }}</p>
            </div>
            <div class="w-12 h-12 rounded-xl bg-primary-50 flex items-center justify-center">
              <svg class="w-6 h-6 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z">
                </path>
              </svg>
            </div>
          </div>
        </div>

        <div class="stat-card card-hover stagger-2" style="animation: slideUp 0.4s ease-out forwards; opacity: 0;">
          <div class="flex items-start justify-between">
            <div>
              <p class="text-surface-500 text-sm font-medium mb-1">Total Evaluaciones</p>
              <p class="text-3xl font-bold text-surface-900">{{ data.totalEvaluationsCount }}</p>
            </div>
            <div class="w-12 h-12 rounded-xl bg-amber-50 flex items-center justify-center">
              <svg class="w-6 h-6 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                </path>
              </svg>
            </div>
          </div>
        </div>

        <div class="stat-card card-hover stagger-3" style="animation: slideUp 0.4s ease-out forwards; opacity: 0;">
          <div class="flex items-start justify-between">
            <div>
              <p class="text-surface-500 text-sm font-medium mb-1">Completadas</p>
              <p class="text-3xl font-bold text-emerald-600">{{ data.completedEvaluations }}</p>
            </div>
            <div class="w-12 h-12 rounded-xl bg-emerald-50 flex items-center justify-center">
              <svg class="w-6 h-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
              </svg>
            </div>
          </div>
        </div>
      </div>

      <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
        <div class="card">
          <div class="card-header">
            <h3 class="font-semibold text-surface-900">Acciones Rápidas</h3>
          </div>
          <div class="card-body space-y-3">
            <Link href="/create-evaluation" class="btn btn-primary w-full justify-start">
              <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6">
                </path>
              </svg>
              Crear Nueva Evaluación
            </Link>
            <Link href="/view-evaluations" class="btn btn-secondary w-full justify-start">
              <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                </path>
              </svg>
              Ver Evaluaciones
            </Link>
            <Link href="/reports" class="btn btn-secondary w-full justify-start">
              <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z">
                </path>
              </svg>
              Ver Reportes
            </Link>
          </div>
        </div>

        <div class="card">
          <div class="card-header">
            <h3 class="font-semibold text-surface-900">Empleados Asignados</h3>
          </div>
          <div class="card-body">
            <div v-if="data.myEmployees && data.myEmployees.length" class="space-y-3">
              <div v-for="emp in data.myEmployees" :key="emp.id"
                class="flex items-center gap-3 p-3 rounded-lg bg-surface-50 hover:bg-surface-100 transition-colors">
                <div
                  class="w-10 h-10 rounded-full bg-gradient-to-br from-violet-500 to-violet-600 flex items-center justify-center text-white text-sm font-semibold">
                  {{ getInitials(emp.name) }}
                </div>
                <div class="flex-1 min-w-0">
                  <p class="text-sm font-medium text-surface-900 truncate">{{ emp.name }}</p>
                  <p class="text-xs text-surface-500 truncate">{{ emp.role?.name }}</p>
                </div>
                <span class="badge badge-neutral text-xs">{{ emp.position?.name || 'N/A' }}</span>
              </div>
            </div>
            <div v-else class="empty-state py-8">
              <svg class="empty-state-icon w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                  d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z">
                </path>
              </svg>
              <p class="empty-state-title">Sin empleados</p>
              <p class="empty-state-description">No tienes empleados asignados todavía.</p>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Employee Dashboard -->
    <!-- Employee Dashboard -->
    <div v-else-if="data.dashboardType === 'employee'">
      <div class="flex items-center justify-between mb-6">
        <div>
          <h1 class="page-title">Bienvenido, {{ data.user?.name }}</h1>
          <p class="page-subtitle">Tu panel de evaluaciones</p>
        </div>

        <div class="badge badge-success text-sm px-3 py-1.5">
          Empleado
        </div>
      </div>

      <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
        <div class="stat-card card-hover stagger-1" style="animation: slideUp 0.4s ease-out forwards; opacity: 0;">
          <div class="flex items-start justify-between">
            <div>
              <p class="text-surface-500 text-sm font-medium mb-1">Total Evaluaciones</p>
              <p class="text-3xl font-bold text-surface-900">
                {{ data.myEvaluations?.length || 0 }}
              </p>
            </div>

            <div class="w-12 h-12 rounded-xl bg-primary-50 flex items-center justify-center">
              <svg class="w-6 h-6 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
              </svg>
            </div>
          </div>
        </div>

        <div class="stat-card card-hover stagger-2" style="animation: slideUp 0.4s ease-out forwards; opacity: 0;">
          <div class="flex items-start justify-between">
            <div>
              <p class="text-surface-500 text-sm font-medium mb-1">Completadas</p>
              <p class="text-3xl font-bold text-emerald-600">
                {{data.myEvaluations?.filter(e => e.status === 'completed').length || 0}}
              </p>
            </div>

            <div class="w-12 h-12 rounded-xl bg-emerald-50 flex items-center justify-center">
              <svg class="w-6 h-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
              </svg>
            </div>
          </div>
        </div>

        <div class="stat-card card-hover stagger-3" style="animation: slideUp 0.4s ease-out forwards; opacity: 0;">
          <div class="flex items-start justify-between">
            <div>
              <p class="text-surface-500 text-sm font-medium mb-1">Pendientes</p>
              <p class="text-3xl font-bold text-amber-600">
                {{data.myEvaluations?.filter(e => e.status !== 'completed').length || 0}}
              </p>
            </div>

            <div class="w-12 h-12 rounded-xl bg-amber-50 flex items-center justify-center">
              <svg class="w-6 h-6 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
              </svg>
            </div>
          </div>
        </div>
      </div>

      <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mt-6">
        <div class="lg:col-span-2">
          <div class="card">
            <div class="card-header flex justify-between items-center">
              <h3 class="font-semibold text-surface-900">
                Historial de Evaluaciones
              </h3>

              <Link href="/my-evaluations" class="text-sm text-primary-600 hover:text-primary-700 font-medium">
                Ver todas →
              </Link>
            </div>

            <div class="card-body">
              <div v-if="data.myEvaluations && data.myEvaluations.length">
                <div v-for="evaluation in data.myEvaluations.slice(0, 3)" :key="evaluation.id"
                  class="p-4 rounded-xl border border-surface-200 hover:border-surface-300 transition-all mb-3 last:mb-0">
                  <div class="flex items-center justify-between">
                    <div class="flex items-center gap-4">
                      <div class="w-12 h-12 rounded-xl bg-surface-100 flex items-center justify-center">
                        <svg class="w-6 h-6 text-surface-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                      </div>

                      <div>
                        <p class="font-semibold text-surface-900">
                          Período: {{ evaluation.period }}
                        </p>

                        <p class="text-sm text-surface-500">
                          {{ formatDate(evaluation.created_at) }}
                        </p>
                      </div>
                    </div>

                    <div class="text-right">
                      <span :class="getStatusClass(evaluation.status)" class="badge">
                        {{ formatStatus(evaluation.status) }}
                      </span>

                      <div v-if="evaluation.status === 'completed'" class="mt-2">
                        <span class="text-lg font-bold text-emerald-600">
                          {{ (evaluation.total_score || 0).toFixed(2) }}
                        </span>
                        <span class="text-sm text-surface-400">/5</span>
                      </div>
                    </div>
                  </div>
                </div>
              </div>

              <div v-else class="empty-state py-8">
                <svg class="empty-state-icon w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>

                <p class="empty-state-title">Sin evaluaciones</p>
                <p class="empty-state-description">
                  Tu evaluador te asignará evaluaciones.
                </p>
              </div>
            </div>
          </div>
        </div>

        <div class="space-y-6">
          <div class="card">
            <div class="card-header">
              <h3 class="font-semibold text-surface-900">
                Directrices
              </h3>
            </div>

            <div class="card-body">
              <ul class="space-y-3">
                <li class="flex items-center gap-3 text-sm text-surface-600">
                  <div class="w-6 h-6 rounded-full bg-emerald-100 flex items-center justify-center flex-shrink-0">
                    <svg class="w-3.5 h-3.5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" />
                    </svg>
                  </div>
                  Evaluaciones trimestrales
                </li>

                <li class="flex items-center gap-3 text-sm text-surface-600">
                  <div class="w-6 h-6 rounded-full bg-emerald-100 flex items-center justify-center flex-shrink-0">
                    <svg class="w-3.5 h-3.5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" />
                    </svg>
                  </div>
                  Retroalimentación detallada
                </li>

                <li class="flex items-center gap-3 text-sm text-surface-600">
                  <div class="w-6 h-6 rounded-full bg-emerald-100 flex items-center justify-center flex-shrink-0">
                    <svg class="w-3.5 h-3.5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" />
                    </svg>
                  </div>
                  Enfocado en crecimiento
                </li>

                <li class="flex items-center gap-3 text-sm text-surface-600">
                  <div class="w-6 h-6 rounded-full bg-emerald-100 flex items-center justify-center flex-shrink-0">
                    <svg class="w-3.5 h-3.5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" />
                    </svg>
                  </div>
                  Resultados confidenciales
                </li>
              </ul>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { Link } from '@inertiajs/vue3';
import AppLayout from '../Layouts/AppLayout.vue';

defineOptions({
  layout: AppLayout,
});

defineProps({
  data: {
    type: Object,
    required: true,
  },
});

const formatDate = (date) => {
  if (!date) return 'N/A';
  const d = new Date(date);
  const day = String(d.getDate()).padStart(2, '0');
  const month = String(d.getMonth() + 1).padStart(2, '0');
  const year = d.getFullYear();
  return `${day}/${month}/${year}`;
};

const getStatusClass = (status) => {
  const classes = {
    'pending': 'badge-warning',
    'in_progress': 'badge-info',
    'completed': 'badge-success',
  };
  return classes[status] || 'badge-neutral';
};

const formatStatus = (status) => {
  const labels = {
    'pending': 'Pendiente',
    'in_progress': 'En Progreso',
    'completed': 'Completada',
  };
  return labels[status] || status;
};

const getInitials = (name) => {
  return name.split(' ').map(n => n[0]).slice(0, 2).join('').toUpperCase();
};
</script>