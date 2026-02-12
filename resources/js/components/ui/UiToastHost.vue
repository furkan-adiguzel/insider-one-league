<template>
    <div class="fixed bottom-4 right-4 space-y-2">
        <div
            v-for="t in ui.toasts"
            :key="t.id"
            class="rounded-lg border bg-white px-3 py-2 text-sm shadow"
        >
            <div class="font-medium" :class="t.type === 'error' ? 'text-red-600' : t.type === 'success' ? 'text-green-600' : 'text-gray-900'">
                {{ t.type.toUpperCase() }}
            </div>
            <div class="text-gray-700">{{ t.message }}</div>
        </div>
    </div>
</template>

<script setup lang="ts">
import { useUiStore } from '../../stores/uiStore'
const ui = useUiStore()
</script>
