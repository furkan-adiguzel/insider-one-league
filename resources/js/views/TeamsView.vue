<template>
    <div class="space-y-6">
        <UiCard>
            <div class="flex items-center justify-between">
                <div>
                    <div class="font-semibold">Teams</div>
                    <div class="text-sm text-gray-600">Add teams and set their power.</div>
                </div>

                <UiButton :disabled="teams.loading" @click="teams.load">Refresh</UiButton>
            </div>
        </UiCard>

        <div class="grid gap-6 md:grid-cols-2">
            <UiCard>
                <div class="font-semibold mb-3">Add Team</div>
                <TeamForm :busy="teams.loading" @submit="onCreate" />
            </UiCard>

            <UiCard>
                <div class="font-semibold mb-3">Team List</div>

                <div v-if="teams.loading"><UiSpinner>Loading teams…</UiSpinner></div>
                <div v-else>
                    <TeamsTable
                        :teams="teams.teams"
                        @update="onUpdate"
                        @delete="onDelete"
                    />
                </div>

                <div v-if="teams.error" class="text-sm text-red-600 mt-2">{{ teams.error }}</div>
            </UiCard>
        </div>
    </div>
</template>

<script setup lang="ts">
import { onMounted } from 'vue'
import UiCard from '../components/ui/UiCard.vue'
import UiButton from '../components/ui/UiButton.vue'
import UiSpinner from '../components/ui/UiSpinner.vue'
import TeamForm from '../components/teams/TeamForm.vue'
import TeamsTable from '../components/teams/TeamsTable.vue'
import { useTeamsStore } from '../stores/teamsStore'
import { useUiStore } from '../stores/uiStore'

const teams = useTeamsStore()
const ui = useUiStore()

async function onCreate(payload: { name: string; power: number }) {
    try {
        await teams.create(payload.name, payload.power)
        ui.toast('success', 'Team created.')
    } catch (e: any) {
        ui.toast('error', e?.message ?? 'Create failed.')
    }
}

async function onUpdate(payload: { teamId: number; name: string; power: number }) {
    try {
        await teams.update(payload.teamId, { name: payload.name, power: payload.power })
        ui.toast('success', 'Team updated.')
    } catch (e: any) {
        ui.toast('error', e?.message ?? 'Update failed.')
    }
}

async function onDelete(teamId: number) {
    if (!confirm('Delete this team?')) return
    try {
        await teams.remove(teamId)
        ui.toast('success', 'Team deleted.')
    } catch (e: any) {
        ui.toast('error', e?.message ?? 'Delete failed.')
    }
}

onMounted(() => teams.load())
</script>
