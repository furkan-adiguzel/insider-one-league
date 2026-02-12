import { api } from '../client'
import type { FixturesByWeek } from '../types'

export class MatchRepository {
    fixtures(): Promise<FixturesByWeek> {
        return api.get<FixturesByWeek>('/api/fixtures')
    }
}

export const matchRepository = new MatchRepository()
