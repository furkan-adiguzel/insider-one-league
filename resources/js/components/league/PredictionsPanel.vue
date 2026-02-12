<template>
    <div>
        <div class="flex items-center justify-between">
            <div class="font-semibold">Championship Predictions</div>
            <div class="text-xs text-gray-500">Monte Carlo</div>
        </div>

        <div v-if="predictions.length === 0" class="text-sm text-gray-600 mt-2">
            Predictions will appear in the last 2 weeks.
        </div>

        <div v-else class="mt-3 space-y-2">
            <div v-for="p in predictions" :key="p.teamId" class="flex items-center justify-between">
                <div class="text-sm font-medium">{{ p.teamName }}</div>
                <div class="text-sm tabular-nums">{{ p.championshipPercent.toFixed(1) }}%</div>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
import type { PredictionDTO } from '../../api/types'
defineProps<{ predictions: PredictionDTO[] }>()
</script>
