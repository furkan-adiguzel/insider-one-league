<template>
    <div class="space-y-4">
        <div v-if="playedWeeks.length === 0" class="text-sm text-gray-600">
            No played weeks yet.
        </div>

        <div v-for="w in playedWeeks" :key="w" class="rounded-lg border p-3">
            <div class="font-medium mb-2">Week {{ w }}</div>
            <div class="space-y-1">
                <div v-for="m in fixturesByWeek[w]" :key="m.id" class="flex items-center justify-between text-sm">
                    <div class="truncate">
                        {{ m.home.name }} vs {{ m.away.name }}
                    </div>
                    <div class="tabular-nums font-medium">
                        <span v-if="m.is_played">{{ m.home_score }} - {{ m.away_score }}</span>
                        <span v-else>—</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
import { computed } from 'vue'
import type { FixturesByWeek } from '../../api/types'

const props = defineProps<{ fixturesByWeek: FixturesByWeek }>()

const playedWeeks = computed(() => {
    const weeks = Object.keys(props.fixturesByWeek).map(Number).sort((a, b) => a - b)
    return weeks.filter(w => (props.fixturesByWeek[w] ?? []).some(m => m.is_played))
})
</script>
