import { defineStore } from 'pinia'
import type { LeagueStateDTO } from '../api/types'
import { leagueRepository } from '../api/repositories/leagueRepository'

export const useLeagueStore = defineStore('league', {
    state: () => ({
        state: null as LeagueStateDTO | null,
        loading: false,
        error: null as string | null,
    }),
    getters: {
        leagueInfo: (s) => s.state?.league ?? null,
        standings: (s) => s.state?.standings ?? [],
        predictions: (s) => s.state?.predictions ?? [],
        fixturesByWeek: (s) => s.state?.fixturesByWeek ?? {},
    },
    actions: {
        async refresh() {
            this.loading = true
            this.error = null
            try {
                this.state = await leagueRepository.getState()
            } catch (e: any) {
                this.error = e?.message ?? 'Failed to load league'
            } finally {
                this.loading = false
            }
        },
    },
})
