<template>
    <div class="space-y-6 animate-fade-in">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="page-title">{{ isEvaluator ? 'Mis Reportes' : 'Reportes y Resúmenes' }}</h1>
                <p class="page-subtitle">Análisis y estadísticas de evaluaciones</p>
            </div>
        </div>

        <div class="border-b border-surface-200 overflow-x-auto">
            <div class="flex gap-1 min-w-max">
                <button v-for="tab in filteredTabs" :key="tab.key" @click="changeTab(tab.key)"
                    class="tab-button whitespace-nowrap" :class="{ 'tab-button-active': activeTab === tab.key }">
                    {{ tab.label }}
                </button>
            </div>
        </div>

        <div v-if="activeTab === 'individual'" class="space-y-6">
            <div class="card">
                <div class="card-header flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                    <h2 class="font-semibold text-surface-900">Seleccionar Empleado</h2>
                    <div v-if="selectedEmployeeName" class="flex items-center gap-2 text-sm text-surface-600">
                        <span>Seleccionado:</span>
                        <span class="font-semibold text-primary-600">{{ selectedEmployeeName }}</span>
                    </div>
                </div>
                <div class="card-body">
                    <div class="flex flex-col sm:flex-row gap-4">
                        <div class="flex-1 relative">
                            <select v-model="selectedEmployee" @change="generateIndividual" class="select">
                                <option value="">Seleccionar Empleado</option>
                                <option v-for="emp in employees" :key="emp.id" :value="emp.id">{{ emp.name }}</option>
                            </select>
                        </div>
                        <button v-if="selectedEmployee" @click="clearSelection" class="btn btn-ghost">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                            Limpiar
                        </button>
                    </div>
                </div>
            </div>

            <div v-if="props.individualReport" class="space-y-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="font-semibold text-surface-900">Información del Empleado</h3>
                        </div>
                        <div class="card-body">
                            <div class="flex items-center gap-4">
                                <div class="w-16 h-16 rounded-full bg-gradient-to-br from-violet-500 to-violet-600 flex items-center justify-center text-white text-xl font-bold flex-shrink-0">
                                    {{ getInitials(props.individualReport.employee?.name) }}
                                </div>
                                <div>
                                    <h4 class="text-lg font-bold text-surface-900">{{ props.individualReport.employee?.name }}</h4>
                                    <p class="text-surface-600">{{ props.individualReport.employee?.role?.name }}</p>
                                    <p class="text-sm text-surface-400">{{ props.individualReport.employee?.department }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-header">
                            <h3 class="font-semibold text-surface-900">Resumen de Puntuaciones</h3>
                        </div>
                        <div class="card-body">
                            <div class="grid grid-cols-3 gap-4">
                                <div class="text-center p-4 bg-primary-50 rounded-xl">
                                    <div class="text-2xl font-bold text-primary-600">{{ props.individualReport.averageScore }}</div>
                                    <div class="text-xs text-surface-500 mt-1">Promedio</div>
                                </div>
                                <div class="text-center p-4 bg-emerald-50 rounded-xl">
                                    <div class="text-2xl font-bold text-emerald-600">{{ props.individualReport.highestScore }}</div>
                                    <div class="text-xs text-surface-500 mt-1">Más Alto</div>
                                </div>
                                <div class="text-center p-4 bg-amber-50 rounded-xl">
                                    <div class="text-2xl font-bold text-amber-600">{{ props.individualReport.lowestScore }}</div>
                                    <div class="text-xs text-surface-500 mt-1">Más Bajo</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div v-if="props.individualReport?.evaluations?.length" class="card">
                    <div class="card-header">
                        <h3 class="font-semibold text-surface-900">Gráfico de Radar - Evaluaciones</h3>
                    </div>
                    <div class="card-body">
                        <div class="flex flex-col lg:flex-row gap-6 items-start">
                            <div class="w-full lg:w-1/2 h-80">
                                <Radar :data="radarChartData" :options="radarOptions" />
                            </div>
                            <div class="w-full lg:w-1/2">
                                <div class="space-y-3">
                                    <div v-for="(eva, index) in props.individualReport.evaluations" :key="eva.id"
                                        class="flex items-center justify-between p-3 rounded-lg border border-surface-200 hover:border-primary-300 transition-colors cursor-pointer"
                                        :class="selectedEvaluationIndex === index ? 'bg-primary-50 border-primary-400' : 'bg-surface-50'"
                                        @click="selectedEvaluationIndex = index">
                                        <div class="flex items-center gap-3">
                                            <div class="w-8 h-8 rounded-full flex items-center justify-center text-white text-sm font-bold"
                                                :style="{ backgroundColor: chartColors[index % chartColors.length] }">
                                                {{ index + 1 }}
                                            </div>
                                            <div>
                                                <p class="font-medium text-surface-900">{{ eva.period }}</p>
                                                <p class="text-xs text-surface-500">{{ formatDate(eva.evaluator_completed_date) }}</p>
                                            </div>
                                        </div>
                                        <div class="text-right">
                                            <p class="text-lg font-bold text-surface-900">{{ eva.total_score?.toFixed(2) }}</p>
                                            <p class="text-xs text-surface-500">/ 5.0</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div v-if="selectedEvaluation" class="card">
                    <div class="card-header flex items-center justify-between">
                        <h3 class="font-semibold text-surface-900">Detalle: {{ selectedEvaluation.period }}</h3>
                        <button @click="selectedEvaluationIndex = null" class="btn btn-ghost btn-sm">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>
                    <div class="card-body">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                            <div class="p-4 bg-primary-50 rounded-xl text-center">
                                <div class="text-3xl font-bold text-primary-600">{{ selectedEvaluation.competency_score?.toFixed(2) }}</div>
                                <div class="text-sm text-surface-600">Competencias (30%)</div>
                            </div>
                            <div class="p-4 bg-emerald-50 rounded-xl text-center">
                                <div class="text-3xl font-bold text-emerald-600">{{ selectedEvaluation.indicator_score?.toFixed(2) }}</div>
                                <div class="text-sm text-surface-600">Indicadores (70%)</div>
                            </div>
                            <div class="p-4 bg-violet-50 rounded-xl text-center">
                                <div class="text-3xl font-bold text-violet-600">{{ selectedEvaluation.total_score?.toFixed(2) }}</div>
                                <div class="text-sm text-surface-600">Puntaje Total</div>
                            </div>
                        </div>
                        <div v-if="selectedEvaluation.evaluator" class="p-3 bg-surface-50 rounded-lg mb-4">
                            <span class="text-sm text-surface-600">Evaluado por: </span>
                            <span class="font-medium text-surface-900">{{ selectedEvaluation.evaluator.name }}</span>
                        </div>
                    </div>
                </div>

                <div v-if="props.individualReport?.evaluations?.length" class="card">
                    <div class="card-header">
                        <h3 class="font-semibold text-surface-900">Historial de Evaluaciones</h3>
                    </div>
                    <div class="card-body">
                        <div class="table-container">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th class="text-left py-3 px-4 font-semibold">Período</th>
                                        <th class="text-left py-3 px-4 font-semibold">Evaluador</th>
                                        <th class="text-left py-3 px-4 font-semibold">Competencias</th>
                                        <th class="text-left py-3 px-4 font-semibold">Indicadores</th>
                                        <th class="text-left py-3 px-4 font-semibold">Puntuación</th>
                                        <th class="text-left py-3 px-4 font-semibold">Fecha</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="evaluation in props.individualReport.evaluations" :key="evaluation.id" class="border-t">
                                        <td class="py-3 px-4 font-medium text-surface-900">{{ evaluation.period }}</td>
                                        <td class="py-3 px-4 text-surface-600">{{ evaluation.evaluator?.name || 'N/A' }}</td>
                                        <td class="py-3 px-4">
                                            <span class="font-semibold text-primary-600">{{ evaluation.competency_score?.toFixed(2) }}</span>
                                        </td>
                                        <td class="py-3 px-4">
                                            <span class="font-semibold text-emerald-600">{{ evaluation.indicator_score?.toFixed(2) }}</span>
                                        </td>
                                        <td class="py-3 px-4">
                                            <div class="flex items-center gap-2">
                                                <div class="w-16 h-2 bg-surface-200 rounded-full overflow-hidden">
                                                    <div class="h-full bg-primary-500 rounded-full" :style="{ width: (evaluation.total_score / 5 * 100) + '%' }"></div>
                                                </div>
                                                <span class="font-semibold text-surface-900">{{ evaluation.total_score?.toFixed(2) }}</span>
                                            </div>
                                        </td>
                                        <td class="py-3 px-4 text-surface-500">{{ formatDate(evaluation.evaluator_completed_date) }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div v-else class="card p-8">
                    <div class="empty-state">
                        <svg class="empty-state-icon w-16 h-16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        <p class="empty-state-title">Sin evaluaciones</p>
                        <p class="empty-state-description">Este empleado no tiene evaluaciones completadas.</p>
                    </div>
                </div>
            </div>

            <div v-else class="card p-12">
                <div class="text-center">
                    <div class="w-20 h-20 mx-auto mb-4 rounded-full bg-surface-100 flex items-center justify-center">
                        <svg class="w-10 h-10 text-surface-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-surface-900 mb-2">Selecciona un empleado</h3>
                    <p class="text-surface-500">Elige un empleado del menú desplegable para ver sus reportes.</p>
                </div>
            </div>
        </div>

        <div v-if="activeTab === 'group' && isAdmin" class="space-y-6">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="card">
                    <div class="card-header">
                        <h3 class="font-semibold text-surface-900">Total Empleados</h3>
                    </div>
                    <div class="card-body text-center">
                        <div class="text-4xl font-bold text-primary-600">{{ groupReport?.length || 0 }}</div>
                        <p class="text-surface-500 text-sm">Con evaluaciones</p>
                    </div>
                </div>
                <div class="card">
                    <div class="card-header">
                        <h3 class="font-semibold text-surface-900">Promedio General</h3>
                    </div>
                    <div class="card-body text-center">
                        <div class="text-4xl font-bold text-emerald-600">{{ overallAverage }}</div>
                        <p class="text-surface-500 text-sm">De todas las evaluaciones</p>
                    </div>
                </div>
                <div class="card">
                    <div class="card-header">
                        <h3 class="font-semibold text-surface-900">Total Evaluaciones</h3>
                    </div>
                    <div class="card-body text-center">
                        <div class="text-4xl font-bold text-violet-600">{{ totalEvaluations }}</div>
                        <p class="text-surface-500 text-sm">Completadas</p>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <h3 class="font-semibold text-surface-900">Rendimiento por Empleado</h3>
                </div>
                <div class="card-body">
                    <div class="table-container">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th class="text-left py-3 px-4 font-semibold">Empleado</th>
                                    <th class="text-left py-3 px-4 font-semibold">Evaluaciones</th>
                                    <th class="text-left py-3 px-4 font-semibold">Puntuación Promedio</th>
                                    <th class="text-left py-3 px-4 font-semibold">Rendimiento</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(r, i) in sortedGroupReport" :key="i" class="border-t">
                                    <td class="py-3 px-4">
                                        <div class="flex items-center gap-3">
                                            <div class="w-8 h-8 rounded-full bg-gradient-to-br from-violet-500 to-violet-600 flex items-center justify-center text-white text-xs font-semibold">
                                                {{ getInitials(r.employee_name) }}
                                            </div>
                                            <span class="font-medium text-surface-900">{{ r.employee_name }}</span>
                                        </div>
                                    </td>
                                    <td class="py-3 px-4">{{ r.total_evaluations }}</td>
                                    <td class="py-3 px-4">
                                        <span class="font-semibold text-primary-600">{{ r.average_score }}</span>
                                    </td>
                                    <td class="py-3 px-4">
                                        <div class="flex items-center gap-2">
                                            <div class="w-24 h-2 bg-surface-200 rounded-full overflow-hidden">
                                                <div class="h-full rounded-full" :class="getPerformanceColor(r.average_score)" :style="{ width: (r.average_score / 5 * 100) + '%' }"></div>
                                            </div>
                                            <span class="text-sm" :class="getPerformanceTextColor(r.average_score)">{{ getPerformanceLabel(r.average_score) }}</span>
                                        </div>
                                    </td>
                                </tr>
                                <tr v-if="!groupReport?.length">
                                    <td colspan="4" class="py-8 text-center text-surface-500">
                                        No hay datos disponibles
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div v-if="activeTab === 'trends'" class="space-y-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="card">
                    <div class="card-header">
                        <h3 class="font-semibold text-surface-900">Evaluaciones por Período</h3>
                    </div>
                    <div class="card-body">
                        <div class="space-y-3">
                            <div v-for="(r, i) in trendsReport" :key="i" class="flex items-center justify-between p-3 bg-surface-50 rounded-lg">
                                <span class="font-medium text-surface-700">{{ r.period }}</span>
                                <div class="flex items-center gap-3">
                                    <div class="w-32 h-2 bg-surface-200 rounded-full overflow-hidden">
                                        <div class="h-full bg-primary-500 rounded-full" :style="{ width: (r.total_evaluations / maxTrendEvaluations * 100) + '%' }"></div>
                                    </div>
                                    <span class="text-sm font-semibold text-surface-900 w-8 text-right">{{ r.total_evaluations }}</span>
                                </div>
                            </div>
                            <div v-if="!trendsReport?.length" class="text-center py-4 text-surface-500">
                                No hay datos disponibles
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header">
                        <h3 class="font-semibold text-surface-900">Tendencia de Puntuación</h3>
                    </div>
                    <div class="card-body">
                        <div class="space-y-3">
                            <div v-for="(r, i) in trendsReport" :key="i" class="flex items-center justify-between p-3 bg-surface-50 rounded-lg">
                                <span class="font-medium text-surface-700">{{ r.period }}</span>
                                <div class="flex items-center gap-3">
                                    <div class="w-32 h-2 bg-surface-200 rounded-full overflow-hidden">
                                        <div class="h-full rounded-full" :class="getScoreBarColor(r.average_score)" :style="{ width: (r.average_score / 5 * 100) + '%' }"></div>
                                    </div>
                                    <span class="text-sm font-semibold" :class="getPerformanceTextColor(r.average_score) + ' w-8 text-right'">{{ r.average_score }}</span>
                                </div>
                            </div>
                            <div v-if="!trendsReport?.length" class="text-center py-4 text-surface-500">
                                No hay datos disponibles
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <h3 class="font-semibold text-surface-900">Resumen de Tendencias</h3>
                </div>
                <div class="card-body">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div class="p-4 bg-surface-50 rounded-xl text-center">
                            <div class="text-2xl font-bold text-surface-900">{{ trendSummary.totalEvaluations }}</div>
                            <div class="text-sm text-surface-500">Total Evaluaciones</div>
                        </div>
                        <div class="p-4 bg-surface-50 rounded-xl text-center">
                            <div class="text-2xl font-bold text-surface-900">{{ trendSummary.averageScore }}</div>
                            <div class="text-sm text-surface-500">Promedio General</div>
                        </div>
                        <div class="p-4 bg-surface-50 rounded-xl text-center">
                            <div class="text-2xl font-bold" :class="trendSummary.trend >= 0 ? 'text-emerald-600' : 'text-rose-600'">
                                {{ trendSummary.trend >= 0 ? '+' : '' }}{{ trendSummary.trend }}
                            </div>
                            <div class="text-sm text-surface-500">Tendencia</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, computed, watch } from 'vue';
import { router, usePage } from '@inertiajs/vue3';
import { Radar } from 'vue-chartjs';
import {
    Chart as ChartJS,
    RadialLinearScale,
    PointElement,
    LineElement,
    Filler,
    Tooltip,
    Legend
} from 'chart.js';
import AppLayout from '../Layouts/AppLayout.vue';

ChartJS.register(RadialLinearScale, PointElement, LineElement, Filler, Tooltip, Legend);

defineOptions({
    layout: AppLayout,
});

const page = usePage();
const isEvaluator = computed(() => page.props.auth?.user?.role === 'evaluator');
const isAdmin = computed(() => page.props.auth?.user?.role === 'administrator');

const props = defineProps({
    employees: { type: Array, default: () => [] },
    individualReport: { type: Object, default: null },
    groupReport: { type: Array, default: () => [] },
    trendsReport: { type: Array, default: () => [] },
});

const activeTab = ref('individual');
const selectedEmployee = ref(props.individualReport?.employee?.id || '');
const selectedEvaluationIndex = ref(null);

const chartColors = [
    'rgba(59, 130, 246, 1)',
    'rgba(16, 185, 129, 1)',
    'rgba(139, 92, 246, 1)',
    'rgba(245, 158, 11, 1)',
    'rgba(236, 72, 153, 1)',
    'rgba(14, 165, 233, 1)',
];

const chartBackgroundColors = chartColors.map(c => c.replace('1)', '0.2)'));

const selectedEmployeeName = computed(() => {
    if (!selectedEmployee.value) return '';
    const emp = props.employees.find(e => e.id === selectedEmployee.value);
    return emp?.name || '';
});

const selectedEvaluation = computed(() => {
    if (selectedEvaluationIndex.value === null || !props.individualReport?.evaluations) return null;
    return props.individualReport.evaluations[selectedEvaluationIndex.value];
});

const tabs = [
    { key: 'individual', label: 'Reportes Individuales' },
    { key: 'group', label: 'Reportes Grupales' },
    { key: 'trends', label: 'Análisis de Tendencias' },
];

const filteredTabs = computed(() => {
    if (isEvaluator.value || !isAdmin.value) {
        return tabs.filter(tab => tab.key !== 'group');
    }
    return tabs;
});

const changeTab = (tab) => {
    activeTab.value = tab;
    selectedEmployee.value = '';
    selectedEvaluationIndex.value = null;

    if (tab === 'group' && isAdmin.value) {
        router.get('/reports/group', {}, { replace: true, preserveState: true });
    } else if (tab === 'trends') {
        router.get('/reports/trends', {}, { replace: true, preserveState: true });
    } else {
        router.get('/reports', {}, { replace: true, preserveState: true });
    }
};

const generateIndividual = () => {
    if (selectedEmployee.value) {
        selectedEvaluationIndex.value = null;
        router.get('/reports', { employee_id: selectedEmployee.value }, { replace: true, preserveState: true });
    }
};

const clearSelection = () => {
    selectedEmployee.value = '';
    selectedEvaluationIndex.value = null;
    router.get('/reports', {}, { replace: true, preserveState: true });
};

const getInitials = (name) => {
    if (!name) return '?';
    return name.split(' ').map(n => n[0]).slice(0, 2).join('').toUpperCase();
};

const formatDate = (date) => {
    if (!date) return 'N/A';
    return new Date(date).toLocaleDateString('es-ES', {
        year: 'numeric',
        month: 'short',
        day: 'numeric'
    });
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

const radarChartData = computed(() => {
    if (!props.individualReport?.evaluations?.length) {
        return { labels: [], datasets: [] };
    }

    const evaluations = props.individualReport.evaluations;
    const allLabels = [];
    const labelScores = {};

    evaluations.forEach(eva => {
        eva.competencies?.forEach(comp => {
            if (!allLabels.includes(comp.name)) {
                allLabels.push(comp.name);
            }
            labelScores[comp.name] = comp.score || 0;
        });
        eva.indicators?.forEach(ind => {
            if (!allLabels.includes(ind.name)) {
                allLabels.push(ind.name);
            }
            labelScores[ind.name] = ind.score || 0;
        });
    });

    if (allLabels.length === 0) {
        return {
            labels: ['Sin datos'],
            datasets: []
        };
    }

    return {
        labels: allLabels,
        datasets: evaluations.map((eva, index) => {
            const data = allLabels.map(label => {
                const comp = eva.competencies?.find(c => c.name === label);
                const ind = eva.indicators?.find(i => i.name === label);
                return (comp?.score || ind?.score || 0);
            });

            return {
                label: eva.period,
                data: data,
                fill: true,
                backgroundColor: chartBackgroundColors[index % chartColors.length],
                borderColor: chartColors[index % chartColors.length],
                borderWidth: 2,
                pointBackgroundColor: chartColors[index % chartColors.length],
                pointBorderColor: '#fff',
                pointHoverBackgroundColor: '#fff',
                pointHoverBorderColor: chartColors[index % chartColors.length],
                pointRadius: 5,
                pointHoverRadius: 7,
            };
        })
    };
});

const radarOptions = {
    responsive: true,
    maintainAspectRatio: false,
    scales: {
        r: {
            min: 0,
            max: 5,
            ticks: {
                stepSize: 0.5,
                display: true,
                backdropColor: 'transparent'
            },
            pointLabels: {
                font: {
                    size: 11,
                    weight: '500'
                },
                color: '#4b5563'
            },
            grid: {
                color: 'rgba(156, 163, 175, 0.25)'
            },
            angleLines: {
                color: 'rgba(156, 163, 175, 0.25)'
            }
        }
    },
    plugins: {
        legend: {
            position: 'bottom',
            labels: {
                usePointStyle: true,
                padding: 15,
                font: {
                    size: 11
                }
            }
        },
        tooltip: {
            backgroundColor: 'rgba(17, 24, 39, 0.9)',
            titleFont: { size: 12 },
            bodyFont: { size: 11 },
            padding: 10,
            cornerRadius: 6,
            callbacks: {
                title: function(context) {
                    return context[0].label;
                },
                label: function(context) {
                    return context.dataset.label + ': ' + (context.raw || 0).toFixed(2);
                }
            }
        }
    }
};

const sortedGroupReport = computed(() => {
    return [...(groupReport || [])].sort((a, b) => parseFloat(b.average_score) - parseFloat(a.average_score));
});

const groupReport = computed(() => props.groupReport);

const overallAverage = computed(() => {
    if (!groupReport.value?.length) return '0.00';
    const sum = groupReport.value.reduce((acc, r) => acc + parseFloat(r.average_score || 0), 0);
    return (sum / groupReport.value.length).toFixed(2);
});

const totalEvaluations = computed(() => {
    return groupReport.value?.reduce((acc, r) => acc + (r.total_evaluations || 0), 0) || 0;
});

const getPerformanceColor = (score) => {
    const s = parseFloat(score);
    if (s >= 4) return 'bg-emerald-500';
    if (s >= 3) return 'bg-primary-500';
    if (s >= 2) return 'bg-amber-500';
    return 'bg-rose-500';
};

const getPerformanceTextColor = (score) => {
    const s = parseFloat(score);
    if (s >= 4) return 'text-emerald-600';
    if (s >= 3) return 'text-primary-600';
    if (s >= 2) return 'text-amber-600';
    return 'text-rose-600';
};

const getPerformanceLabel = (score) => {
    const s = parseFloat(score);
    if (s >= 4) return 'Excelente';
    if (s >= 3) return 'Bueno';
    if (s >= 2) return 'Regular';
    return 'Bajo';
};

const maxTrendEvaluations = computed(() => {
    return Math.max(...(trendsReport?.map(r => r.total_evaluations) || [1]), 1);
});

const getScoreBarColor = (score) => {
    const s = parseFloat(score);
    if (s >= 4) return 'bg-emerald-500';
    if (s >= 3) return 'bg-primary-500';
    if (s >= 2) return 'bg-amber-500';
    return 'bg-rose-500';
};

const trendsReport = computed(() => props.trendsReport);

const trendSummary = computed(() => {
    const evaluations = trendsReport.value?.reduce((acc, r) => acc + (r.total_evaluations || 0), 0) || 0;
    const avg = trendsReport.value?.length ? (trendsReport.value.reduce((acc, r) => acc + parseFloat(r.average_score || 0), 0) / trendsReport.value.length).toFixed(2) : '0.00';

    let trend = 0;
    if (trendsReport.value?.length >= 2) {
        const recent = parseFloat(trendsReport.value[trendsReport.value.length - 1]?.average_score || 0);
        const older = parseFloat(trendsReport.value[0]?.average_score || 0);
        trend = (recent - older).toFixed(2);
    }

    return {
        totalEvaluations: evaluations,
        averageScore: avg,
        trend: trend
    };
});

watch(() => props.individualReport, (newVal) => {
    if (newVal?.employee?.id && !selectedEmployee.value) {
        selectedEmployee.value = newVal.employee.id;
    }
});
</script>