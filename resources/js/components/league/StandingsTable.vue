<template>
    <div class="overflow-auto">
        <table class="min-w-full text-sm">
            <thead class="text-left text-gray-600">
            <tr class="border-b">
                <th class="py-2 pr-3">#</th>
                <th class="py-2 pr-3">Team</th>
                <th class="py-2 pr-3">P</th>
                <th class="py-2 pr-3">W</th>
                <th class="py-2 pr-3">D</th>
                <th class="py-2 pr-3">L</th>
                <th class="py-2 pr-3">GF</th>
                <th class="py-2 pr-3">GA</th>
                <th class="py-2 pr-3">GD</th>
                <th class="py-2 pr-3">Pts</th>
            </tr>
            </thead>

            <tbody>
            <tr v-if="rows.length === 0">
                <td class="py-3 text-gray-600" colspan="10">No standings yet.</td>
            </tr>

            <tr v-for="(r, idx) in rows" :key="r.teamId" class="border-b last:border-b-0">
                <td class="py-2 pr-3">{{ idx + 1 }}</td>
                <td class="py-2 pr-3 font-medium">{{ r.teamName }}</td>
                <td class="py-2 pr-3">{{ r.played }}</td>
                <td class="py-2 pr-3">{{ r.win }}</td>
                <td class="py-2 pr-3">{{ r.draw }}</td>
                <td class="py-2 pr-3">{{ r.lose }}</td>
                <td class="py-2 pr-3">{{ r.gf }}</td>
                <td class="py-2 pr-3">{{ r.ga }}</td>
                <td class="py-2 pr-3">{{ r.gd }}</td>
                <td class="py-2 pr-3 font-semibold">{{ r.points }}</td>
            </tr>
            </tbody>
        </table>
    </div>
</template>

<script setup lang="ts">
import type { StandingsRowDTO } from '../../api/types'
defineProps<{ rows: StandingsRowDTO[] }>()
</script>
