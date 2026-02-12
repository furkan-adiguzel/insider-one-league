import { api } from '../client'

export class SimulationRepository {
    generateFixtures(): Promise<{ generated: boolean }> {
        return api.post<{ generated: boolean }>('/api/simulation/generate-fixtures')
    }

    playNextWeek(): Promise<{ current_week: number }> {
        return api.post<{ current_week: number }>('/api/simulation/play-next-week')
    }

    playAll(): Promise<{ played_all: boolean }> {
        return api.post<{ played_all: boolean }>('/api/simulation/play-all')
    }

    reset(): Promise<{ reset: boolean }> {
        return api.post<{ reset: boolean }>('/api/simulation/reset')
    }

    editMatch(matchId: number, payload: { home_score: number; away_score: number }): Promise<{ updated: boolean }> {
        return api.patch<{ updated: boolean }>(`/api/simulation/matches/${matchId}`, payload)
    }
}

export const simulationRepository = new SimulationRepository()
