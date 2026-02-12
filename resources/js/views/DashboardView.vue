<template>
    <div class="space-y-6">
        <UiCard>
            <LeagueHeader
                :league="league.leagueInfo"
                :loading="league.loading"
                :error="league.error"
                @refresh="reloadAll"
            />
        </UiCard>

        <div class="grid gap-6 md:grid-cols-3">
            <UiCard class="md:col-span-1">
                <div class="font-semibold mb-3">Simulation</div>

                <div class="space-y-2">
                    <UiButton :disabled="sim.busy" variant="primary" @click="onGenerate">
                        Generate Fixtures
                    </UiButton>

                    <UiButton :disabled="sim.busy" @click="onPlayNext">
                        Play Next Week
                    </UiButton>

                    <UiButton :disabled="sim.busy" @click="onPlayAll">
                        Play All
                    </UiButton>

                    <UiButton :disabled="sim.busy" variant="danger" @click="onReset">
                        Reset Data
                    </UiButton>

                    <div v-if="sim.busy" class="pt-2">
                        <UiSpinner>Working…</UiSpinner>
                    </div>

                    <div v-if="sim.error" class="text-sm text-red-600 pt-2">
                        {{ sim.error }}
                    </div>

                    <div class="text-xs text-gray-500 pt-2">
                        Note: Predictions show in the last 2 weeks (Monte Carlo).
                    </div>
                </div>
            </UiCard>

            <UiCard class="md:col-span-2">
                <div class="flex items-center justify-between mb-3">
                    <div class="font-semibold">Standings</div>
                    <UiButton :disabled="league.loading" @click="reloadAll">Refresh</UiButton>
                </div>

                <div v-if="league.loading"><UiSpinner>Loading league…</UiSpinner></div>
                <div v-else>
                    <StandingsTable :rows="league.standings" />
                </div>
            </UiCard>
        </div>

        <UiCard>
            <PredictionsPanel :predictions="league.predictions" />
        </UiCard>

        <div class="grid gap-6 md:grid-cols-2">
            <UiCard>
                <div class="font-semibold mb-3">Fixtures</div>
                <FixturesAccordion
                    :fixturesByWeek="league.fixturesByWeek"
                    @edit="openEdit"
                />
            </UiCard>

            <UiCard>
                <div class="font-semibold mb-3">Weekly Results</div>
                <WeekResultsList :fixturesByWeek="league.fixturesByWeek" />
            </UiCard>
        </div>

        <MatchEditModal
            v-if="edit.open"
            :match="edit.match"
            :busy="sim.busy"
            @close="edit.open = false"
            @save="saveEdit"
        />
    </div>
</template>

<script setup lang="ts">
import { reactive, onMounted } from 'vue'
import UiCard from '../components/ui/UiCard.vue'
import UiButton from '../components/ui/UiButton.vue'
import UiSpinner from '../components/ui/UiSpinner.vue'
import LeagueHeader from '../components/league/LeagueHeader.vue'
import StandingsTable from '../components/league/StandingsTable.vue'
import PredictionsPanel from '../components/league/PredictionsPanel.vue'
import FixturesAccordion from '../components/league/FixturesAccordion.vue'
import MatchEditModal from '../components/league/MatchEditModal.vue'
import WeekResultsList from '../components/league/WeekResultsList.vue'

import { useLeagueStore } from '../stores/leagueStore'
import { useSimulationStore } from '../stores/simulationStore'
import { useUiStore } from '../stores/uiStore'
import type { FixtureMatch } from '../api/types'

const league = useLeagueStore()
const sim = useSimulationStore()
const ui = useUiStore()

const edit = reactive<{ open: boolean; match: FixtureMatch | null }>({
    open: false,
    match: null,
})

async function reloadAll() {
    await league.refresh()
}

function openEdit(m: FixtureMatch) {
    edit.open = true
    edit.match = m
}

async function saveEdit(payload: { matchId: number; home: number; away: number }) {
    try {
        await sim.editMatch(payload.matchId, payload.home, payload.away)
        ui.toast('success', 'Match updated.')
        edit.open = false
        edit.match = null
        await reloadAll()
    } catch {
        ui.toast('error', sim.error ?? 'Failed to update match.')
    }
}

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
    try {
        const r = await sim.playNextWeek()
        ui.toast('success', `Played week ${r.current_week}.`)
        await reloadAll()
    } catch {
        ui.toast('error', sim.error ?? 'Play next failed.')
    }
}

async function onPlayAll() {
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

onMounted(async () => {
    await reloadAll()
})
</script>
