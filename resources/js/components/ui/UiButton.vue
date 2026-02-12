<template>
    <button
        type="button"
        class="inline-flex items-center justify-center gap-2 rounded-xl px-3.5 py-2.5 text-sm font-semibold
           transition focus:outline-none focus:ring-2 focus:ring-gray-900/15
           disabled:opacity-50 disabled:cursor-not-allowed"
        :class="cls"
        :disabled="disabled"
        @click="$emit('click')"
    >
        <slot />
    </button>
</template>

<script setup lang="ts">
import { computed } from 'vue'

const props = defineProps<{
    disabled?: boolean
    variant?: 'primary' | 'default' | 'danger' | 'ghost'
    size?: 'sm' | 'md'
}>()

defineEmits<{ (e: 'click'): void }>()

const cls = computed(() => {
    const v = props.variant ?? 'default'
    const size = props.size ?? 'md'
    const sizeCls = size === 'sm' ? 'px-3 py-2 text-sm rounded-lg' : 'px-3.5 py-2.5 text-sm rounded-xl'

    const base = `select-none ${sizeCls}`
    if (v === 'primary') return `${base} bg-gray-900 text-white hover:bg-gray-800`
    if (v === 'danger') return `${base} bg-red-600 text-white hover:bg-red-500`
    if (v === 'ghost') return `${base} bg-transparent text-gray-700 hover:bg-gray-100`
    return `${base} bg-white text-gray-900 border border-gray-200 hover:bg-gray-50`
})
</script>
