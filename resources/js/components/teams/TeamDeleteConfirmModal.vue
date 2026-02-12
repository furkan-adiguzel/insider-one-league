<template>
    <div class="fixed inset-0 bg-black/40 flex items-center justify-center p-4">
        <div class="w-full max-w-md rounded-xl bg-white border p-4">
            <div class="flex items-center justify-between">
                <div class="font-semibold">Delete Team</div>
                <button class="text-sm underline" :disabled="busy" @click="$emit('close')">Close</button>
            </div>

            <div class="mt-3 text-sm text-gray-700">
                Are you sure you want to delete <b>{{ team?.name }}</b>?
                <div class="mt-1 text-xs text-gray-500">This action cannot be undone.</div>
            </div>

            <div class="flex items-center justify-end gap-2 mt-4">
                <UiButton :disabled="busy" @click="$emit('close')">Cancel</UiButton>
                <UiButton :disabled="busy" variant="danger" @click="confirmDelete">Delete</UiButton>
            </div>

            <div v-if="busy" class="mt-3">
                <UiSpinner>Deleting…</UiSpinner>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
import UiButton from '../ui/UiButton.vue'
import UiSpinner from '../ui/UiSpinner.vue'
import type { Team } from '../../api/types'

const props = defineProps<{
    team: Team | null
    busy: boolean
}>()

const emit = defineEmits<{
    (e: 'close'): void
    (e: 'confirm', teamId: number): void
}>()

function confirmDelete() {
    if (!props.team) return
    emit('confirm', props.team.id)
}
</script>
