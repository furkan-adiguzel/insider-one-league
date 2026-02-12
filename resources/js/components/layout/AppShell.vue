<template>
    <div class="min-h-screen bg-gray-50 text-gray-900">
        <header class="border-b bg-white">
            <div class="mx-auto max-w-6xl px-4 py-3 flex items-center justify-between">
                <div class="font-semibold tracking-tight">Insider One – Champions League Sim</div>
                <nav class="flex gap-4 text-sm">
                    <RouterLink class="hover:underline" to="/ui/dashboard">Dashboard</RouterLink>
                    <RouterLink class="hover:underline" to="/ui/teams">Teams</RouterLink>
                </nav>
            </div>
        </header>

        <main class="mx-auto max-w-6xl px-4 py-6">
            <RouterView />
        </main>

        <UiToastHost />
    </div>
</template>

<script setup lang="ts">
import UiToastHost from '../ui/UiToastHost.vue'
</script>
