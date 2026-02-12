<template>
    <div class="fixed inset-0 bg-black/40 flex items-center justify-center p-4">
        <div class="w-full max-w-md rounded-xl bg-white border p-4">
            <div class="flex items-center justify-between">
                <div class="font-semibold">Edit Match</div>
                <button class="text-sm underline" @click="$emit('close')">Close</button>
            </div>

            <div class="mt-3 text-sm text-gray-700">
                <b>{{ match.home.name }}</b> vs <b>{{ match.away.name }}</b>
                <div class="text-xs text-gray-500">Week {{ match.week }}</div>
            </div>

            <div class="grid grid-cols-2 gap-3 mt-4">
                <div>
                    <div class="text-xs text-gray-600 mb-1">{{ match.home.name }} score</div>
                    <UiInput type="number" min="0" max="20" v-model="home" />
                </div>
                <div>
                    <div class="text-xs text-gray-600 mb-1">{{ match.away.name }} score</div>
                    <UiInput type="number" min="0" max="20" v-model="away" />
                </div>
            </div>

            <div class="flex items-center justify-end gap-2 mt-4">
                <UiButton :disabled="busy" @click="$emit('close')">Cancel</UiButton>
                <UiButton :disabled="busy" variant="primary" @click="save">Save</UiButton>
            </div>

            <div v-if="busy" class="mt-3">
                <UiSpinner>Saving…</UiSpinner>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
import { ref, watch } from 'vue'
import UiInput from '../ui/UiInput.vue'
import UiButton from '../ui/UiButton.vue'
import UiSpinner from '../ui/UiSpinner.vue'
import type { FixtureMatch } from '../../api/types'

const props = defineProps<{ match: FixtureMatch; busy: boolean }>()
const emit = defineEmits<{
    (e: 'close'): void
    (e: 'save', payload: { matchId: number; home: number; away: number }): void
}>()

const home = ref<number>(props.match.home_score ?? 0)
const away = ref<number>(props.match.away_score ?? 0)

watch(
    () => props.match,
    (m) => {
        home.value = m.home_score ?? 0
        away.value = m.away_score ?? 0
    }
)

function save() {
    emit('save', { matchId: props.match.id, home: Number(home.value), away: Number(away.value) })
}
</script>
