<template>
    <div class="min-h-screen flex">
        <div class="hidden lg:flex lg:w-1/2 bg-gradient-to-br from-surface-900 via-surface-800 to-primary-900 relative overflow-hidden">
            <div class="absolute inset-0 opacity-20">
                <div class="absolute top-0 -left-40 w-80 h-80 bg-primary-500 rounded-full mix-blend-multiply filter blur-3xl animate-pulse"></div>
                <div class="absolute top-1/3 -right-20 w-96 h-96 bg-primary-400 rounded-full mix-blend-multiply filter blur-3xl animate-pulse" style="animation-delay: 1s;"></div>
                <div class="absolute -bottom-20 left-1/3 w-72 h-72 bg-primary-600 rounded-full mix-blend-multiply filter blur-3xl animate-pulse" style="animation-delay: 2s;"></div>
            </div>
            <div class="relative z-10 flex flex-col justify-center px-12 lg:px-20">
                <div class="flex items-center gap-4 mb-8">
                    <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-primary-400 to-primary-600 flex items-center justify-center shadow-2xl shadow-primary-500/40">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                        </svg>
                    </div>
                    <div>
                        <h1 class="text-3xl font-bold text-white">Performance Evaluation</h1>
                        <p class="text-primary-200 text-sm">Sistema de Gestión</p>
                    </div>
                </div>
                <h2 class="text-4xl font-bold text-white mb-4 leading-tight">
                    Evalúa el talento de tu organización
                </h2>
                <p class="text-surface-300 text-lg max-w-md mb-8">
                    Administra evaluaciones de desempeño, registra retroalimentación y potencia el crecimiento de tu equipo.
                </p>
                <div class="flex gap-4">
                    <div class="flex items-center gap-2 text-surface-300 text-sm">
                        <svg class="w-5 h-5 text-primary-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        Evaluaciones trimestrales
                    </div>
                    <div class="flex items-center gap-2 text-surface-300 text-sm">
                        <svg class="w-5 h-5 text-primary-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        Reportes detallados
                    </div>
                </div>
            </div>
        </div>

        <div class="w-full lg:w-1/2 flex items-center justify-center p-8 bg-surface-50">
            <div class="w-full max-w-md animate-fade-in">
                <div class="lg:hidden flex items-center gap-3 mb-8 justify-center">
                    <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-primary-500 to-primary-700 flex items-center justify-center shadow-lg">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                        </svg>
                    </div>
                    <span class="text-xl font-bold text-surface-900">PES</span>
                </div>

                <div class="card p-8 shadow-xl border-surface-100">
                    <div class="text-center mb-8">
                        <h1 class="text-2xl font-bold text-surface-900 mb-2">Bienvenido de nuevo</h1>
                        <p class="text-surface-500">Ingresa tus credenciales para continuar</p>
                    </div>

                    <form @submit.prevent="submit" class="space-y-5">
                        <div>
                            <label class="label">Usuario</label>
                            <input v-model="form.username" type="text" class="input" :class="{ 'input-error': errors.username }"
                                placeholder="Ingresa tu usuario" required />
                            <p v-if="errors.username" class="text-rose-500 text-xs mt-1.5">{{ errors.username }}</p>
                        </div>

                        <div>
                            <label class="label">Contraseña</label>
                            <div class="relative">
                                <input v-model="form.password" :type="showPassword ? 'text' : 'password'" class="input pr-10" :class="{ 'input-error': errors.password }"
                                    placeholder="Ingresa tu contraseña" required />
                                <button type="button" @click="showPassword = !showPassword" class="absolute right-3 top-1/2 -translate-y-1/2 text-surface-400 hover:text-surface-600">
                                    <svg v-if="!showPassword" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                    </svg>
                                    <svg v-else class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"></path>
                                    </svg>
                                </button>
                            </div>
                            <p v-if="errors.password" class="text-rose-500 text-xs mt-1.5">{{ errors.password }}</p>
                        </div>

                        <button type="submit" class="w-full btn btn-primary btn-lg" :disabled="processing">
                            <svg v-if="processing" class="w-5 h-5 animate-spin" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            <span v-else>Iniciar sesión</span>
                        </button>
                    </form>

                    <div class="mt-6 pt-6 border-t border-surface-100 text-center">
                        <p class="text-sm text-surface-500">
                            ¿Necesitas ayuda? Contacta al administrador del sistema
                        </p>
                    </div>
                </div>

                <p class="text-center text-surface-400 text-xs mt-6">
                    © 2026 Performance Evaluation System
                </p>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref } from 'vue';
import { useForm } from '@inertiajs/vue3';

const showPassword = ref(false);

const form = useForm({
    username: '',
    password: '',
});

const errors = form.errors;
const processing = form.processing;

const submit = () => {
    form.post('/login');
};
</script>