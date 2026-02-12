export type ApiMeta = {
    timestamp: string
    request_id: string
    version: string
}

export type ApiErrorPayload = {
    type: string
    message: string
    code: number
    fields?: Record<string, string[]>
}

export type ApiEnvelope<T> = {
    ok: boolean
    data: T
    error: ApiErrorPayload | null
    meta: ApiMeta
}

// ---- Domain types ----

export type LeagueInfo = {
    id: number
    name: string
    current_week: number
    total_weeks: number
    is_started: boolean
    is_finished: boolean
}

export type StandingsRowDTO = {
    teamId: number
    teamName: string
    played: number
    win: number
    draw: number
    lose: number
    gf: number
    ga: number
    gd: number
    points: number
}

export type PredictionDTO = {
    teamId: number
    teamName: string
    championshipPercent: number
}

export type FixtureTeam = { id: number; name: string }

export type FixtureMatch = {
    id: number
    week: number
    home: FixtureTeam
    away: FixtureTeam
    home_score: number | null
    away_score: number | null
    is_played: boolean
    is_edited: boolean
}

export type FixturesByWeek = Record<number, FixtureMatch[]>

export type LeagueStateDTO = {
    league: LeagueInfo
    standings: StandingsRowDTO[]
    predictions: PredictionDTO[]
    fixturesByWeek: FixturesByWeek
}

export type Team = {
    id: number
    league_id: number
    name: string
    power: number
}
