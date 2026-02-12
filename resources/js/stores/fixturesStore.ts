import { defineStore } from 'pinia'
import type { FixturesByWeek } from '../api/types'
import { matchRepository } from '../api/repositories/matchRepository'

export const useFixturesStore = defineStore('fixtures', {
    state: () => ({
        fixturesByWeek: {} as FixturesByWeek,
        loading: false,
        error: null as string | null,
    }),
    actions: {
        async load() {
            this.loading = true
            this.error = null
            try {
                this.fixturesByWeek = await matchRepository.fixtures()
            } catch (e: any) {
                this.error = e?.message ?? 'Failed to load fixtures'
            } finally {
                this.loading = false
            }
        },
    },
})
