<template>
    <div class="flex items-center justify-between gap-4">
        <div>
            <div class="font-semibold">
                {{ league?.name ?? 'League' }}
            </div>
            <div class="text-sm text-gray-600">
                Week: <b>{{ league?.current_week ?? 0 }}</b> / {{ league?.total_weeks ?? 0 }}
                <span v-if="league?.is_finished" class="ml-2 text-green-700">(Finished)</span>
                <span v-else-if="league?.is_started" class="ml-2 text-blue-700">(Running)</span>
                <span v-else class="ml-2 text-gray-700">(Not started)</span>
            </div>
            <div v-if="error" class="text-sm text-red-600 mt-1">{{ error }}</div>
        </div>

        <div class="flex items-center gap-2">
            <span v-if="loading" class="text-sm text-gray-600">Loading…</span>
            <button class="text-sm underline" @click="$emit('refresh')">Refresh</button>
        </div>
    </div>
</template>

<script setup lang="ts">
import type { LeagueInfo } from '../../api/types'

defineProps<{
    league: LeagueInfo | null
    loading: boolean
    error: string | null
}>()

defineEmits<{ (e: 'refresh'): void }>()
</script>
