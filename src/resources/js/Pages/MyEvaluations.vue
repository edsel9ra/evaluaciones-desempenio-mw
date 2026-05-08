<template>
    <div class="space-y-6 animate-fade-in">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="page-title">Mis Evaluaciones</h1>
                <p class="page-subtitle">Historial de tus evaluaciones de desempeño</p>
            </div>
        </div>

        <div v-if="evaluations.length" class="space-y-4">
            <div v-for="evaluation in evaluations" :key="evaluation.id"
                class="card card-hover p-5">
                <div class="flex flex-col lg:flex-row lg:justify-between lg:items-start gap-4 mb-4">
                    <div class="flex items-start gap-4">
                        <div class="w-12 h-12 rounded-xl bg-primary-50 flex items-center justify-center flex-shrink-0">
                            <svg class="w-6 h-6 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                        </div>
                        <div>
                            <h3 class="font-semibold text-lg text-surface-900">Período: {{ evaluation.period }}</h3>
                            <p class="text-sm text-surface-500">
                                Evaluado por: <span class="text-surface-700">{{ evaluation.evaluator?.name || 'N/A' }}</span>
                            </p>
                            <p class="text-sm text-surface-400">
                                Fecha: {{ formatDate(evaluation.created_at) }}
                            </p>
                        </div>
                    </div>
                    <span :class="getStatusClass(evaluation.status)" class="badge self-start lg:self-auto">
                        {{ formatStatus(evaluation.status) }}
                    </span>
                </div>

                <div v-if="evaluation.status === 'completed'" class="bg-surface-50 rounded-xl p-4 mb-4">
                    <h4 class="font-semibold mb-3 text-surface-800">Resultados de Evaluación</h4>
                    <div class="grid grid-cols-3 gap-3">
                        <div class="bg-white p-3 rounded-lg text-center border border-surface-100">
                            <div class="text-2xl font-bold text-primary-600">
                                {{ (evaluation.competency_score || 0).toFixed(2) }}
                            </div>
                            <div class="text-xs text-surface-500">Competencias (30%)</div>
                        </div>
                        <div class="bg-white p-3 rounded-lg text-center border border-surface-100">
                            <div class="text-2xl font-bold text-emerald-600">
                                {{ (evaluation.indicator_score || 0).toFixed(2) }}
                            </div>
                            <div class="text-xs text-surface-500">Indicadores (70%)</div>
                        </div>
                        <div class="bg-white p-3 rounded-lg text-center border border-surface-100">
                            <div class="text-2xl font-bold text-violet-600">
                                {{ (evaluation.total_score || 0).toFixed(2) }}
                            </div>
                            <div class="text-xs text-surface-500">Puntaje Final /5</div>
                        </div>
                    </div>
                </div>

                <div v-if="evaluation.feedback" class="p-3 bg-primary-50 rounded-lg mb-4">
                    <h4 class="font-medium text-sm text-primary-800 mb-1">Retroalimentación:</h4>
                    <p class="text-sm text-surface-700">{{ evaluation.feedback }}</p>
                </div>

                <div class="flex gap-2">
                    <button @click="viewDetails(evaluation.id)" class="btn btn-primary btn-sm">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                        </svg>
                        Ver Detalles
                    </button>
                </div>
            </div>
        </div>

        <div v-else class="card p-8">
            <div class="empty-state">
                <svg class="empty-state-icon w-16 h-16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                <p class="empty-state-title">Sin evaluaciones</p>
                <p class="empty-state-description">No tienes evaluaciones asignadas todavía.</p>
            </div>
        </div>
    </div>
</template>

<script setup>
import { router } from '@inertiajs/vue3';
import AppLayout from '../Layouts/AppLayout.vue';

defineOptions({
    layout: AppLayout,
});

const props = defineProps({
    evaluations: { type: Array, default: () => [] },
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

const viewDetails = (id) => {
    router.visit(`/evaluations/${id}`);
};
</script>