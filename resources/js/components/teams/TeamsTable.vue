<template>
    <div class="overflow-auto">
        <table class="min-w-full text-sm">
            <thead class="text-left text-gray-600">
            <tr class="border-b">
                <th class="py-2 pr-3">Name</th>
                <th class="py-2 pr-3">Power</th>
                <th class="py-2 pr-3">Actions</th>
            </tr>
            </thead>

            <tbody>
            <tr v-if="teams.length === 0">
                <td class="py-3 text-gray-600" colspan="3">No teams.</td>
            </tr>

            <tr v-for="t in teams" :key="t.id" class="border-b last:border-b-0">
                <td class="py-2 pr-3">
                    <input
                        class="w-full rounded-lg border border-gray-300 px-2 py-1"
                        v-model="local[t.id].name"
                    />
                </td>

                <td class="py-2 pr-3 w-32">
                    <input
                        type="number"
                        min="1"
                        max="200"
                        class="w-full rounded-lg border border-gray-300 px-2 py-1"
                        v-model.number="local[t.id].power"
                    />
                    <div v-if="!isPowerValid(t.id)" class="mt-1 text-[11px] text-red-600">
                        Power must be between 1 and 200
                    </div>
                </td>

                <td class="py-2 pr-3">
                    <div class="flex gap-2">
                        <button
                            class="cursor-pointer px-3 py-1.5 text-xs font-medium rounded-lg bg-gray-900 text-white hover:bg-gray-800 transition disabled:opacity-50 disabled:cursor-not-allowed"
                            :disabled="!canSave(t.id)"
                            @click="save(t.id)"
                        >
                            Save
                        </button>

                        <button
                            class="cursor-pointer px-3 py-1.5 text-xs font-medium rounded-lg bg-red-50 text-red-600 hover:bg-red-100 transition"
                            @click="$emit('delete', t.id)"
                        >
                            Delete
                        </button>
                    </div>
                </td>
            </tr>
            </tbody>
        </table>
    </div>
</template>

<script setup lang="ts">
import { reactive, watchEffect } from 'vue'
import type { Team } from '../../api/types'

const props = defineProps<{ teams: Team[] }>()
const emit = defineEmits<{
    (e: 'update', payload: { teamId: number; name: string; power: number }): void
    (e: 'delete', teamId: number): void
}>()

const local = reactive<Record<number, { name: string; power: number }>>({})

watchEffect(() => {
    for (const t of props.teams) {
        if (!local[t.id]) local[t.id] = { name: t.name, power: t.power }
        else {
            local[t.id].name = t.name
            local[t.id].power = t.power
        }
    }
})

function isNameValid(teamId: number): boolean {
    const n = (local[teamId]?.name ?? '').trim()
    return n.length > 1
}

function isPowerValid(teamId: number): boolean {
    const p = Number(local[teamId]?.power)
    return Number.isFinite(p) && p >= 1 && p <= 200
}

function canSave(teamId: number): boolean {
    return isNameValid(teamId) && isPowerValid(teamId)
}

function save(teamId: number) {
    if (!canSave(teamId)) return
    const row = local[teamId]
    emit('update', { teamId, name: row.name.trim(), power: Number(row.power) })
}
</script>
