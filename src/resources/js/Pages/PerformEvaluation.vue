<template>
    <div class="space-y-6 animate-fade-in">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="page-title">Evaluación de {{ evaluation.employee?.name }}</h1>
                <p class="page-subtitle">Período: {{ evaluation.period }}</p>
            </div>
            <span :class="statusClass(evaluation.status)" class="badge">
                {{ formatStatus(evaluation.status) }}
            </span>
        </div>

        <div class="card">
            <div class="card-header">
                <h2 class="font-semibold text-surface-900">Evaluación de Desempeño</h2>
            </div>
            <div class="card-body space-y-6">
                <div v-if="evaluation.selected_competencies?.length" class="space-y-4">
                    <h3 class="text-lg font-semibold text-primary-700 flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        Competencias (30%)
                    </h3>
                    <div class="grid grid-cols-1 gap-4">
                        <div v-for="comp in evaluation.selected_competencies" :key="comp.id"
                            class="border border-surface-200 rounded-xl p-4 bg-surface-50">
                            <div class="flex flex-col sm:flex-row sm:justify-between sm:items-start gap-3 mb-3">
                                <div class="flex-1">
                                    <h4 class="font-semibold text-surface-900">{{ comp.competency?.name }}</h4>
                                    <p class="text-sm text-surface-600">{{ comp.competency?.description }}</p>
                                    <span class="badge badge-primary text-xs mt-2">{{ comp.competency?.category }}</span>
                                </div>
                                <div v-if="isReadOnly" class="text-right">
                                    <div class="text-2xl font-bold text-primary-600">
                                        {{ formatScore(getScore('competency_' + comp.id)) }}
                                    </div>
                                    <div class="text-xs text-surface-500">puntuación</div>
                                </div>
                            </div>
                            <div v-if="!isReadOnly" class="mt-3">
                                <label class="label">Calificación (1-5)</label>
                                <div class="flex items-center gap-4">
                                    <input type="number" step="0.01" min="1" max="5" inputmode="decimal"
                                        :value="getScore('competency_' + comp.id)"
                                        @input="setScore('competency_' + comp.id, $event.target.value)"
                                        class="input w-32" placeholder="1-5" />
                                    <div class="flex-1">
                                        <input type="range" min="1" max="5" step="0.01"
                                            :value="getScore('competency_' + comp.id)"
                                            @input="setScore('competency_' + comp.id, $event.target.value)"
                                            class="w-full h-2 bg-surface-200 rounded-lg appearance-none cursor-pointer" />
                                    </div>
                                </div>
                            </div>
                            <div v-if="getComment('competency_' + comp.id)" class="mt-3 p-3 bg-white rounded-lg border border-surface-100">
                                <label class="text-xs font-medium text-surface-500 mb-1 block">Comentarios</label>
                                <p class="text-sm text-surface-700">{{ getComment('competency_' + comp.id) }}</p>
                            </div>
                            <div v-if="!isReadOnly" class="mt-3">
                                <label class="label">Comentarios (opcional)</label>
                                <textarea :value="getComment('competency_' + comp.id)" @input="setComment('competency_' + comp.id, $event.target.value)"
                                    rows="2" class="input text-sm" placeholder="Comentarios adicionales..."></textarea>
                            </div>
                        </div>
                    </div>
                </div>

                <div v-if="evaluation.selected_indicators?.length" class="space-y-4">
                    <h3 class="text-lg font-semibold text-emerald-700 flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        Indicadores (70%)
                    </h3>
                    <div class="grid grid-cols-1 gap-4">
                        <div v-for="ind in evaluation.selected_indicators" :key="ind.id"
                            class="border border-surface-200 rounded-xl p-4 bg-surface-50">
                            <div class="flex flex-col sm:flex-row sm:justify-between sm:items-start gap-3 mb-3">
                                <div class="flex-1">
                                    <h4 class="font-semibold text-surface-900">{{ ind.indicator?.name }}</h4>
                                    <p class="text-sm text-surface-600">{{ ind.indicator?.description }}</p>
                                    <span class="badge badge-success text-xs mt-2">{{ ind.indicator?.category }}</span>
                                </div>
                                <div v-if="isReadOnly" class="text-right">
                                    <div class="text-2xl font-bold text-emerald-600">
                                        {{ formatScore(getScore('indicator_' + ind.id)) }}
                                    </div>
                                    <div class="text-xs text-surface-500">puntuación</div>
                                </div>
                            </div>
                            <div v-if="!isReadOnly" class="mt-3">
                                <label class="label">Calificación (1-5)</label>
                                <div class="flex items-center gap-4">
                                    <input type="number" step="0.01" min="1" max="5" inputmode="decimal"
                                        :value="getScore('indicator_' + ind.id)"
                                        @input="setScore('indicator_' + ind.id, $event.target.value)"
                                        class="input w-32" placeholder="1-5" />
                                    <div class="flex-1">
                                        <input type="range" min="1" max="5" step="0.01"
                                            :value="getScore('indicator_' + ind.id)"
                                            @input="setScore('indicator_' + ind.id, $event.target.value)"
                                            class="w-full h-2 bg-surface-200 rounded-lg appearance-none cursor-pointer" />
                                    </div>
                                </div>
                            </div>
                            <div v-if="getComment('indicator_' + ind.id)" class="mt-3 p-3 bg-white rounded-lg border border-surface-100">
                                <label class="text-xs font-medium text-surface-500 mb-1 block">Comentarios</label>
                                <p class="text-sm text-surface-700">{{ getComment('indicator_' + ind.id) }}</p>
                            </div>
                            <div v-if="!isReadOnly" class="mt-3">
                                <label class="label">Comentarios (opcional)</label>
                                <textarea :value="getComment('indicator_' + ind.id)" @input="setComment('indicator_' + ind.id, $event.target.value)"
                                    rows="2" class="input text-sm" placeholder="Comentarios adicionales..."></textarea>
                            </div>
                        </div>
                    </div>
                </div>

                <div>
                    <label class="label">Retroalimentación General</label>
                    <textarea v-if="!isReadOnly" v-model="feedback" rows="4" class="input"
                        placeholder="Proporciona retroalimentación general para el empleado..."></textarea>
                    <div v-else class="bg-surface-50 p-4 rounded-xl border border-surface-200">
                        <p v-if="feedback" class="text-sm text-surface-700">{{ feedback }}</p>
                        <p v-else class="text-sm text-surface-400 italic">Sin retroalimentación</p>
                    </div>
                </div>

                <div class="p-4 bg-primary-50 rounded-xl border border-primary-100">
                    <h4 class="font-semibold text-surface-800 mb-3">Vista Previa de Puntuación</h4>
                    <div class="grid grid-cols-3 gap-3">
                        <div class="bg-white p-3 rounded-lg text-center border border-primary-100">
                            <div class="text-2xl font-bold text-primary-600">
                                {{ previewScores.competency.toFixed(2) || '—' }}
                            </div>
                            <div class="text-xs text-surface-500">Competencias (30%)</div>
                        </div>
                        <div class="bg-white p-3 rounded-lg text-center border border-emerald-100">
                            <div class="text-2xl font-bold text-emerald-600">
                                {{ previewScores.indicator.toFixed(2) || '—' }}
                            </div>
                            <div class="text-xs text-surface-500">Indicadores (70%)</div>
                        </div>
                        <div class="bg-white p-3 rounded-lg text-center border border-violet-100">
                            <div class="text-2xl font-bold text-violet-600">
                                {{ previewScores.total.toFixed(2) || '—' }}
                            </div>
                            <div class="text-xs text-surface-500">Puntaje Final /5</div>
                        </div>
                    </div>
                </div>

                <div v-if="evaluation.status === 'completed'" class="p-4 bg-emerald-50 rounded-xl border border-emerald-200">
                    <div class="flex items-center gap-2">
                        <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <div>
                            <h4 class="font-semibold text-emerald-700">Evaluación Completada</h4>
                            <p class="text-sm text-emerald-600">
                                Completada el: {{ formatDate(evaluation.evaluator_completed_date) }}
                            </p>
                        </div>
                    </div>
                </div>

                <div v-if="message.text"
                    :class="['p-4 rounded-xl flex items-center gap-3', message.type === 'success' ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-rose-50 text-rose-700 border border-rose-200']">
                    <svg v-if="message.type === 'success'" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <svg v-else class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    {{ message.text }}
                </div>

                <template v-if="!isReadOnly">
                    <div class="flex flex-col sm:flex-row gap-3">
                        <button type="button" @click="submit('save')" class="btn btn-secondary flex-1">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"></path>
                            </svg>
                            Guardar y Pausar
                        </button>
                        <button type="button" @click="submit('complete')" class="btn btn-primary flex-1" :disabled="form.processing">
                            <svg v-if="form.processing" class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            <span v-else>Completar Evaluación</span>
                        </button>
                    </div>
                    <Link href="/view-evaluations" class="btn btn-ghost w-full justify-center">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                        </svg>
                        Volver a Evaluaciones
                    </Link>
                </template>
                <template v-else>
                    <div class="flex gap-3">
                        <Link v-if="isEvaluator || props.isAdmin" href="/view-evaluations" class="btn btn-primary flex-1 justify-center">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                            </svg>
                            Volver a Evaluaciones
                        </Link>
                        <Link v-else href="/my-evaluations" class="btn btn-primary flex-1 justify-center">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                            </svg>
                            Volver a Mis Evaluaciones
                        </Link>
                    </div>
                </template>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, reactive, computed, onMounted } from 'vue';
import { useForm, router, Link, usePage } from '@inertiajs/vue3';
import AppLayout from '../Layouts/AppLayout.vue';

defineOptions({
    layout: AppLayout,
});

const page = usePage();
const isEmployee = computed(() => page.props.auth?.user?.role === 'employee');
const isEvaluator = computed(() => page.props.auth?.user?.role === 'evaluator');

const props = defineProps({
    evaluation: { type: Object, required: true },
    competencies: { type: Array, default: () => [] },
    indicators: { type: Array, default: () => [] },
    isAdmin: { type: Boolean, default: false },
    isEmployeeEvaluated: { type: Boolean, default: false },
    isEvaluatorOfEvaluation: { type: Boolean, default: false },
});

const form = useForm({});
const message = ref({ type: '', text: '' });
const feedback = ref(props.evaluation.feedback || '');

const scores = ref({});
const comments = ref({});

const normalizeScore = (value) => {
    if (value === null || value === undefined || value === '') {
        return null;
    }

    const normalizedValue = String(value).replace(',', '.');
    const numberValue = Number(normalizedValue);

    if (!Number.isFinite(numberValue)) {
        return null;
    }

    const limitedValue = Math.min(5, Math.max(1, numberValue));

    return Math.round(limitedValue * 100) / 100;
};

const setScore = (key, value) => {
    scores.value[key] = normalizeScore(value);
};

const getScore = (key) => {
    return scores.value[key];
};

const formatScore = (value) => {
    const numberValue = Number(value);

    if (!Number.isFinite(numberValue)) {
        return '—';
    }

    return numberValue.toFixed(2);
};

const setComment = (key, value) => {
    comments.value[key] = value;
};

const getComment = (key) => {
    return comments.value[key] || '';
};

const isReadOnly = computed(() => {
    if (isEmployee.value || props.isAdmin) return true;
    if (props.isEmployeeEvaluated && !props.isEvaluatorOfEvaluation) return true;
    if (props.evaluation.status === 'completed' && isEmployee.value) return true;
    return false;
});

onMounted(() => {
    if (props.evaluation.items) {
        props.evaluation.items.forEach(item => {
            const key = item.item_type + '_' + item.item_id;

            if (item.score !== undefined && item.score !== null) {
                setScore(key, item.score);
            }

            setComment(key, item.comments || '');
        });
    }
});

const previewScores = computed(() => {
    let competencySum = 0;
    let competencyCount = 0;
    let indicatorSum = 0;
    let indicatorCount = 0;

    if (props.evaluation.selected_competencies) {
        props.evaluation.selected_competencies.forEach(comp => {
            const key = 'competency_' + comp.id;
            const score = scores.value[key];
            if (score !== undefined && score !== null && parseFloat(score) >= 1) {
                competencySum += parseFloat(score);
                competencyCount++;
            }
        });
    }

    if (props.evaluation.selected_indicators) {
        props.evaluation.selected_indicators.forEach(ind => {
            const key = 'indicator_' + ind.id;
            const score = scores.value[key];
            if (score !== undefined && score !== null && parseFloat(score) >= 1) {
                indicatorSum += parseFloat(score);
                indicatorCount++;
            }
        });
    }

    const competencyScore = competencyCount > 0 ? competencySum / competencyCount : 0;
    const indicatorScore = indicatorCount > 0 ? indicatorSum / indicatorCount : 0;
    const totalScore = (competencyScore * 0.3) + (indicatorScore * 0.7);

    return {
        competency: competencyScore,
        indicator: indicatorScore,
        total: totalScore
    };
});

const formatDate = (date) => {
    if (!date) return 'N/A';
    const d = new Date(date);
    const day = String(d.getDate()).padStart(2, '0');
    const month = String(d.getMonth() + 1).padStart(2, '0');
    const year = d.getFullYear();
    return `${day}/${month}/${year}`;
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

const submit = (action) => {
    const items = [];

    if (props.evaluation.selected_competencies) {
        props.evaluation.selected_competencies.forEach(comp => {
            const key = 'competency_' + comp.id;
            const score = scores.value[key];
            if (score !== undefined && score !== null) {
                items.push({
                    item_type: 'competency',
                    item_id: comp.id,
                    score: normalizeScore(score),
                    comments: comments.value[key] || null,
                });
            }
        });
    }

    if (props.evaluation.selected_indicators) {
        props.evaluation.selected_indicators.forEach(ind => {
            const key = 'indicator_' + ind.id;
            const score = scores.value[key];
            if (score !== undefined && score !== null) {
                items.push({
                    item_type: 'indicator',
                    item_id: ind.id,
                    score: normalizeScore(score),
                    comments: comments.value[key] || null,
                });
            }
        });
    }

    form.transform((data) => ({
        ...data,
        items: JSON.stringify(items),
        feedback: feedback.value,
        action: action,
    })).put(`/evaluations/${props.evaluation.id}`, {
        onSuccess: () => {
            if (action === 'complete') {
                router.visit('/view-evaluations');
            } else {
                message.value = { type: 'success', text: '¡Progreso guardado exitosamente!' };
            }
        },
        onError: () => {
            message.value = { type: 'error', text: 'Error al guardar la evaluación' };
        },
    });
};
</script>