<template>
    <div class="overflow-auto -mx-2 sm:mx-0">
        <table class="min-w-full text-sm">
            <thead class="text-left text-gray-500">
            <tr class="border-b border-gray-100">
                <th class="py-2 px-2 sm:px-3 font-medium">#</th>
                <th class="py-2 px-2 sm:px-3 font-medium">Team</th>
                <th class="py-2 px-2 sm:px-3 font-medium">P</th>
                <th class="py-2 px-2 sm:px-3 font-medium">W</th>
                <th class="py-2 px-2 sm:px-3 font-medium">D</th>
                <th class="py-2 px-2 sm:px-3 font-medium">L</th>
                <th class="py-2 px-2 sm:px-3 font-medium">GF</th>
                <th class="py-2 px-2 sm:px-3 font-medium">GA</th>
                <th class="py-2 px-2 sm:px-3 font-medium">GD</th>
                <th class="py-2 px-2 sm:px-3 font-medium text-right">Pts</th>
            </tr>
            </thead>

            <tbody>
            <tr v-if="rows.length === 0">
                <td class="py-4 px-3 text-gray-600" colspan="10">No standings yet.</td>
            </tr>

            <tr
                v-for="(r, idx) in rows"
                :key="r.teamId"
                class="border-b border-gray-50 hover:bg-gray-50/60 transition"
            >
                <td class="py-2 px-2 sm:px-3 text-gray-500">{{ idx + 1 }}</td>
                <td class="py-2 px-2 sm:px-3 font-semibold">{{ r.teamName }}</td>
                <td class="py-2 px-2 sm:px-3">{{ r.played }}</td>
                <td class="py-2 px-2 sm:px-3">{{ r.win }}</td>
                <td class="py-2 px-2 sm:px-3">{{ r.draw }}</td>
                <td class="py-2 px-2 sm:px-3">{{ r.lose }}</td>
                <td class="py-2 px-2 sm:px-3">{{ r.gf }}</td>
                <td class="py-2 px-2 sm:px-3">{{ r.ga }}</td>
                <td class="py-2 px-2 sm:px-3">{{ r.gd }}</td>
                <td class="py-2 px-2 sm:px-3 text-right font-bold tabular-nums">{{ r.points }}</td>
            </tr>
            </tbody>
        </table>
    </div>
</template>

<script setup lang="ts">
import type { StandingsRowDTO } from '../../api/types'
defineProps<{ rows: StandingsRowDTO[] }>()
</script>
