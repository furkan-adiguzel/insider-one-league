<template>
    <div>
        <div class="flex items-center justify-between">
            <div class="text-xs text-gray-500">Monte Carlo</div>
            <div class="text-sm font-semibold">Predictions</div>
        </div>

        <div v-if="predictions.length === 0" class="mt-2 text-sm text-gray-600">
            Predictions will appear in the last 3 weeks.
        </div>

        <div v-else class="mt-3 space-y-2">
            <div
                v-for="p in predictions"
                :key="p.teamId"
                class="flex items-center justify-between rounded-xl border border-gray-200 px-3 py-2"
            >
                <div class="text-sm font-medium">{{ p.teamName }}</div>
                <div class="text-sm font-semibold tabular-nums">{{ p.championshipPercent.toFixed(1) }}%</div>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
import type { PredictionDTO } from '../../api/types'
defineProps<{ predictions: PredictionDTO[] }>()
</script>
