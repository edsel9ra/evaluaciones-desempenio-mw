<template>
    <div class="space-y-6 animate-fade-in">
        <!-- Employee Modal -->
        <div
            v-if="showEmployeeModal"
            class="fixed inset-0 z-[9999] bg-surface-900/45 backdrop-blur-sm lg:left-64 lg:top-16"
            @click.self="closeEmployeeModal"
        >
            <div class="flex h-full w-full items-center justify-center p-4">
                <div
                    class="flex max-h-[calc(100vh-7rem)] w-full max-w-5xl flex-col overflow-hidden rounded-3xl border border-surface-200 bg-white shadow-2xl"
                >
                    <!-- Header -->
                    <div class="flex flex-shrink-0 items-start justify-between gap-4 border-b border-surface-100 bg-white px-6 py-5">
                        <div>
                            <div class="mb-2 inline-flex rounded-full bg-primary-50 px-3 py-1 text-xs font-bold uppercase tracking-wide text-primary-700">
                                {{ editingEmployee ? 'Edición de empleado' : 'Nuevo empleado' }}
                            </div>

                            <h3 class="text-xl font-black text-surface-900">
                                {{ editingEmployee ? 'Editar Empleado' : 'Agregar Empleado' }}
                            </h3>

                            <p class="mt-1 text-sm text-surface-500">
                                Completa los datos personales, laborales y permisos de acceso.
                            </p>
                        </div>

                        <button
                            type="button"
                            @click="closeEmployeeModal"
                            class="flex h-10 w-10 flex-shrink-0 items-center justify-center rounded-2xl border border-surface-200 bg-white text-surface-500 shadow-sm transition hover:border-rose-200 hover:bg-rose-50 hover:text-rose-600"
                        >
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12"
                                />
                            </svg>
                        </button>
                    </div>

                    <!-- Form -->
                    <form
                        @submit.prevent="saveEmployee"
                        class="flex min-h-0 flex-1 flex-col"
                    >
                        <!-- Body scroll -->
                        <div class="flex-1 overflow-y-auto px-6 py-6">
                            <div class="grid grid-cols-1 gap-6 xl:grid-cols-2">
                                <!-- Información personal -->
                                <section class="rounded-3xl border border-surface-200 bg-surface-50 p-5">
                                    <div class="mb-5 flex items-center gap-3">
                                        <div class="flex h-11 w-11 items-center justify-center rounded-2xl bg-primary-50 text-primary-600">
                                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path
                                                    stroke-linecap="round"
                                                    stroke-linejoin="round"
                                                    stroke-width="2"
                                                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"
                                                />
                                            </svg>
                                        </div>

                                        <div>
                                            <h4 class="text-sm font-bold text-surface-900">
                                                Información Personal
                                            </h4>
                                            <p class="text-xs text-surface-500">
                                                Datos de identificación y acceso.
                                            </p>
                                        </div>
                                    </div>

                                    <div class="space-y-4">
                                        <div>
                                            <label class="label">
                                                Nombre Completo
                                            </label>

                                            <input
                                                v-model="employeeForm.name"
                                                class="input"
                                                placeholder="Nombre completo del empleado"
                                                required
                                            />
                                        </div>

                                        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                                            <div>
                                                <label class="label">
                                                    Usuario
                                                </label>

                                                <input
                                                    v-model="employeeForm.username"
                                                    class="input"
                                                    placeholder="Usuario"
                                                    required
                                                    :disabled="editingEmployee"
                                                />
                                            </div>

                                            <div>
                                                <label class="label">
                                                    Email
                                                </label>

                                                <input
                                                    v-model="employeeForm.email"
                                                    type="email"
                                                    class="input"
                                                    placeholder="correo@ejemplo.com"
                                                    required
                                                />
                                            </div>
                                        </div>

                                        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                                            <div>
                                                <label class="label">
                                                    Contraseña
                                                </label>

                                                <input
                                                    v-model="employeeForm.password"
                                                    type="password"
                                                    class="input"
                                                    :placeholder="editingEmployee ? 'Sin cambios' : 'Contraseña'"
                                                    :required="!editingEmployee"
                                                />
                                            </div>

                                            <div>
                                                <label class="label">
                                                    Fecha de Contratación
                                                </label>

                                                <input
                                                    v-model="employeeForm.hire_date"
                                                    type="date"
                                                    class="input"
                                                />
                                            </div>
                                        </div>
                                    </div>
                                </section>

                                <!-- Información laboral -->
                                <section class="rounded-3xl border border-surface-200 bg-surface-50 p-5">
                                    <div class="mb-5 flex items-center gap-3">
                                        <div class="flex h-11 w-11 items-center justify-center rounded-2xl bg-emerald-50 text-emerald-600">
                                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path
                                                    stroke-linecap="round"
                                                    stroke-linejoin="round"
                                                    stroke-width="2"
                                                    d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"
                                                />
                                            </svg>
                                        </div>

                                        <div>
                                            <h4 class="text-sm font-bold text-surface-900">
                                                Información Laboral
                                            </h4>
                                            <p class="text-xs text-surface-500">
                                                Área, rol, posición y evaluador.
                                            </p>
                                        </div>
                                    </div>

                                    <div class="space-y-4">
                                        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                                            <div>
                                                <label class="label">
                                                    Departamento
                                                </label>

                                                <select
                                                    v-model="employeeForm.department"
                                                    class="select"
                                                    required
                                                >
                                                    <option value="">Seleccionar</option>
                                                    <option
                                                        v-for="dept in departments"
                                                        :key="dept"
                                                        :value="dept"
                                                    >
                                                        {{ dept }}
                                                    </option>
                                                </select>
                                            </div>

                                            <div>
                                                <label class="label">
                                                    Rol
                                                </label>

                                                <select
                                                    v-model="employeeForm.role_id"
                                                    class="select"
                                                    required
                                                >
                                                    <option value="">Seleccionar</option>
                                                    <option
                                                        v-for="role in roles"
                                                        :key="role.id"
                                                        :value="role.id"
                                                    >
                                                        {{ role.name }}
                                                    </option>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                                            <div>
                                                <label class="label">
                                                    Posición
                                                </label>

                                                <select
                                                    v-model="employeeForm.position_id"
                                                    class="select"
                                                >
                                                    <option value="">Seleccionar</option>
                                                    <option
                                                        v-for="pos in positions"
                                                        :key="pos.id"
                                                        :value="pos.id"
                                                    >
                                                        {{ pos.name }}
                                                    </option>
                                                </select>
                                            </div>

                                            <div>
                                                <label class="label">
                                                    Evaluador
                                                </label>

                                                <select
                                                    v-model="employeeForm.evaluator_id"
                                                    class="select"
                                                >
                                                    <option value="">Seleccionar</option>
                                                    <option
                                                        v-for="user in evaluators"
                                                        :key="user.id"
                                                        :value="user.id"
                                                    >
                                                        {{ user.name }}
                                                    </option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Rol de acceso -->
                                    <div class="mt-6">
                                        <div class="mb-4 flex items-center gap-2">
                                            <div class="flex h-8 w-8 items-center justify-center rounded-xl bg-violet-50 text-violet-600">
                                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path
                                                        stroke-linecap="round"
                                                        stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"
                                                    />
                                                </svg>
                                            </div>

                                            <h4 class="text-sm font-bold text-surface-900">
                                                Rol de Acceso al Sistema
                                            </h4>
                                        </div>

                                        <div class="grid grid-cols-1 gap-3 sm:grid-cols-3">
                                            <label
                                                class="group cursor-pointer rounded-2xl border bg-white p-4 text-center shadow-sm transition hover:border-primary-200 hover:bg-primary-50"
                                                :class="employeeForm.user_role === 'employee'
                                                    ? 'border-primary-500 bg-primary-50 ring-2 ring-primary-100'
                                                    : 'border-surface-200'"
                                            >
                                                <input
                                                    v-model="employeeForm.user_role"
                                                    type="radio"
                                                    value="employee"
                                                    class="sr-only"
                                                />

                                                <div class="mx-auto mb-3 flex h-11 w-11 items-center justify-center rounded-2xl bg-primary-100 text-primary-600">
                                                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path
                                                            stroke-linecap="round"
                                                            stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"
                                                        />
                                                    </svg>
                                                </div>

                                                <span class="block text-sm font-bold text-surface-900">
                                                    Empleado
                                                </span>

                                                <span class="mt-1 block text-xs text-surface-500">
                                                    Acceso básico
                                                </span>
                                            </label>

                                            <label
                                                class="group cursor-pointer rounded-2xl border bg-white p-4 text-center shadow-sm transition hover:border-emerald-200 hover:bg-emerald-50"
                                                :class="employeeForm.user_role === 'evaluator'
                                                    ? 'border-emerald-500 bg-emerald-50 ring-2 ring-emerald-100'
                                                    : 'border-surface-200'"
                                            >
                                                <input
                                                    v-model="employeeForm.user_role"
                                                    type="radio"
                                                    value="evaluator"
                                                    class="sr-only"
                                                />

                                                <div class="mx-auto mb-3 flex h-11 w-11 items-center justify-center rounded-2xl bg-emerald-100 text-emerald-600">
                                                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path
                                                            stroke-linecap="round"
                                                            stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"
                                                        />
                                                    </svg>
                                                </div>

                                                <span class="block text-sm font-bold text-surface-900">
                                                    Evaluador
                                                </span>

                                                <span class="mt-1 block text-xs text-surface-500">
                                                    Gestiona eval.
                                                </span>
                                            </label>

                                            <label
                                                class="group cursor-pointer rounded-2xl border bg-white p-4 text-center shadow-sm transition hover:border-violet-200 hover:bg-violet-50"
                                                :class="employeeForm.user_role === 'administrator'
                                                    ? 'border-violet-500 bg-violet-50 ring-2 ring-violet-100'
                                                    : 'border-surface-200'"
                                            >
                                                <input
                                                    v-model="employeeForm.user_role"
                                                    type="radio"
                                                    value="administrator"
                                                    class="sr-only"
                                                />

                                                <div class="mx-auto mb-3 flex h-11 w-11 items-center justify-center rounded-2xl bg-violet-100 text-violet-600">
                                                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path
                                                            stroke-linecap="round"
                                                            stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"
                                                        />
                                                        <path
                                                            stroke-linecap="round"
                                                            stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"
                                                        />
                                                    </svg>
                                                </div>

                                                <span class="block text-sm font-bold text-surface-900">
                                                    Admin
                                                </span>

                                                <span class="mt-1 block text-xs text-surface-500">
                                                    Acceso total
                                                </span>
                                            </label>
                                        </div>
                                    </div>
                                </section>
                            </div>
                        </div>

                        <!-- Footer actions -->
                        <div class="flex flex-shrink-0 flex-col-reverse gap-3 border-t border-surface-100 bg-white px-6 py-4 sm:flex-row sm:items-center sm:justify-end">
                            <button
                                type="button"
                                @click="closeEmployeeModal"
                                class="btn btn-secondary"
                            >
                                Cancelar
                            </button>

                            <button
                                type="submit"
                                class="btn btn-primary"
                            >
                                {{ editingEmployee ? 'Actualizar Empleado' : 'Crear Empleado' }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="page-title">Gestión de Empleados</h1>
                <p class="page-subtitle">Administra empleados, roles y posiciones</p>
            </div>
        </div>

        <div class="border-b border-surface-200 overflow-x-auto">
            <div class="flex gap-1 min-w-max">
                <button v-for="tab in tabs" :key="tab.key" @click="activeTab = tab.key"
                    class="tab-button whitespace-nowrap" :class="{ 'tab-button-active': activeTab === tab.key }">
                    {{ tab.label }}
                </button>
            </div>
        </div>

        <!-- Employees Tab -->
        <div v-if="activeTab === 'employees'">
            <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4 mb-4">
                <h2 class="text-lg font-semibold text-surface-900">Empleados</h2>
                <button @click="openEmployeeModal()" class="btn btn-primary">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    <span class="hidden sm:inline">Agregar Empleado</span>
                    <span class="sm:hidden">Agregar</span>
                </button>
            </div>

            <div class="card overflow-hidden">
                <div class="table-container">
                    <table class="table">
                        <thead>
                            <tr class="hidden lg:table-row">
                                <th class="text-left py-3 px-4 font-semibold">Nombre</th>
                                <th class="text-left py-3 px-4 font-semibold">Usuario</th>
                                <th class="text-left py-3 px-4 font-semibold">Email</th>
                                <th class="text-left py-3 px-4 font-semibold">Fecha de Contratación</th>
                                <th class="text-left py-3 px-4 font-semibold">Rol</th>
                                <th class="text-left py-3 px-4 font-semibold">Posición</th>
                                <th class="text-left py-3 px-4 font-semibold">Evaluador</th>
                                <th class="text-left py-3 px-4 font-semibold">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="emp in employees" :key="emp.id" class="border-t hover:bg-surface-50">
                                <td class="py-3 px-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 rounded-full bg-gradient-to-br from-violet-500 to-violet-600 flex items-center justify-center text-white text-sm font-semibold flex-shrink-0">
                                            {{ getInitials(emp.name) }}
                                        </div>
                                        <div>
                                            <div class="font-medium text-surface-900">{{ emp.name }}</div>
                                            <div class="text-sm text-surface-500 lg:hidden">{{ emp.department }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="py-3 px-4 hidden md:table-cell">{{ emp.user?.username }}</td>
                                <td class="py-3 px-4 hidden md:table-cell">{{ emp.user?.email }}</td>
                                <td class="py-3 px-4 hidden lg:table-cell">{{ emp.hire_date ? new Date(emp.hire_date).toLocaleDateString('es-ES') : 'N/A' }}</td>
                                <td class="py-3 px-4">
                                    <span class="badge badge-primary text-xs">{{ emp.role?.name || 'N/A' }}</span>
                                </td>
                                <td class="py-3 px-4 hidden sm:table-cell">
                                    <span class="badge badge-warning text-xs">{{ emp.position?.name || 'N/A' }}</span>
                                </td>
                                <td class="py-3 px-4 hidden sm:table-cell">
                                    <span v-if="emp.evaluator" class="text-sm text-surface-600">{{ emp.evaluator.name }}</span>
                                    <span v-else class="text-sm text-surface-400">Sin asignar</span>
                                </td>
                                <td class="py-3 px-4">
                                    <div class="flex items-center gap-2">
                                        <button @click="editEmployee(emp)" class="btn btn-ghost btn-sm p-1.5" title="Editar">
                                            <svg class="w-4 h-4 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                            </svg>
                                        </button>
                                        <button @click="confirmDeleteEmployee(emp)" class="btn btn-ghost btn-sm p-1.5" title="Eliminar">
                                            <svg class="w-4 h-4 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                            </svg>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            <tr v-if="!employees.length">
                                <td colspan="8" class="py-8 text-center text-surface-500">
                                    <div class="flex flex-col items-center">
                                        <svg class="w-12 h-12 text-surface-300 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        </svg>
                                        No se encontraron empleados
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Roles Tab -->
        <div v-if="activeTab === 'roles'">
            <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4 mb-4">
                <h2 class="text-lg font-semibold text-surface-900">Roles</h2>
                <button @click="openRoleModal()" class="btn btn-primary">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    <span class="hidden sm:inline">Agregar Rol</span>
                    <span class="sm:hidden">Agregar</span>
                </button>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                <div v-for="role in roles" :key="role.id" class="card card-hover p-4">
                    <div class="flex justify-between items-start mb-3">
                        <div class="flex-1">
                            <h3 class="font-bold text-lg text-surface-900">{{ role.name }}</h3>
                            <p class="text-surface-600 text-sm">{{ role.description }}</p>
                            <span class="badge badge-primary text-xs mt-2 inline-block">{{ role.department }}</span>
                        </div>
                        <div class="flex gap-1">
                            <button @click="editRole(role)" class="btn btn-ghost btn-sm p-1.5" title="Editar">
                                <svg class="w-4 h-4 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                </svg>
                            </button>
                            <button @click="confirmDeleteRole(role)" class="btn btn-ghost btn-sm p-1.5" title="Eliminar">
                                <svg class="w-4 h-4 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                </svg>
                            </button>
                        </div>
                    </div>
                    <div class="text-sm text-surface-500">{{ role.competencies?.length || 0 }} competencias</div>
                </div>
                <div v-if="!roles.length" class="col-span-full">
                    <div class="card p-8 text-center">
                        <svg class="w-12 h-12 text-surface-300 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                        </svg>
                        <p class="text-surface-500">No se encontraron roles</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Positions Tab -->
        <div v-if="activeTab === 'positions'">
            <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4 mb-4">
                <h2 class="text-lg font-semibold text-surface-900">Posiciones</h2>
                <button @click="openPositionModal()" class="btn btn-primary">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    <span class="hidden sm:inline">Agregar Posición</span>
                    <span class="sm:hidden">Agregar</span>
                </button>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                <div v-for="pos in positions" :key="pos.id" class="card card-hover p-4">
                    <div class="flex justify-between items-start mb-3">
                        <div class="flex-1">
                            <h3 class="font-bold text-lg text-surface-900">{{ pos.name }}</h3>
                            <p class="text-surface-600 text-sm">{{ pos.description }}</p>
                            <div class="flex gap-2 mt-2">
                                <span class="badge badge-warning text-xs">{{ pos.level }}</span>
                                <span class="badge badge-neutral text-xs">{{ pos.salary_range || 'N/A' }}</span>
                            </div>
                        </div>
                        <div class="flex gap-1">
                            <button @click="editPosition(pos)" class="btn btn-ghost btn-sm p-1.5" title="Editar">
                                <svg class="w-4 h-4 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                </svg>
                            </button>
                            <button @click="confirmDeletePosition(pos)" class="btn btn-ghost btn-sm p-1.5" title="Eliminar">
                                <svg class="w-4 h-4 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                </svg>
                            </button>
                        </div>
                    </div>
                    <div class="text-sm text-surface-500">{{ pos.indicators?.length || 0 }} indicadores</div>
                </div>
                <div v-if="!positions.length" class="col-span-full">
                    <div class="card p-8 text-center">
                        <svg class="w-12 h-12 text-surface-300 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                        </svg>
                        <p class="text-surface-500">No se encontraron posiciones</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Role Modal -->
        <div v-if="showRoleModal" class="modal-overlay" @click.self="closeRoleModal">
            <div class="modal animate-scale-in">
                <div class="modal-header">
                    <h3 class="text-lg font-bold text-surface-900">{{ editingRole ? 'Editar Rol' : 'Agregar Rol' }}</h3>
                    <button @click="closeRoleModal" class="btn btn-ghost p-1">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
                <form @submit.prevent="saveRole" class="modal-body space-y-4">
                    <div>
                        <label class="label">Nombre del Rol</label>
                        <input v-model="roleForm.name" class="input" placeholder="Nombre del rol" required />
                    </div>
                    <div>
                        <label class="label">Descripción</label>
                        <textarea v-model="roleForm.description" class="input" rows="2" placeholder="Descripción del rol"></textarea>
                    </div>
                    <div>
                        <label class="label">Departamento</label>
                        <select v-model="roleForm.department" class="select" required>
                            <option value="">Seleccionar Departamento</option>
                            <option v-for="dept in departments" :key="dept" :value="dept">{{ dept }}</option>
                        </select>
                    </div>
                    <div class="flex gap-3 pt-2">
                        <button type="submit" class="btn btn-primary flex-1">{{ editingRole ? 'Actualizar' : 'Crear' }}</button>
                        <button type="button" @click="closeRoleModal" class="btn btn-secondary flex-1">Cancelar</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Position Modal -->
        <div v-if="showPositionModal" class="modal-overlay" @click.self="closePositionModal">
            <div class="modal animate-scale-in">
                <div class="modal-header">
                    <h3 class="text-lg font-bold text-surface-900">{{ editingPosition ? 'Editar Posición' : 'Agregar Posición' }}</h3>
                    <button @click="closePositionModal" class="btn btn-ghost p-1">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
                <form @submit.prevent="savePosition" class="modal-body space-y-4">
                    <div>
                        <label class="label">Nombre de la Posición</label>
                        <input v-model="positionForm.name" class="input" placeholder="Nombre de la posición" required />
                    </div>
                    <div>
                        <label class="label">Nivel</label>
                        <select v-model="positionForm.level" class="select" required>
                            <option value="">Seleccionar Nivel</option>
                            <option v-for="lvl in levels" :key="lvl" :value="lvl">{{ lvl }}</option>
                        </select>
                    </div>
                    <div>
                        <label class="label">Rango Salarial</label>
                        <input v-model="positionForm.salary_range" class="input" placeholder="ej. $50k-$70k" />
                    </div>
                    <div>
                        <label class="label">Descripción</label>
                        <textarea v-model="positionForm.description" class="input" rows="2" placeholder="Descripción de la posición"></textarea>
                    </div>
                    <div class="flex gap-3 pt-2">
                        <button type="submit" class="btn btn-primary flex-1">{{ editingPosition ? 'Actualizar' : 'Crear' }}</button>
                        <button type="button" @click="closePositionModal" class="btn btn-secondary flex-1">Cancelar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref } from 'vue';
import { router } from '@inertiajs/vue3';
import AppLayout from '../Layouts/AppLayout.vue';

defineOptions({
    layout: AppLayout,
});

const props = defineProps({
    employees: { type: Array, default: () => [] },
    roles: { type: Array, default: () => [] },
    positions: { type: Array, default: () => [] },
    evaluators: { type: Array, default: () => [] },
});

const activeTab = ref('employees');
const tabs = [
    { key: 'employees', label: 'Empleados' },
    { key: 'roles', label: 'Roles' },
    { key: 'positions', label: 'Posiciones' },
];

const departments = ['HR', 'IT', 'Finance', 'Operations', 'Marketing', 'Sales', 'Admin', 'Support'];
const levels = ['Intern', 'Junior', 'Mid-Level', 'Senior', 'Lead', 'Manager', 'Director', 'VP', 'Executive'];

const showEmployeeModal = ref(false);
const showRoleModal = ref(false);
const showPositionModal = ref(false);
const editingEmployee = ref(null);
const editingRole = ref(null);
const editingPosition = ref(null);

const employeeForm = ref({
    name: '',
    username: '',
    email: '',
    password: '',
    role_id: '',
    position_id: '',
    department: '',
    evaluator_id: '',
    user_role: 'employee',
    hire_date: '',
});

const roleForm = ref({ name: '', description: '', department: '' });
const positionForm = ref({ name: '', level: '', salary_range: '', description: '' });

const getInitials = (name) => {
    return name.split(' ').map(n => n[0]).slice(0, 2).join('').toUpperCase();
};

const openEmployeeModal = () => {
    editingEmployee.value = null;
    employeeForm.value = {
        name: '',
        username: '',
        email: '',
        password: '',
        role_id: '',
        position_id: '',
        department: '',
        evaluator_id: '',
        user_role: 'employee',
        hire_date: '',
    };
    showEmployeeModal.value = true;
};

const editEmployee = (emp) => {
    editingEmployee.value = emp.id;
    employeeForm.value = {
        name: emp.name,
        username: emp.user?.username || '',
        email: emp.user?.email || '',
        password: '',
        role_id: emp.role_id || '',
        position_id: emp.position_id || '',
        department: emp.department || '',
        evaluator_id: emp.evaluator_id || '',
        user_role: emp.user?.role || '',
        hire_date: emp.hire_date || '',
    };
    showEmployeeModal.value = true;
};

const closeEmployeeModal = () => {
    showEmployeeModal.value = false;
    editingEmployee.value = null;
};

const saveEmployee = () => {
    if (editingEmployee.value) {
        router.put(`/employees/${editingEmployee.value}`, employeeForm.value);
    } else {
        router.post('/employees', employeeForm.value);
    }
    closeEmployeeModal();
    window.Toast.fire({ icon: 'success', title: editingEmployee.value ? 'Empleado actualizado' : 'Empleado creado' });
};

const confirmDeleteEmployee = (emp) => {
    window.Swal.fire({
        title: '¿Eliminar empleado?',
        text: `¿Estás seguro de eliminar a ${emp.name}?`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Eliminar',
        cancelButtonText: 'Cancelar',
        confirmButtonColor: '#f43f5e',
        reverseButtons: true
    }).then((result) => {
        if (result.isConfirmed) {
            router.delete(`/employees/${emp.id}`);
            window.Toast.fire({ icon: 'success', title: 'Empleado eliminado' });
        }
    });
};

const openRoleModal = () => {
    editingRole.value = null;
    roleForm.value = { name: '', description: '', department: '' };
    showRoleModal.value = true;
};

const editRole = (role) => {
    editingRole.value = role.id;
    roleForm.value = { ...role };
    showRoleModal.value = true;
};

const closeRoleModal = () => {
    showRoleModal.value = false;
    editingRole.value = null;
};

const saveRole = () => {
    if (editingRole.value) {
        router.put(`/roles/${editingRole.value}`, roleForm.value);
    } else {
        router.post('/roles', roleForm.value);
    }
    closeRoleModal();
    window.Toast.fire({ icon: 'success', title: editingRole.value ? 'Rol actualizado' : 'Rol creado' });
};

const confirmDeleteRole = (role) => {
    window.Swal.fire({
        title: '¿Eliminar rol?',
        text: `¿Estás seguro de eliminar el rol "${role.name}"?`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Eliminar',
        cancelButtonText: 'Cancelar',
        confirmButtonColor: '#f43f5e',
        reverseButtons: true
    }).then((result) => {
        if (result.isConfirmed) {
            router.delete(`/roles/${role.id}`);
            window.Toast.fire({ icon: 'success', title: 'Rol eliminado' });
        }
    });
};

const openPositionModal = () => {
    editingPosition.value = null;
    positionForm.value = { name: '', level: '', salary_range: '', description: '' };
    showPositionModal.value = true;
};

const editPosition = (pos) => {
    editingPosition.value = pos.id;
    positionForm.value = { ...pos };
    showPositionModal.value = true;
};

const closePositionModal = () => {
    showPositionModal.value = false;
    editingPosition.value = null;
};

const savePosition = () => {
    if (editingPosition.value) {
        router.put(`/positions/${editingPosition.value}`, positionForm.value);
    } else {
        router.post('/positions', positionForm.value);
    }
    closePositionModal();
    window.Toast.fire({ icon: 'success', title: editingPosition.value ? 'Posición actualizada' : 'Posición creada' });
};

const confirmDeletePosition = (pos) => {
    window.Swal.fire({
        title: '¿Eliminar posición?',
        text: `¿Estás seguro de eliminar la posición "${pos.name}"?`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Eliminar',
        cancelButtonText: 'Cancelar',
        confirmButtonColor: '#f43f5e',
        reverseButtons: true
    }).then((result) => {
        if (result.isConfirmed) {
            router.delete(`/positions/${pos.id}`);
            window.Toast.fire({ icon: 'success', title: 'Posición eliminada' });
        }
    });
};
</script>