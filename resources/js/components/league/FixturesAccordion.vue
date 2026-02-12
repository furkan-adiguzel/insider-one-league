<template>
    <div class="space-y-3">
        <div v-if="weeks.length === 0" class="text-sm text-gray-600">
            No fixtures. Generate fixtures first.
        </div>

        <div v-for="w in weeks" :key="w" class="rounded-lg border">
            <button
                class="cursor-pointer w-full flex items-center justify-between px-3 py-2 text-left"
                @click="toggle(w)"
            >
                <div class="font-medium">Week {{ w }}</div>
                <div class="text-sm text-gray-600">{{ open[w] ? '−' : '+' }}</div>
            </button>

            <div v-if="open[w]" class="border-t p-3 space-y-2">
                <div
                    v-for="m in fixturesByWeek[w]"
                    :key="m.id"
                    class="flex items-center justify-between gap-3 rounded-lg border px-3 py-2"
                >
                    <div class="min-w-0">
                        <div class="text-sm font-medium">
                            {{ m.home.name }} <span class="text-gray-500">vs</span> {{ m.away.name }}
                        </div>
                        <div class="text-xs text-gray-500">
                            {{ m.is_played ? (m.is_edited ? 'Edited' : 'Played') : 'Not played' }}
                        </div>
                    </div>

                    <div class="flex items-center gap-2">
                        <div class="text-sm tabular-nums w-16 text-right">
                            <template v-if="m.is_played">{{ m.home_score }} - {{ m.away_score }}</template>
                            <template v-else>—</template>
                        </div>

                        <button class="cursor-pointer px-3 py-1.5 text-xs font-medium rounded-lg bg-gray-100 text-gray-700 hover:bg-gray-200 transition" @click="$emit('edit', m)">Edit</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
import { reactive, computed } from 'vue'
import type { FixturesByWeek, FixtureMatch } from '../../api/types'

const props = defineProps<{ fixturesByWeek: FixturesByWeek }>()
defineEmits<{ (e: 'edit', m: FixtureMatch): void }>()

const open = reactive<Record<number, boolean>>({})

const weeks = computed(() =>
    Object.keys(props.fixturesByWeek)
        .map(n => Number(n))
        .sort((a, b) => a - b)
)

function toggle(w: number) {
    open[w] = !open[w]
}
</script>
