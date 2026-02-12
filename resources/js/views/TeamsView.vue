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
                        @delete="openDelete"
                    />
                </div>

                <div v-if="teams.error" class="text-sm text-red-600 mt-2">{{ teams.error }}</div>
            </UiCard>
        </div>

        <TeamDeleteConfirmModal
            v-if="del.open"
            :team="teamToDelete"
            :busy="del.busy"
            @close="closeDelete"
            @confirm="confirmDelete"
        />
    </div>
</template>

<script setup lang="ts">
import { computed, onMounted, reactive } from 'vue'
import UiCard from '../components/ui/UiCard.vue'
import UiButton from '../components/ui/UiButton.vue'
import UiSpinner from '../components/ui/UiSpinner.vue'
import TeamForm from '../components/teams/TeamForm.vue'
import TeamsTable from '../components/teams/TeamsTable.vue'
import TeamDeleteConfirmModal from '../components/teams/TeamDeleteConfirmModal.vue'

import { useTeamsStore } from '../stores/teamsStore'
import { useUiStore } from '../stores/uiStore'
import { useTeamsActions } from '../composables/useTeamsActions'

const teams = useTeamsStore()
const ui = useUiStore()

const { onCreate, onUpdate, onDelete } = useTeamsActions({
    create: (name, power) => teams.create(name, power),
    update: (id, payload) => teams.update(id, payload),
    remove: (id) => teams.remove(id),
    toast: ui.toast,
})

const del = reactive<{ open: boolean; teamId: number | null; busy: boolean }>({
    open: false,
    teamId: null,
    busy: false,
})

const teamToDelete = computed(() => {
    if (!del.teamId) return null
    return teams.teams.find(t => t.id === del.teamId) ?? null
})

function openDelete(teamId: number) {
    del.open = true
    del.teamId = teamId
}

function closeDelete() {
    if (del.busy) return
    del.open = false
    del.teamId = null
}

async function confirmDelete(teamId: number) {
    del.busy = true
    try {
        await onDelete(teamId)
        del.open = false
        del.teamId = null
    } catch {
    } finally {
        del.busy = false
    }
}

onMounted(() => teams.load())
</script>
