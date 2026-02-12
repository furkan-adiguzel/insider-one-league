import { defineStore } from 'pinia'
import { simulationRepository } from '../api/repositories/simulationRepository'

export const useSimulationStore = defineStore('simulation', {
    state: () => ({
        busy: false,
        error: null as string | null,
    }),
    actions: {
        async run<T>(fn: () => Promise<T>) {
            this.busy = true
            this.error = null
            try {
                return await fn()
            } catch (e: any) {
                this.error = e?.message ?? 'Simulation error'
                throw e
            } finally {
                this.busy = false
            }
        },
        generateFixtures() { return this.run(() => simulationRepository.generateFixtures()) },
        playNextWeek() { return this.run(() => simulationRepository.playNextWeek()) },
        playAll() { return this.run(() => simulationRepository.playAll()) },
        reset() { return this.run(() => simulationRepository.reset()) },
        editMatch(matchId: number, home: number, away: number) {
            return this.run(() => simulationRepository.editMatch(matchId, { home_score: home, away_score: away }))
        },
    },
})
