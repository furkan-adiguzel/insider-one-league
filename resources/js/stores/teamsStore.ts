import { defineStore } from 'pinia'
import type { Team } from '../api/types'
import { teamRepository } from '../api/repositories/teamRepository'

export const useTeamsStore = defineStore('teams', {
    state: () => ({
        teams: [] as Team[],
        loading: false,
        error: null as string | null,
    }),
    actions: {
        async load() {
            this.loading = true
            this.error = null
            try {
                this.teams = await teamRepository.list()
            } catch (e: any) {
                this.error = e?.message ?? 'Failed to load teams'
            } finally {
                this.loading = false
            }
        },
        async create(name: string, power: number) {
            const team = await teamRepository.create({ name, power })
            this.teams = [...this.teams, team]
            return team
        },
        async update(teamId: number, payload: Partial<{ name: string; power: number }>) {
            const team = await teamRepository.update(teamId, payload)
            this.teams = this.teams.map(t => (t.id === teamId ? team : t))
            return team
        },
        async remove(teamId: number) {
            await teamRepository.delete(teamId)
            this.teams = this.teams.filter(t => t.id !== teamId)
        },
    },
})
