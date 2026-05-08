<template>
    <div class="space-y-6 animate-fade-in">
        <div>
            <h1 class="page-title">Gestión de Items</h1>
            <p class="page-subtitle">Administra competencias, indicadores y asociaciones</p>
        </div>

        <div class="border-b border-surface-200 overflow-x-auto">
            <div class="flex gap-1 min-w-max">
                <button v-for="tab in tabs" :key="tab.key" @click="activeTab = tab.key"
                    class="tab-button whitespace-nowrap" :class="{ 'tab-button-active': activeTab === tab.key }">
                    {{ tab.label }}
                </button>
            </div>
        </div>

        <div v-if="activeTab === 'competencies'">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <div class="card">
                    <div class="card-header flex justify-between items-center">
                        <span class="font-semibold text-surface-900">Competencias</span>
                        <button @click="openCompetencyModal()" class="btn btn-primary btn-sm">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                            </svg>
                            Agregar
                        </button>
                    </div>
                    <div class="card-body">
                        <div v-if="competencies.length" class="space-y-3">
                            <div v-for="comp in competencies" :key="comp.id" class="border border-surface-200 rounded-lg p-4 hover:border-surface-300 transition-colors">
                                <div class="flex justify-between items-start">
                                    <div class="flex-1">
                                        <h3 class="font-bold text-surface-900">{{ comp.name }}</h3>
                                        <p class="text-sm text-surface-600">{{ comp.description }}</p>
                                        <div class="flex gap-2 mt-2">
                                            <span class="badge badge-primary text-xs">{{ comp.category }}</span>
                                            <span class="badge badge-neutral text-xs">Peso: {{ comp.weight }}</span>
                                        </div>
                                    </div>
                                    <div class="flex gap-1 ml-2">
                                        <button @click="editCompetency(comp)" class="btn btn-ghost btn-sm p-1.5" title="Editar">
                                            <svg class="w-4 h-4 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                            </svg>
                                        </button>
                                        <button @click="confirmDeleteCompetency(comp)" class="btn btn-ghost btn-sm p-1.5" title="Eliminar">
                                            <svg class="w-4 h-4 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <p v-else class="text-surface-500 text-center py-8">No se encontraron competencias.</p>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header">
                        <span class="font-semibold text-surface-900">Asociación Rol-Competencia</span>
                    </div>
                    <div class="card-body">
                        <div class="flex flex-col sm:flex-row gap-3 mb-4">
                            <select v-model="selectedRole" class="select flex-1">
                                <option value="">Seleccionar Rol</option>
                                <option v-for="role in roles" :key="role.id" :value="role.id">{{ role.name }}</option>
                            </select>
                            <select v-model="selectedCompetency" class="select flex-1">
                                <option value="">Seleccionar Competencia</option>
                                <option v-for="comp in competencies" :key="comp.id" :value="comp.id">{{ comp.name }}</option>
                            </select>
                            <button @click="attachRoleCompetency" class="btn btn-primary" :disabled="!selectedRole || !selectedCompetency">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                </svg>
                            </button>
                        </div>
                        <div class="space-y-3">
                            <div v-for="role in rolesWithCompetencies" :key="role.id" class="border border-surface-200 rounded-lg p-3">
                                <h4 class="font-semibold text-surface-900 mb-2">{{ role.name }}</h4>
                                <div class="flex flex-wrap gap-2">
                                    <span v-for="comp in role.competencies" :key="comp.id"
                                        class="inline-flex items-center gap-1 bg-primary-50 text-primary-700 px-3 py-1 rounded-full text-sm">
                                        {{ comp.name }}
                                        <button @click="detachRoleCompetency(role.id, comp.id)" class="hover:text-rose-600 font-bold">&times;</button>
                                    </span>
                                    <span v-if="!role.competencies?.length" class="text-surface-400 text-sm">Sin competencias asignadas</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div v-if="activeTab === 'indicators'">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <div class="card">
                    <div class="card-header flex justify-between items-center">
                        <span class="font-semibold text-surface-900">Indicadores</span>
                        <button @click="openIndicatorModal()" class="btn btn-primary btn-sm">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                            </svg>
                            Agregar
                        </button>
                    </div>
                    <div class="card-body">
                        <div v-if="indicators.length" class="space-y-3">
                            <div v-for="ind in indicators" :key="ind.id" class="border border-surface-200 rounded-lg p-4 hover:border-surface-300 transition-colors">
                                <div class="flex justify-between items-start">
                                    <div class="flex-1">
                                        <h3 class="font-bold text-surface-900">{{ ind.name }}</h3>
                                        <p class="text-sm text-surface-600">{{ ind.description }}</p>
                                        <div class="flex gap-2 mt-2">
                                            <span class="badge badge-success text-xs">{{ ind.category }}</span>
                                            <span class="badge badge-neutral text-xs">Peso: {{ ind.weight }}</span>
                                        </div>
                                    </div>
                                    <div class="flex gap-1 ml-2">
                                        <button @click="editIndicator(ind)" class="btn btn-ghost btn-sm p-1.5" title="Editar">
                                            <svg class="w-4 h-4 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                            </svg>
                                        </button>
                                        <button @click="confirmDeleteIndicator(ind)" class="btn btn-ghost btn-sm p-1.5" title="Eliminar">
                                            <svg class="w-4 h-4 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <p v-else class="text-surface-500 text-center py-8">No se encontraron indicadores.</p>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header">
                        <span class="font-semibold text-surface-900">Asociación Posición-Indicador</span>
                    </div>
                    <div class="card-body">
                        <div class="flex flex-col sm:flex-row gap-3 mb-4">
                            <select v-model="selectedPosition" class="select flex-1">
                                <option value="">Seleccionar Posición</option>
                                <option v-for="pos in positions" :key="pos.id" :value="pos.id">{{ pos.name }}</option>
                            </select>
                            <select v-model="selectedIndicator" class="select flex-1">
                                <option value="">Seleccionar Indicador</option>
                                <option v-for="ind in indicators" :key="ind.id" :value="ind.id">{{ ind.name }}</option>
                            </select>
                            <button @click="attachPositionIndicator" class="btn btn-primary" :disabled="!selectedPosition || !selectedIndicator">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                </svg>
                            </button>
                        </div>
                        <div class="space-y-3">
                            <div v-for="pos in positionsWithIndicators" :key="pos.id" class="border border-surface-200 rounded-lg p-3">
                                <h4 class="font-semibold text-surface-900 mb-2">{{ pos.name }}</h4>
                                <div class="flex flex-wrap gap-2">
                                    <span v-for="ind in pos.indicators" :key="ind.id"
                                        class="inline-flex items-center gap-1 bg-emerald-50 text-emerald-700 px-3 py-1 rounded-full text-sm">
                                        {{ ind.name }}
                                        <button @click="detachPositionIndicator(pos.id, ind.id)" class="hover:text-rose-600 font-bold">&times;</button>
                                    </span>
                                    <span v-if="!pos.indicators?.length" class="text-surface-400 text-sm">Sin indicadores asignados</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div v-if="activeTab === 'matrix'" class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <div class="card">
                <div class="card-header">
                    <span class="font-semibold text-surface-900">Matriz Rol-Competencia</span>
                </div>
                <div class="card-body">
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm">
                            <thead>
                                <tr class="border-b border-surface-200">
                                    <th class="text-left p-3 font-semibold text-surface-700">Rol</th>
                                    <th v-for="comp in competencies" :key="comp.id" class="text-center p-3 font-semibold text-surface-700 min-w-[100px]">
                                        <span class="block text-xs text-surface-500 truncate">{{ comp.name }}</span>
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="role in rolesWithCompetencies" :key="role.id" class="border-b border-surface-100">
                                    <td class="p-3 font-medium text-surface-900">{{ role.name }}</td>
                                    <td v-for="comp in competencies" :key="comp.id" class="text-center p-3">
                                        <span :class="hasCompetency(role, comp.id) ? 'text-emerald-600' : 'text-surface-300'" class="text-lg">
                                            {{ hasCompetency(role, comp.id) ? '✓' : '—' }}
                                        </span>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <span class="font-semibold text-surface-900">Matriz Posición-Indicador</span>
                </div>
                <div class="card-body">
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm">
                            <thead>
                                <tr class="border-b border-surface-200">
                                    <th class="text-left p-3 font-semibold text-surface-700">Posición</th>
                                    <th v-for="ind in indicators" :key="ind.id" class="text-center p-3 font-semibold text-surface-700 min-w-[100px]">
                                        <span class="block text-xs text-surface-500 truncate">{{ ind.name }}</span>
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="pos in positionsWithIndicators" :key="pos.id" class="border-b border-surface-100">
                                    <td class="p-3 font-medium text-surface-900">{{ pos.name }}</td>
                                    <td v-for="ind in indicators" :key="ind.id" class="text-center p-3">
                                        <span :class="hasIndicator(pos, ind.id) ? 'text-emerald-600' : 'text-surface-300'" class="text-lg">
                                            {{ hasIndicator(pos, ind.id) ? '✓' : '—' }}
                                        </span>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Competency Modal -->
        <div v-if="showCompetencyModal" class="modal-overlay" @click.self="closeCompetencyModal">
            <div class="modal animate-scale-in">
                <div class="modal-header">
                    <h3 class="text-lg font-bold text-surface-900">{{ editingCompetency ? 'Editar Competencia' : 'Agregar Competencia' }}</h3>
                    <button @click="closeCompetencyModal" class="btn btn-ghost p-1">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
                <form @submit.prevent="saveCompetency" class="modal-body space-y-4">
                    <div>
                        <label class="label">Nombre</label>
                        <input v-model="competencyForm.name" class="input" placeholder="Nombre de la competencia" required />
                    </div>
                    <div>
                        <label class="label">Categoría</label>
                        <select v-model="competencyForm.category" class="select" required>
                            <option value="">Seleccionar Categoría</option>
                            <option value="Technical">Técnica</option>
                            <option value="Leadership">Liderazgo</option>
                            <option value="Interpersonal">Interpersonal</option>
                            <option value="Strategic">Estratégica</option>
                        </select>
                    </div>
                    <div>
                        <label class="label">Descripción</label>
                        <textarea v-model="competencyForm.description" class="input" rows="3" placeholder="Descripción de la competencia"></textarea>
                    </div>
                    <div>
                        <label class="label">Peso</label>
                        <input v-model.number="competencyForm.weight" type="number" step="0.1" min="0" max="10" class="input" placeholder="Peso (0-10)" required />
                    </div>
                    <div class="flex gap-3 pt-2">
                        <button type="submit" class="btn btn-primary flex-1">{{ editingCompetency ? 'Actualizar' : 'Crear' }}</button>
                        <button type="button" @click="closeCompetencyModal" class="btn btn-secondary flex-1">Cancelar</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Indicator Modal -->
        <div v-if="showIndicatorModal" class="modal-overlay" @click.self="closeIndicatorModal">
            <div class="modal animate-scale-in">
                <div class="modal-header">
                    <h3 class="text-lg font-bold text-surface-900">{{ editingIndicator ? 'Editar Indicador' : 'Agregar Indicador' }}</h3>
                    <button @click="closeIndicatorModal" class="btn btn-ghost p-1">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
                <form @submit.prevent="saveIndicator" class="modal-body space-y-4">
                    <div>
                        <label class="label">Nombre</label>
                        <input v-model="indicatorForm.name" class="input" placeholder="Nombre del indicador" required />
                    </div>
                    <div>
                        <label class="label">Categoría</label>
                        <select v-model="indicatorForm.category" class="select" required>
                            <option value="">Seleccionar Categoría</option>
                            <option value="Performance">Rendimiento</option>
                            <option value="Quality">Calidad</option>
                            <option value="Efficiency">Eficiencia</option>
                            <option value="Innovation">Innovación</option>
                        </select>
                    </div>
                    <div>
                        <label class="label">Descripción</label>
                        <textarea v-model="indicatorForm.description" class="input" rows="3" placeholder="Descripción del indicador"></textarea>
                    </div>
                    <div>
                        <label class="label">Peso</label>
                        <input v-model.number="indicatorForm.weight" type="number" step="0.1" min="0" max="10" class="input" placeholder="Peso (0-10)" required />
                    </div>
                    <div class="flex gap-3 pt-2">
                        <button type="submit" class="btn btn-primary flex-1">{{ editingIndicator ? 'Actualizar' : 'Crear' }}</button>
                        <button type="button" @click="closeIndicatorModal" class="btn btn-secondary flex-1">Cancelar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, computed } from 'vue';
import { router } from '@inertiajs/vue3';
import AppLayout from '../Layouts/AppLayout.vue';

defineOptions({
    layout: AppLayout,
});

const props = defineProps({
    competencies: { type: Array, default: () => [] },
    indicators: { type: Array, default: () => [] },
    roles: { type: Array, default: () => [] },
    positions: { type: Array, default: () => [] },
});

const activeTab = ref('competencies');
const tabs = [
    { key: 'competencies', label: 'Competencias' },
    { key: 'indicators', label: 'Indicadores' },
    { key: 'matrix', label: 'Matriz de Asociaciones' },
];

const selectedRole = ref('');
const selectedCompetency = ref('');
const selectedPosition = ref('');
const selectedIndicator = ref('');

const showCompetencyModal = ref(false);
const showIndicatorModal = ref(false);
const editingCompetency = ref(null);
const editingIndicator = ref(null);

const competencyForm = ref({ name: '', category: '', description: '', weight: 1 });
const indicatorForm = ref({ name: '', category: '', description: '', weight: 1 });

const rolesWithCompetencies = computed(() => {
    return props.roles.map(role => ({
        ...role,
        competencies: props.competencies.filter(comp =>
            role.competencies?.some(rc => rc.id === comp.id)
        )
    }));
});

const positionsWithIndicators = computed(() => {
    return props.positions.map(pos => ({
        ...pos,
        indicators: props.indicators.filter(ind =>
            pos.indicators?.some(pi => pi.id === ind.id)
        )
    }));
});

const hasCompetency = (role, compId) => role.competencies?.some(c => c.id === compId);
const hasIndicator = (pos, indId) => pos.indicators?.some(i => i.id === indId);

const openCompetencyModal = () => {
    editingCompetency.value = null;
    competencyForm.value = { name: '', category: '', description: '', weight: 1 };
    showCompetencyModal.value = true;
};

const openIndicatorModal = () => {
    editingIndicator.value = null;
    indicatorForm.value = { name: '', category: '', description: '', weight: 1 };
    showIndicatorModal.value = true;
};

const editCompetency = (comp) => {
    editingCompetency.value = comp.id;
    competencyForm.value = { ...comp };
    showCompetencyModal.value = true;
};

const editIndicator = (ind) => {
    editingIndicator.value = ind.id;
    indicatorForm.value = { ...ind };
    showIndicatorModal.value = true;
};

const closeCompetencyModal = () => {
    showCompetencyModal.value = false;
    editingCompetency.value = null;
    competencyForm.value = { name: '', category: '', description: '', weight: 1 };
};

const closeIndicatorModal = () => {
    showIndicatorModal.value = false;
    editingIndicator.value = null;
    indicatorForm.value = { name: '', category: '', description: '', weight: 1 };
};

const saveCompetency = () => {
    if (editingCompetency.value) {
        router.put(`/competencies/${editingCompetency.value}`, competencyForm.value);
    } else {
        router.post('/competencies', competencyForm.value);
    }
    closeCompetencyModal();
    window.Toast.fire({ icon: 'success', title: editingCompetency.value ? 'Competencia actualizada' : 'Competencia creada' });
};

const saveIndicator = () => {
    if (editingIndicator.value) {
        router.put(`/indicators/${editingIndicator.value}`, indicatorForm.value);
    } else {
        router.post('/indicators', indicatorForm.value);
    }
    closeIndicatorModal();
    window.Toast.fire({ icon: 'success', title: editingIndicator.value ? 'Indicador actualizado' : 'Indicador creado' });
};

const confirmDeleteCompetency = (comp) => {
    window.Swal.fire({
        title: '¿Eliminar competencia?',
        text: `¿Estás seguro de eliminar "${comp.name}"?`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Eliminar',
        cancelButtonText: 'Cancelar',
        confirmButtonColor: '#f43f5e',
        reverseButtons: true
    }).then((result) => {
        if (result.isConfirmed) {
            router.delete(`/competencies/${comp.id}`);
            window.Toast.fire({ icon: 'success', title: 'Competencia eliminada' });
        }
    });
};

const confirmDeleteIndicator = (ind) => {
    window.Swal.fire({
        title: '¿Eliminar indicador?',
        text: `¿Estás seguro de eliminar "${ind.name}"?`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Eliminar',
        cancelButtonText: 'Cancelar',
        confirmButtonColor: '#f43f5e',
        reverseButtons: true
    }).then((result) => {
        if (result.isConfirmed) {
            router.delete(`/indicators/${ind.id}`);
            window.Toast.fire({ icon: 'success', title: 'Indicador eliminado' });
        }
    });
};

const attachRoleCompetency = () => {
    router.post('/role-competency/attach', {
        role_id: selectedRole.value,
        competency_id: selectedCompetency.value,
    });
    selectedRole.value = '';
    selectedCompetency.value = '';
    window.Toast.fire({ icon: 'success', title: 'Competencia asignada al rol' });
};

const detachRoleCompetency = (roleId, competencyId) => {
    router.post('/role-competency/detach', {
        role_id: roleId,
        competency_id: competencyId,
    });
    window.Toast.fire({ icon: 'success', title: 'Competencia desasignada del rol' });
};

const attachPositionIndicator = () => {
    router.post('/position-indicator/attach', {
        position_id: selectedPosition.value,
        indicator_id: selectedIndicator.value,
    });
    selectedPosition.value = '';
    selectedIndicator.value = '';
    window.Toast.fire({ icon: 'success', title: 'Indicador asignado a la posición' });
};

const detachPositionIndicator = (positionId, indicatorId) => {
    router.post('/position-indicator/detach', {
        position_id: positionId,
        indicator_id: indicatorId,
    });
    window.Toast.fire({ icon: 'success', title: 'Indicador desasignado de la posición' });
};
</script>