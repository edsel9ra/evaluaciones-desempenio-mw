<template>
    <div class="space-y-6 animate-fade-in">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="page-title">Evaluaciones</h1>
                <p class="page-subtitle">Ver y gestionar evaluaciones</p>
            </div>
            <div class="flex gap-2">
                <Link href="/create-evaluation" class="btn btn-primary">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    Nueva Evaluación
                </Link>
            </div>
        </div>

        <div class="card overflow-hidden">
            <div class="table-container">
                <table class="table">
                    <thead>
                        <tr class="hidden lg:table-row">
                            <th class="text-left py-3 px-4 font-semibold">Empleado</th>
                            <th class="text-left py-3 px-4 font-semibold">Período</th>
                            <th class="text-left py-3 px-4 font-semibold">Evaluador</th>
                            <th class="text-left py-3 px-4 font-semibold">Estado</th>
                            <th class="text-left py-3 px-4 font-semibold">Puntuación</th>
                            <th class="text-left py-3 px-4 font-semibold">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="evaluation in evaluations" :key="evaluation.id" class="border-t hover:bg-surface-50">
                            <td class="py-3 px-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-full bg-gradient-to-br from-violet-500 to-violet-600 flex items-center justify-center text-white text-sm font-semibold flex-shrink-0">
                                        {{ getInitials(evaluation.employee?.name) }}
                                    </div>
                                    <div>
                                        <div class="font-medium text-surface-900">{{ evaluation.employee?.name }}</div>
                                        <div class="text-sm text-surface-500 lg:hidden">{{ evaluation.employee?.department }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="py-3 px-4 text-surface-600">{{ evaluation.period }}</td>
                            <td class="py-3 px-4 hidden md:table-cell text-surface-600">{{ evaluation.evaluator?.name }}</td>
                            <td class="py-3 px-4">
                                <span :class="statusClass(evaluation.status)" class="badge">
                                    {{ formatStatus(evaluation.status) }}
                                </span>
                            </td>
                            <td class="py-3 px-4">
                                <div v-if="evaluation.status === 'completed'" class="flex flex-col">
                                    <span class="font-bold text-lg text-surface-900">{{ formatScore(evaluation.total_score) }}<span class="text-sm text-surface-400">/5</span></span>
                                    <span class="text-xs text-surface-500 hidden sm:block">
                                        C: {{ formatScore(evaluation.competency_score) }} /
                                        I: {{ formatScore(evaluation.indicator_score) }}
                                    </span>
                                </div>
                                <span v-else class="text-surface-400">—</span>
                            </td>
                            <td class="py-3 px-4">
                                <button @click="performEvaluation(evaluation.id)" class="btn btn-primary btn-sm">
                                    {{ getButtonLabel(evaluation) }}
                                </button>
                            </td>
                        </tr>
                        <tr v-if="!evaluations.length">
                            <td colspan="6" class="py-8 text-center text-surface-500">
                                <div class="flex flex-col items-center">
                                    <svg class="w-12 h-12 text-surface-300 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                    No se encontraron evaluaciones
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</template>

<script setup>
import { computed } from 'vue';
import { router, usePage, Link } from '@inertiajs/vue3';
import AppLayout from '../Layouts/AppLayout.vue';

defineOptions({
    layout: AppLayout,
});

const page = usePage();
const userRole = computed(() => page.props.auth?.user?.role);

defineProps({
    evaluations: { type: Array, default: () => [] },
});

const getInitials = (name) => {
    if (!name) return '?';
    return name.split(' ').map(n => n[0]).slice(0, 2).join('').toUpperCase();
};

const statusClass = (status) => {
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

const getButtonLabel = (evaluation) => {
    if (evaluation.status === 'completed') return 'Ver';
    return 'Realizar';
};

const performEvaluation = (id) => {
    router.visit(`/evaluations/${id}`);
};

const formatScore = (value) => {
    const numberValue = Number(value);

    if (!Number.isFinite(numberValue)) {
        return '0.00';
    }

    return numberValue.toFixed(2);
};
</script>