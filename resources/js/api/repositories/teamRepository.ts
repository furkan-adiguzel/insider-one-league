import { api } from '../client'
import type { Team } from '../types'

export class TeamRepository {
    list(): Promise<Team[]> {
        return api.get<Team[]>('/api/teams')
    }

    create(payload: { name: string; power: number }): Promise<Team> {
        return api.post<Team>('/api/teams', payload)
    }

    update(teamId: number, payload: Partial<{ name: string; power: number }>): Promise<Team> {
        return api.patch<Team>(`/api/teams/${teamId}`, payload)
    }

    delete(teamId: number): Promise<{ deleted: boolean }> {
        return api.delete<{ deleted: boolean }>(`/api/teams/${teamId}`)
    }
}

export const teamRepository = new TeamRepository()
