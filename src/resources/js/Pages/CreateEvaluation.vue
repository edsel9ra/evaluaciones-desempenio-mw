<template>
    <div class="space-y-6 animate-fade-in">
        <div>
            <h1 class="page-title">Crear Evaluación</h1>
            <p class="page-subtitle">Asigna nuevas evaluaciones a empleados</p>
        </div>

        <div class="card">
            <div class="card-header">
                <h2 class="font-semibold text-surface-900">Nueva Evaluación</h2>
            </div>
            <div class="card-body">
                <form @submit.prevent="submit">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <div>
                            <label class="label">Período de Evaluación</label>
                            <select v-model="form.period" class="select" required>
                                <option value="">Seleccionar Período</option>
                                <optgroup label="Trimestral">
                                    <template v-for="(label, key) in periods" :key="key">
                                        <option v-if="key.startsWith('Q')" :value="key">{{ label }}</option>
                                    </template>
                                </optgroup>
                                <optgroup label="Anual">
                                    <template v-for="(label, key) in periods" :key="key">
                                        <option v-if="key.startsWith('Annual') || key.startsWith('Mid') || key.startsWith('Year')" :value="key">{{ label }}</option>
                                    </template>
                                </optgroup>
                            </select>
                        </div>
                    </div>

                    <div class="mb-6">
                        <label class="label">
                            Seleccionar Empleados
                            <span v-if="form.employee_ids.length > 0" class="text-primary-600 font-normal">
                                ({{ selectedEmployees.length }} seleccionados)
                            </span>
                        </label>
                        <div class="border-2 border-surface-200 rounded-xl p-4 max-h-64 overflow-y-auto">
                            <div v-if="employees.length" class="space-y-2">
                                <label v-for="emp in employees" :key="emp.id"
                                    class="flex items-center gap-3 p-3 rounded-lg hover:bg-surface-50 cursor-pointer transition-colors">
                                    <input type="checkbox" :value="emp.id" v-model="form.employee_ids"
                                        class="w-4 h-4 text-primary-600 rounded" />
                                    <div class="w-10 h-10 rounded-full bg-gradient-to-br from-violet-500 to-violet-600 flex items-center justify-center text-white text-sm font-semibold flex-shrink-0">
                                        {{ getInitials(emp.name) }}
                                    </div>
                                    <div>
                                        <div class="font-medium text-surface-900">{{ emp.name }}</div>
                                        <div class="text-sm text-surface-500">
                                            Rol: {{ emp.role?.name || 'N/A' }} | Posición: {{ emp.position?.name || 'N/A' }}
                                        </div>
                                    </div>
                                </label>
                            </div>
                            <p v-else class="text-surface-500 text-center py-4">No hay empleados disponibles</p>
                        </div>
                    </div>

                    <div v-if="form.employee_ids.length > 0">
                        <div class="mb-6">
                            <h3 class="text-lg font-semibold mb-3 text-surface-900">Items de Evaluación</h3>
                            <p class="text-sm text-surface-600 mb-4">
                                Basado en los roles y posiciones de los empleados seleccionados.
                                <br>
                                <span class="text-primary-600">Competencias (30%)</span> basadas en roles,
                                <span class="text-emerald-600">Indicadores (70%)</span> basados en posiciones.
                            </p>

                            <div v-if="filteredCompetencies.length > 0 || filteredIndicators.length > 0" class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                                <div class="card p-4 border-primary-200">
                                    <h4 class="font-semibold mb-3 text-primary-700 flex items-center gap-2">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        Competencias (30%)
                                        <span class="text-sm font-normal text-surface-500">({{ filteredCompetencies.length }} disponibles)</span>
                                    </h4>
                                    <div v-if="filteredCompetencies.length" class="space-y-2 max-h-48 overflow-y-auto">
                                        <label v-for="comp in filteredCompetencies" :key="comp.id"
                                            class="flex items-center gap-2 p-2 rounded hover:bg-surface-50 cursor-pointer">
                                            <input type="checkbox" :value="comp.id" v-model="selectedCompetencyIds"
                                                class="w-4 h-4 text-primary-600 rounded" />
                                            <div>
                                                <div class="font-medium text-sm text-surface-900">{{ comp.name }}</div>
                                                <div class="text-xs text-surface-500">{{ comp.category }}</div>
                                            </div>
                                        </label>
                                    </div>
                                    <p v-else class="text-surface-500 text-sm">No hay competencias disponibles para los roles seleccionados</p>
                                    <div class="mt-3 pt-3 border-t border-surface-100 text-sm text-surface-600">
                                        Seleccionadas: {{ selectedCompetencyIds.length }}
                                    </div>
                                </div>

                                <div class="card p-4 border-emerald-200">
                                    <h4 class="font-semibold mb-3 text-emerald-700 flex items-center gap-2">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        Indicadores (70%)
                                        <span class="text-sm font-normal text-surface-500">({{ filteredIndicators.length }} disponibles)</span>
                                    </h4>
                                    <div v-if="filteredIndicators.length" class="space-y-2 max-h-48 overflow-y-auto">
                                        <label v-for="ind in filteredIndicators" :key="ind.id"
                                            class="flex items-center gap-2 p-2 rounded hover:bg-surface-50 cursor-pointer">
                                            <input type="checkbox" :value="ind.id" v-model="selectedIndicatorIds"
                                                class="w-4 h-4 text-emerald-600 rounded" />
                                            <div>
                                                <div class="font-medium text-sm text-surface-900">{{ ind.name }}</div>
                                                <div class="text-xs text-surface-500">{{ ind.category }}</div>
                                            </div>
                                        </label>
                                    </div>
                                    <p v-else class="text-surface-500 text-sm">No hay indicadores disponibles para las posiciones seleccionadas</p>
                                    <div class="mt-3 pt-3 border-t border-surface-100 text-sm text-surface-600">
                                        Seleccionadas: {{ selectedIndicatorIds.length }}
                                    </div>
                                </div>
                            </div>

                            <div v-else class="p-4 bg-amber-50 border border-amber-200 rounded-xl">
                                <div class="flex items-start gap-3">
                                    <svg class="w-5 h-5 text-amber-600 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                                    </svg>
                                    <div>
                                        <p class="text-amber-700 text-sm font-medium">No hay items de evaluación</p>
                                        <p class="text-amber-600 text-sm">No hay competencias asociadas a los roles o indicadores a las posiciones seleccionadas. Por favor, asigna competencias a roles e indicadores a posiciones en la Gestión de Items.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div v-else class="mb-6 p-6 bg-surface-50 rounded-xl text-center border border-surface-200">
                        <svg class="w-12 h-12 text-surface-300 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                        <p class="text-surface-500">Selecciona empleados para ver competencias e indicadores disponibles</p>
                    </div>

                    <div v-if="message.text"
                        :class="['p-4 rounded-xl mb-4 flex items-center gap-3', message.type === 'success' ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-rose-50 text-rose-700 border border-rose-200']">
                        <svg v-if="message.type === 'success'" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <svg v-else class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        {{ message.text }}
                    </div>

                    <div class="flex gap-3">
                        <button type="submit" class="btn btn-primary btn-lg flex-1" :disabled="form.processing || !isValid">
                            <svg v-if="form.processing" class="w-5 h-5 animate-spin" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            <span v-else>Crear Evaluación</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, computed, reactive, watch } from 'vue';
import { router } from '@inertiajs/vue3';
import AppLayout from '../Layouts/AppLayout.vue';

defineOptions({
    layout: AppLayout,
});

const props = defineProps({
    employees: { type: Array, default: () => [] },
    periods: { type: Object, default: () => ({}) },
    competencies: { type: Array, default: () => [] },
    indicators: { type: Array, default: () => [] },
});

const form = reactive({
    period: '',
    employee_ids: [],
    processing: false,
});

const message = ref({ type: '', text: '' });

const selectedCompetencyIds = ref([]);
const selectedIndicatorIds = ref([]);

const getInitials = (name) => {
    return name.split(' ').map(n => n[0]).slice(0, 2).join('').toUpperCase();
};

const selectedEmployees = computed(() => {
    return props.employees.filter(emp => form.employee_ids.includes(emp.id));
});

const selectedRoleIds = computed(() => {
    const roleIds = new Set();
    selectedEmployees.value.forEach(emp => {
        if (emp.role_id) {
            roleIds.add(emp.role_id);
        }
    });
    return Array.from(roleIds);
});

const selectedPositionIds = computed(() => {
    const positionIds = new Set();
    selectedEmployees.value.forEach(emp => {
        if (emp.position_id) {
            positionIds.add(emp.position_id);
        }
    });
    return Array.from(positionIds);
});

const filteredCompetencies = computed(() => {
    if (selectedRoleIds.value.length === 0) {
        return [];
    }

    return props.competencies.filter(comp => {
        if (!comp.roles || !Array.isArray(comp.roles)) {
            return false;
        }
        return comp.roles.some(role => selectedRoleIds.value.includes(role.id));
    });
});

const filteredIndicators = computed(() => {
    if (selectedPositionIds.value.length === 0) {
        return [];
    }

    return props.indicators.filter(ind => {
        if (!ind.positions || !Array.isArray(ind.positions)) {
            return false;
        }
        return ind.positions.some(pos => selectedPositionIds.value.includes(pos.id));
    });
});

watch(() => form.employee_ids, () => {
    selectedCompetencyIds.value = [];
    selectedIndicatorIds.value = [];
});

const isValid = computed(() => {
    return form.period &&
           form.employee_ids.length > 0 &&
           (selectedCompetencyIds.value.length > 0 || selectedIndicatorIds.value.length > 0);
});

const submit = () => {
    if (!form.period || form.employee_ids.length === 0) {
        message.value = { type: 'error', text: 'Por favor selecciona un período y al menos un empleado' };
        return;
    }

    if (selectedCompetencyIds.value.length === 0 && selectedIndicatorIds.value.length === 0) {
        message.value = { type: 'error', text: 'Por favor selecciona al menos una competencia o indicador' };
        return;
    }

    form.processing = true;

    const selectedCompetencies = selectedCompetencyIds.value.map(id => ({ id }));
    const selectedIndicators = selectedIndicatorIds.value.map(id => ({ id }));

    router.post('/evaluations', {
        period: form.period,
        employee_ids: form.employee_ids,
        selected_competencies: selectedCompetencies,
        selected_indicators: selectedIndicators,
    }, {
        onSuccess: () => {
            message.value = { type: 'success', text: '¡Evaluación creada exitosamente!' };
            form.period = '';
            form.employee_ids = [];
            selectedCompetencyIds.value = [];
            selectedIndicatorIds.value = [];
            form.processing = false;
        },
        onError: (errors) => {
            console.error(errors);
            message.value = { type: 'error', text: 'Error al crear evaluación: ' + Object.values(errors).join(', ') };
            form.processing = false;
        },
    });
};
</script>