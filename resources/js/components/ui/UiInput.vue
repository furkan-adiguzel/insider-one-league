<template>
    <input
        class="w-full rounded-xl border border-gray-200 bg-white px-3.5 py-2.5 text-sm
           outline-none transition focus:ring-2 focus:ring-gray-900/10 focus:border-gray-300"
        :value="modelValue"
        @input="onInput"
        v-bind="attrs"
    />
</template>

<script setup lang="ts">
import { useAttrs } from 'vue'

const attrs = useAttrs()

const props = defineProps<{
    modelValue?: string | number
}>()

const emit = defineEmits<{
    (e: 'update:modelValue', v: string | number): void
}>()

function onInput(e: Event) {
    const el = e.target as HTMLInputElement
    // number input ise numeric döndür, değilse string
    const type = (attrs.type as string | undefined) ?? 'text'
    const v: string | number = type === 'number' ? (el.value === '' ? '' : Number(el.value)) : el.value
    emit('update:modelValue', v)
}
</script>
