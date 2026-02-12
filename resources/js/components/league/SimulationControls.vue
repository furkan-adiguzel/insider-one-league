<template>
    <UiCard class="lg:col-span-1">
        <div class="flex items-start justify-between gap-3">
            <div>
                <div class="text-xs text-gray-500">Controls</div>
                <div class="text-lg font-semibold">Simulation</div>
            </div>
            <div v-if="busy">
                <UiSpinner>Working…</UiSpinner>
            </div>
        </div>

        <div class="mt-4 grid gap-2">
            <UiButton :disabled="busy" variant="primary" @click="$emit('generate')">
                Generate Fixtures
            </UiButton>

            <UiButton :disabled="busy || !canPlayNext" @click="$emit('playNext')">
                Play Next Week
            </UiButton>

            <UiButton :disabled="busy || !canPlayAll" @click="$emit('playAll')">
                Play All
            </UiButton>

            <div v-if="isFinished" class="mt-1 text-xs text-gray-500">
                Season finished. Reset to play again.
            </div>
            <div v-else-if="!hasFixtures" class="mt-1 text-xs text-gray-500">
                No fixtures yet. Generate fixtures first.
            </div>
        </div>

        <div class="mt-4 rounded-xl border border-gray-200 bg-gray-50 p-3">
            <div class="text-xs text-gray-500">Notes</div>
            <div class="text-sm text-gray-700 mt-1">
                Predictions appear in the last 3 weeks<br /><br />
                Predictions are made using team power, current points, home advantage, etc. using the Monte Carlo system.
            </div>

            <div v-if="error" class="text-sm text-red-600 mt-2">
                {{ error }}
            </div>
        </div>

        <div class="mt-4">
            <PredictionsPanel :predictions="predictions" />
        </div>
    </UiCard>
</template>

<script setup lang="ts">
import UiCard from '../ui/UiCard.vue'
import UiButton from '../ui/UiButton.vue'
import UiSpinner from '../ui/UiSpinner.vue'
import PredictionsPanel from './PredictionsPanel.vue'
import type { PredictionRowDTO } from '../../api/types'

defineProps<{
    busy: boolean
    error: string | null
    predictions: any[] // elindeki tipe göre PredictionRowDTO[] yapabilirsin
    hasFixtures: boolean
    isFinished: boolean
    canPlayNext: boolean
    canPlayAll: boolean
}>()

defineEmits<{
    (e: 'generate'): void
    (e: 'playNext'): void
    (e: 'playAll'): void
}>()
</script>
