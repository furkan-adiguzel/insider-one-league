<template>
    <div class="space-y-6">
        <!-- TOP: Title + status -->
        <UiCard>
            <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <div class="text-xs text-gray-500">League</div>
                    <div class="text-2xl font-semibold tracking-tight">
                        {{ league.leagueInfo?.name ?? 'Insider One League' }}
                    </div>

                    <div class="mt-1 text-sm text-gray-600">
                        Week:
                        <span class="font-semibold text-gray-900">{{ league.leagueInfo?.current_week ?? 0 }}</span>
                        /
                        {{ league.leagueInfo?.total_weeks ?? 0 }}

                        <span
                            v-if="league.leagueInfo?.is_finished"
                            class="ml-2 inline-flex items-center rounded-full bg-green-50 px-2 py-0.5 text-xs font-medium text-green-700"
                        >
              Finished
            </span>
                        <span
                            v-else-if="league.leagueInfo?.is_started"
                            class="ml-2 inline-flex items-center rounded-full bg-blue-50 px-2 py-0.5 text-xs font-medium text-blue-700"
                        >
              Running
            </span>
                        <span
                            v-else
                            class="ml-2 inline-flex items-center rounded-full bg-gray-100 px-2 py-0.5 text-xs font-medium text-gray-700"
                        >
              Not started
            </span>
                    </div>

                    <div v-if="league.error" class="mt-2 text-sm text-red-600">
                        {{ league.error }}
                    </div>
                </div>

                <div class="flex flex-wrap items-center gap-2">
                    <UiButton :disabled="league.loading || sim.busy" variant="ghost" @click="reloadAll" size="sm">
                        Refresh
                    </UiButton>

                    <UiButton :disabled="sim.busy" variant="danger" @click="onReset" size="sm">
                        Reset
                    </UiButton>
                </div>
            </div>
        </UiCard>

        <!-- MAIN GRID -->
        <div class="grid gap-6 lg:grid-cols-3">
            <!-- LEFT: Simulation (component) -->
            <SimulationControls
                :busy="sim.busy"
                :error="sim.error"
                :predictions="league.predictions"
                :hasFixtures="league.hasFixtures"
                :isFinished="league.isFinished"
                :canPlayNext="league.canPlayNext"
                :canPlayAll="league.canPlayAll"
                @generate="onGenerate"
                @playNext="onPlayNext"
                @playAll="onPlayAll"
            />

            <!-- RIGHT: Standings -->
            <UiCard class="lg:col-span-2">
                <div class="flex items-end justify-between gap-3">
                    <div>
                        <div class="text-xs text-gray-500">Table</div>
                        <div class="text-lg font-semibold">Standings</div>
                    </div>

                    <UiButton :disabled="league.loading || sim.busy" variant="ghost" size="sm" @click="reloadAll">
                        Refresh
                    </UiButton>
                </div>

                <div class="mt-4" v-if="league.loading">
                    <UiSpinner>Loading league…</UiSpinner>
                </div>
                <div class="mt-4" v-else>
                    <StandingsTable :rows="league.standings" />
                </div>
            </UiCard>
        </div>

        <!-- LOWER GRID -->
        <div class="grid gap-6 lg:grid-cols-2">
            <UiCard>
                <div class="flex items-end justify-between">
                    <div>
                        <div class="text-xs text-gray-500">Schedule</div>
                        <div class="text-lg font-semibold">Fixtures</div>
                    </div>
                </div>

                <div class="mt-4">
                    <FixturesAccordion :fixturesByWeek="league.fixturesByWeek" @edit="openEdit" />
                </div>
            </UiCard>

            <UiCard>
                <div>
                    <div class="text-xs text-gray-500">History</div>
                    <div class="text-lg font-semibold">Weekly Results</div>
                </div>

                <div class="mt-4">
                    <WeekResultsList :fixturesByWeek="league.fixturesByWeek" />
                </div>
            </UiCard>
        </div>

        <MatchEditModal
            v-if="edit.open"
            :match="edit.match!"
            :busy="sim.busy"
            @close="closeEdit"
            @save="saveEdit"
        />
    </div>
</template>

<script setup lang="ts">
import { onMounted } from 'vue'
import UiCard from '../components/ui/UiCard.vue'
import UiButton from '../components/ui/UiButton.vue'
import UiSpinner from '../components/ui/UiSpinner.vue'
import StandingsTable from '../components/league/StandingsTable.vue'
import FixturesAccordion from '../components/league/FixturesAccordion.vue'
import WeekResultsList from '../components/league/WeekResultsList.vue'
import MatchEditModal from '../components/league/MatchEditModal.vue'
import SimulationControls from '../components/league/SimulationControls.vue'

import { useLeagueStore } from '../stores/leagueStore'
import { useSimulationStore } from '../stores/simulationStore'
import { useUiStore } from '../stores/uiStore'
import { useMatchEdit } from '../composables/useMatchEdit'
import type { FixtureMatch } from '../api/types'

const league = useLeagueStore()
const sim = useSimulationStore()
const ui = useUiStore()

async function reloadAll() {
    await league.refresh()
}

const { edit, openEdit, closeEdit, saveEdit } = useMatchEdit({
    toast: ui.toast,
    editMatch: (id, h, a) => sim.editMatch(id, h, a),
    reloadAll,
    getError: () => sim.error,
})

async function onGenerate() {
    try {
        await sim.generateFixtures()
        ui.toast('success', 'Fixtures generated.')
        await reloadAll()
    } catch {
        ui.toast('error', sim.error ?? 'Generate failed.')
    }
}

async function onPlayNext() {
    if (!league.canPlayNext) return
    try {
        const r = await sim.playNextWeek()
        ui.toast('success', `Played week ${r.current_week}.`)
        await reloadAll()
    } catch {
        ui.toast('error', sim.error ?? 'Play next failed.')
    }
}

async function onPlayAll() {
    if (!league.canPlayAll) return
    try {
        await sim.playAll()
        ui.toast('success', 'All weeks played.')
        await reloadAll()
    } catch {
        ui.toast('error', sim.error ?? 'Play all failed.')
    }
}

async function onReset() {
    if (!confirm('Reset all data (teams + matches)?')) return
    try {
        await sim.reset()
        ui.toast('success', 'Reset completed.')
        await reloadAll()
    } catch {
        ui.toast('error', sim.error ?? 'Reset failed.')
    }
}

onMounted(reloadAll)
</script>
