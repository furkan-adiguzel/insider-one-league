<template>
    <button
        type="button"
        class="inline-flex items-center justify-center rounded-lg border px-3 py-2 text-sm font-medium
           disabled:opacity-50 disabled:cursor-not-allowed"
        :class="variantClass"
        :disabled="disabled"
        @click="$emit('click')"
    >
        <slot />
    </button>
</template>

<script setup lang="ts">
const props = defineProps<{
    disabled?: boolean
    variant?: 'primary' | 'default' | 'danger'
}>()

defineEmits<{ (e: 'click'): void }>()

const variantClass =
    props.variant === 'primary'
        ? 'bg-black text-white border-black'
        : props.variant === 'danger'
            ? 'bg-red-600 text-white border-red-600'
            : 'bg-white text-gray-900 border-gray-300'
</script>
