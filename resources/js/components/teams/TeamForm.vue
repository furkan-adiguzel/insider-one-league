<template>
    <form class="space-y-3" @submit.prevent="submit">
        <div>
            <div class="text-xs text-gray-600 mb-1">Name</div>
            <UiInput v-model="name" placeholder="Team name" />
        </div>

        <div>
            <div class="text-xs text-gray-600 mb-1">Power (1–200)</div>
            <UiInput type="number" min="1" max="200" v-model="power" />
        </div>

        <UiButton :disabled="busy || !canSubmit" variant="primary">Add</UiButton>
    </form>
</template>

<script setup lang="ts">
import { computed, ref } from 'vue'
import UiInput from '../ui/UiInput.vue'
import UiButton from '../ui/UiButton.vue'

const props = defineProps<{ busy: boolean }>()
const emit = defineEmits<{ (e: 'submit', payload: { name: string; power: number }): void }>()

const name = ref('')
const power = ref<number>(80)

const canSubmit = computed(() => name.value.trim().length > 1 && power.value >= 1 && power.value <= 200)

function submit() {
    emit('submit', { name: name.value.trim(), power: Number(power.value) })
    name.value = ''
    power.value = 80
}
</script>
