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
                <button v-for="tab in filteredTabs" :key="tab.key" @click="activeTab = tab.key"
                    class="tab-button whitespace-nowrap" :class="{ 'tab-button-active': activeTab === tab.key }">
                    {{ tab.label }}
                </button>
            </div>
        </div>

        <!-- Individual Report -->
        <div v-if="activeTab === 'individual'" class="space-y-6">
            <div class="card">
                <div class="card-header">
                    <h2 class="font-semibold text-surface-900">Seleccionar Empleado</h2>
                </div>
                <div class="card-body">
                    <form @submit.prevent="generateIndividual" class="flex flex-col sm:flex-row gap-4">
                        <select v-model="selectedEmployee" class="select flex-1" required>
                            <option value="">Seleccionar Empleado</option>
                            <option v-for="emp in employees" :key="emp.id" :value="emp.id">{{ emp.name }}</option>
                        </select>
                        <button type="submit" class="btn btn-primary">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                            Buscar
                        </button>
                    </form>
                </div>
            </div>

            <div v-if="individualReport" class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Employee Info Card -->
                <div class="card">
                    <div class="card-header">
                        <h3 class="font-semibold text-surface-900">Información del Empleado</h3>
                    </div>
                    <div class="card-body text-center">
                        <div class="w-20 h-20 mx-auto mb-4 rounded-full bg-gradient-to-br from-violet-500 to-violet-600 flex items-center justify-center text-white text-2xl font-bold">
                            {{ getInitials(individualReport.employee?.name) }}
                        </div>
                        <h4 class="text-lg font-bold text-surface-900">{{ individualReport.employee?.name }}</h4>
                        <p class="text-surface-500">{{ individualReport.employee?.role?.name }}</p>
                        <p class="text-sm text-surface-400 mt-2">{{ individualReport.employee?.department }}</p>
                    </div>
                </div>

                <!-- Score Summary -->
                <div class="card">
                    <div class="card-header">
                        <h3 class="font-semibold text-surface-900">Resumen de Puntuaciones</h3>
                    </div>
                    <div class="card-body space-y-4">
                        <div class="flex items-center justify-between p-3 bg-primary-50 rounded-lg">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-lg bg-primary-100 flex items-center justify-center">
                                    <svg class="w-5 h-5 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                                    </svg>
                                </div>
                                <span class="font-medium text-surface-700">Promedio</span>
                            </div>
                            <span class="text-2xl font-bold text-primary-600">{{ individualReport.averageScore }}</span>
                        </div>
                        <div class="flex items-center justify-between p-3 bg-emerald-50 rounded-lg">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-lg bg-emerald-100 flex items-center justify-center">
                                    <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"></path>
                                    </svg>
                                </div>
                                <span class="font-medium text-surface-700">Más Alto</span>
                            </div>
                            <span class="text-2xl font-bold text-emerald-600">{{ individualReport.highestScore }}</span>
                        </div>
                        <div class="flex items-center justify-between p-3 bg-amber-50 rounded-lg">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-lg bg-amber-100 flex items-center justify-center">
                                    <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 17h8m0 0V9m0 8l-8-8-4 4-6-6"></path>
                                    </svg>
                                </div>
                                <span class="font-medium text-surface-700">Más Bajo</span>
                            </div>
                            <span class="text-2xl font-bold text-amber-600">{{ individualReport.lowestScore }}</span>
                        </div>
                    </div>
                </div>

                <!-- Score Distribution Chart -->
                <div class="card">
                    <div class="card-header">
                        <h3 class="font-semibold text-surface-900">Distribución de Puntuaciones</h3>
                    </div>
                    <div class="card-body">
                        <div class="flex items-end justify-center gap-2 h-40">
                            <div v-for="(score, index) in scoreDistribution" :key="index"
                                class="w-12 rounded-t-lg transition-all duration-500"
                                :class="getBarColor(index)"
                                :style="{ height: score + '%' }">
                                <span class="block text-xs text-white text-center pt-1">{{ getScoreLabel(index) }}</span>
                            </div>
                        </div>
                        <div class="flex justify-between mt-2 text-xs text-surface-500">
                            <span>1.0</span>
                            <span>5.0</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Evaluation History Table -->
            <div v-if="individualReport?.evaluations?.length" class="card">
                <div class="card-header">
                    <h3 class="font-semibold text-surface-900">Historial de Evaluaciones</h3>
                </div>
                <div class="card-body">
                    <div class="table-container">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th class="text-left py-3 px-4 font-semibold">Período</th>
                                    <th class="text-left py-3 px-4 font-semibold">Estado</th>
                                    <th class="text-left py-3 px-4 font-semibold">Puntuación</th>
                                    <th class="text-left py-3 px-4 font-semibold">Fecha</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="evaluation in individualReport.evaluations" :key="evaluation.id" class="border-t">
                                    <td class="py-3 px-4 font-medium text-surface-900">{{ evaluation.period }}</td>
                                    <td class="py-3 px-4">
                                        <span :class="getStatusClass(evaluation.status)" class="badge">
                                            {{ formatStatus(evaluation.status) }}
                                        </span>
                                    </td>
                                    <td class="py-3 px-4">
                                        <div v-if="evaluation.total_score" class="flex items-center gap-2">
                                            <div class="w-16 h-2 bg-surface-200 rounded-full overflow-hidden">
                                                <div class="h-full bg-primary-500 rounded-full" :style="{ width: (evaluation.total_score / 5 * 100) + '%' }"></div>
                                            </div>
                                            <span class="font-semibold text-surface-900">{{ evaluation.total_score }}</span>
                                        </div>
                                        <span v-else class="text-surface-400">N/A</span>
                                    </td>
                                    <td class="py-3 px-4 text-surface-500">{{ formatDate(evaluation.created_at) }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div v-else-if="individualReport" class="card p-8">
                <div class="empty-state">
                    <svg class="empty-state-icon w-16 h-16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    <p class="empty-state-title">Sin evaluaciones</p>
                    <p class="empty-state-description">Este empleado no tiene evaluaciones registradas.</p>
                </div>
            </div>
        </div>

        <!-- Group Report (Admin Only) -->
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

        <!-- Trends Report -->
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
                                    <span class="text-sm font-semibold" :class="getPerformanceTextColor(r.average_score) w-8 text-right">{{ r.average_score }}</span>
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
import { ref, computed } from 'vue';
import { usePage } from '@inertiajs/vue3';
import AppLayout from '../Layouts/AppLayout.vue';

defineOptions({
    layout: AppLayout,
});

const page = usePage();
const isEvaluator = computed(() => page.props.auth?.user?.role === 'evaluator');
const isAdmin = computed(() => page.props.auth?.user?.role === 'administrator');

defineProps({
    employees: { type: Array, default: () => [] },
    individualReport: { type: Object, default: null },
    groupReport: { type: Array, default: () => [] },
    trendsReport: { type: Array, default: () => [] },
});

const activeTab = ref('individual');
const selectedEmployee = ref('');

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

const getInitials = (name) => {
    if (!name) return '?';
    return name.split(' ').map(n => n[0]).slice(0, 2).join('').toUpperCase();
};

const generateIndividual = () => {
    if (selectedEmployee.value) {
        window.location.href = `/reports/individual?employee_id=${selectedEmployee.value}`;
    }
};

const formatDate = (date) => {
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

// Chart helpers
const scoreDistribution = computed(() => {
    if (!individualReport?.evaluations) return [0, 0, 0, 0, 0];
    const dist = [0, 0, 0, 0, 0];
    individualReport.evaluations.forEach(e => {
        if (e.total_score) {
            const idx = Math.min(4, Math.floor(e.total_score) - 1);
            if (idx >= 0) dist[idx]++;
        }
    });
    const max = Math.max(...dist, 1);
    return dist.map(d => (d / max) * 80);
});

const getBarColor = (index) => {
    const colors = ['bg-rose-500', 'bg-amber-500', 'bg-yellow-500', 'bg-primary-500', 'bg-emerald-500'];
    return colors[index];
};

const getScoreLabel = (index) => {
    return (index + 1);
};

// Group report helpers
const sortedGroupReport = computed(() => {
    return [...(groupReport || [])].sort((a, b) => parseFloat(b.average_score) - parseFloat(a.average_score));
});

const overallAverage = computed(() => {
    if (!groupReport?.length) return '0.00';
    const sum = groupReport.reduce((acc, r) => acc + parseFloat(r.average_score || 0), 0);
    return (sum / groupReport.length).toFixed(2);
});

const totalEvaluations = computed(() => {
    return groupReport?.reduce((acc, r) => acc + (r.total_evaluations || 0), 0) || 0;
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

// Trends helpers
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

const trendSummary = computed(() => {
    const evaluations = trendsReport?.reduce((acc, r) => acc + (r.total_evaluations || 0), 0) || 0;
    const avg = trendsReport?.length ? (trendsReport.reduce((acc, r) => acc + parseFloat(r.average_score || 0), 0) / trendsReport.length).toFixed(2) : '0.00';

    let trend = 0;
    if (trendsReport?.length >= 2) {
        const recent = parseFloat(trendsReport[trendsReport.length - 1]?.average_score || 0);
        const older = parseFloat(trendsReport[0]?.average_score || 0);
        trend = (recent - older).toFixed(2);
    }

    return {
        totalEvaluations: evaluations,
        averageScore: avg,
        trend: trend
    };
});
</script>