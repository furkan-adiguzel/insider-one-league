import { api } from '../client'
import type { LeagueStateDTO } from '../types'

export class LeagueRepository {
    getState(): Promise<LeagueStateDTO> {
        return api.get<LeagueStateDTO>('/api/league')
    }
}

export const leagueRepository = new LeagueRepository()
