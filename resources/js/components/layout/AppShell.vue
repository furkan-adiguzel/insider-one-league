<template>
    <div class="min-h-screen">
        <header class="sticky top-0 z-40 border-b border-gray-200/70 bg-white/80 backdrop-blur">
            <div class="mx-auto max-w-6xl px-4 sm:px-6 py-3 flex items-center justify-between gap-4">
                <div class="flex items-center gap-3 min-w-0">
                    <div class="h-10 w-10 rounded-2xl bg-gray-900 text-white flex items-center justify-center font-bold">
                        IO
                    </div>
                    <div class="min-w-0">
                        <div class="font-semibold tracking-tight leading-tight truncate">
                            Insider One – Champions League Sim
                        </div>
                        <div class="text-xs text-gray-500 truncate">
                            Laravel • Service/DTO • Monte Carlo • Vue 3
                        </div>
                    </div>
                </div>

                <nav class="flex items-center gap-2 text-sm">
                    <RouterLink
                        to="/ui/dashboard"
                        class="px-3 py-2 rounded-xl transition"
                        :class="is('/ui/dashboard') ? 'bg-gray-900 text-white' : 'text-gray-700 hover:bg-gray-100'"
                    >
                        Dashboard
                    </RouterLink>
                    <RouterLink
                        to="/ui/teams"
                        class="px-3 py-2 rounded-xl transition"
                        :class="is('/ui/teams') ? 'bg-gray-900 text-white' : 'text-gray-700 hover:bg-gray-100'"
                    >
                        Teams
                    </RouterLink>
                </nav>
            </div>
        </header>

        <main class="mx-auto max-w-6xl px-4 sm:px-6 py-6">
            <RouterView />
        </main>

        <UiToastHost />
    </div>
</template>

<script setup lang="ts">
import { useRoute } from 'vue-router'
import UiToastHost from '../ui/UiToastHost.vue'

const route = useRoute()
const is = (p: string) => route.path === p
</script>
