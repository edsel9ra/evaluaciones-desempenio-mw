<template>
    <div class="min-h-screen bg-surface-50 flex">
        <form id="logout-form" action="/logout" method="POST" class="hidden">
            <input type="hidden" name="_token" :value="csrfToken" />
        </form>

        <aside class="fixed inset-y-0 left-0 w-64 bg-white border-r border-surface-200 flex flex-col z-40 transform transition-transform duration-300"
            :class="{ '-translate-x-full': !sidebarOpen, 'translate-x-0': sidebarOpen }">
            <div class="h-16 flex items-center px-5 border-b border-surface-100">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded-xl bg-gradient-to-br from-primary-500 to-primary-700 flex items-center justify-center shadow-lg shadow-primary-500/30">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                        </svg>
                    </div>
                    <div>
                        <span class="text-sm font-bold text-surface-900">PES</span>
                        <span class="text-xs text-surface-400 block -mt-0.5">Admin</span>
                    </div>
                </div>
            </div>

            <nav class="flex-1 px-3 py-4 space-y-1 overflow-y-auto">
                <Link v-for="item in filteredNavItems" :key="item.path" :href="item.path"
                    class="sidebar-link" :class="{ 'sidebar-link-active': isActive(item.path) }">
                    <component :is="item.icon" class="w-5 h-5" />
                    {{ item.label }}
                </Link>
            </nav>

            <div class="p-3 border-t border-surface-100">
                <div class="card p-3 bg-surface-50 border-surface-200">
                    <div class="flex items-center gap-3">
                        <div class="w-9 h-9 rounded-full bg-gradient-to-br from-violet-500 to-violet-600 flex items-center justify-center text-white text-sm font-semibold">
                            {{ userInitials }}
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-semibold text-surface-900 truncate">{{ user.name }}</p>
                            <p class="text-xs text-surface-500 truncate capitalize">{{ user.role }}</p>
                        </div>
                    </div>
                    <button @click="logout" class="mt-3 w-full btn btn-ghost text-sm justify-start pl-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                        </svg>
                        Cerrar sesión
                    </button>
                </div>
            </div>
        </aside>

        <div class="flex-1 flex flex-col" :class="{ 'ml-64': sidebarOpen }">
            <header class="h-16 bg-white border-b border-surface-200 flex items-center justify-between px-6 sticky top-0 z-30">
                <div class="flex items-center gap-4">
                    <button @click="sidebarOpen = !sidebarOpen" class="btn btn-ghost p-2 lg:hidden">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                        </svg>
                    </button>
                    <div class="hidden lg:block">
                        <h2 class="text-lg font-semibold text-surface-900">{{ pageTitle }}</h2>
                    </div>
                </div>

                <div class="flex items-center gap-3">
                    <div class="hidden md:flex items-center gap-2 px-3 py-1.5 bg-surface-50 rounded-lg border border-surface-200">
                        <svg class="w-4 h-4 text-surface-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                        <span class="text-sm text-surface-600">{{ currentDate }}</span>
                    </div>
                </div>
            </header>

            <main class="flex-1 p-6">
                <slot />
            </main>
        </div>

        <div v-if="sidebarOpen" @click="sidebarOpen = false" class="fixed inset-0 bg-surface-900/50 z-30 lg:hidden"></div>
    </div>
</template>

<script setup>
import { ref, computed, h } from 'vue';
import { Link, usePage } from '@inertiajs/vue3';

const page = usePage();
const user = computed(() => page.props.auth?.user || {});
const csrfToken = computed(() => page.props._token || page.props.csrf_token || '');
const sidebarOpen = ref(true);

const userInitials = computed(() => {
    const name = user.value.name || '';
    return name.split(' ').map(n => n[0]).slice(0, 2).join('').toUpperCase();
});

const currentDate = computed(() => {
    return new Date().toLocaleDateString('es-ES', {
        weekday: 'long',
        year: 'numeric',
        month: 'long',
        day: 'numeric'
    });
});

const isActive = (path) => {
    return page.url.startsWith(path);
};

const pageTitle = computed(() => {
    const path = page.url;
    const titles = {
        '/dashboard': 'Dashboard',
        '/manage-employees': 'Gestión de Empleados',
        '/manage-items': 'Gestión de Items',
        '/create-evaluation': 'Crear Evaluación',
        '/view-evaluations': 'Ver Evaluaciones',
        '/my-evaluations': 'Mis Evaluaciones',
        '/reports': 'Reportes'
    };
    return titles[path] || 'Dashboard';
});

const DashboardIcon = {
    render: () => h('svg', { fill: 'none', stroke: 'currentColor', viewBox: '0 0 24 24' }, [
        h('path', { 'stroke-linecap': 'round', 'stroke-linejoin': 'round', 'stroke-width': '2', d: 'M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6' })
    ])
};

const UsersIcon = {
    render: () => h('svg', { fill: 'none', stroke: 'currentColor', viewBox: '0 0 24 24' }, [
        h('path', { 'stroke-linecap': 'round', 'stroke-linejoin': 'round', 'stroke-width': '2', d: 'M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z' })
    ])
};

const ClipboardIcon = {
    render: () => h('svg', { fill: 'none', stroke: 'currentColor', viewBox: '0 0 24 24' }, [
        h('path', { 'stroke-linecap': 'round', 'stroke-linejoin': 'round', 'stroke-width': '2', d: 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01' })
    ])
};

const ChartIcon = {
    render: () => h('svg', { fill: 'none', stroke: 'currentColor', viewBox: '0 0 24 24' }, [
        h('path', { 'stroke-linecap': 'round', 'stroke-linejoin': 'round', 'stroke-width': '2', d: 'M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z' })
    ])
};

const DocumentIcon = {
    render: () => h('svg', { fill: 'none', stroke: 'currentColor', viewBox: '0 0 24 24' }, [
        h('path', { 'stroke-linecap': 'round', 'stroke-linejoin': 'round', 'stroke-width': '2', d: 'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z' })
    ])
};

const CogIcon = {
    render: () => h('svg', { fill: 'none', stroke: 'currentColor', viewBox: '0 0 24 24' }, [
        h('path', { 'stroke-linecap': 'round', 'stroke-linejoin': 'round', 'stroke-width': '2', d: 'M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z' }),
        h('path', { 'stroke-linecap': 'round', 'stroke-linejoin': 'round', 'stroke-width': '2', d: 'M15 12a3 3 0 11-6 0 3 3 0 016 0z' })
    ])
};

const navItems = [
    { path: '/dashboard', label: 'Dashboard', icon: DashboardIcon, roles: ['administrator', 'evaluator', 'employee'] },
    { path: '/manage-employees', label: 'Gestión de Empleados', icon: UsersIcon, roles: ['administrator'] },
    { path: '/manage-items', label: 'Items de Evaluación', icon: CogIcon, roles: ['administrator'] },
    { path: '/create-evaluation', label: 'Crear Evaluación', icon: ClipboardIcon, roles: ['administrator', 'evaluator'] },
    { path: '/view-evaluations', label: 'Ver Evaluaciones', icon: DocumentIcon, roles: ['administrator', 'evaluator'] },
    { path: '/my-evaluations', label: 'Mis Evaluaciones', icon: DocumentIcon, roles: ['employee'] },
    { path: '/reports', label: 'Reportes', icon: ChartIcon, roles: ['administrator', 'evaluator'] },
];

const filteredNavItems = computed(() => {
    return navItems.filter(item => item.roles.includes(user.value.role));
});

const logout = () => {
    window.Swal.fire({
        title: '¿Cerrar sesión?',
        text: '¿Estás seguro de que quieres salir del sistema?',
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Sí, cerrar sesión',
        cancelButtonText: 'Cancelar',
        confirmButtonColor: '#2563eb',
        reverseButtons: true
    }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById('logout-form').submit();
        }
    });
};
</script>