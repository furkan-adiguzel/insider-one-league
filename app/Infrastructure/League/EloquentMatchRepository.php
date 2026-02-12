<?php

namespace App\Infrastructure\League;

use App\Domain\League\Contracts\MatchRepository;
use App\Models\GameMatch;

final class EloquentMatchRepository implements MatchRepository
{
    public function deleteByLeague(int $leagueId): void
    {
        GameMatch::query()->where('league_id', $leagueId)->delete();
    }

    public function create(int $leagueId, int $week, int $homeId, int $awayId): GameMatch
    {
        return GameMatch::query()->create([
            'league_id' => $leagueId,
            'week' => $week,
            'home_team_id' => $homeId,
            'away_team_id' => $awayId,
        ]);
    }

    public function weekMatches(int $leagueId, int $week): array
    {
        return GameMatch::query()
            ->where('league_id', $leagueId)
            ->where('week', $week)
            ->orderBy('id')
            ->get()
            ->all();
    }

    public function playedMatches(int $leagueId): array
    {
        return GameMatch::query()
            ->where('league_id', $leagueId)
            ->where('is_played', true)
            ->get(['home_team_id','away_team_id','home_score','away_score'])
            ->all();
    }

    public function remainingMatches(int $leagueId): array
    {
        return GameMatch::query()
            ->where('league_id', $leagueId)
            ->where('is_played', false)
            ->get(['id','week','home_team_id','away_team_id'])
            ->all();
    }

    public function findInLeague(int $leagueId, int $matchId): GameMatch
    {
        return GameMatch::query()
            ->where('league_id', $leagueId)
            ->where('id', $matchId)
            ->firstOrFail();
    }

    public function update(GameMatch $match, array $data): void
    {
        $match->update($data);
    }

    public function fixturesGroupedByWeek(int $leagueId): array
    {
        $matches = GameMatch::query()
            ->with(['homeTeam:id,name', 'awayTeam:id,name'])
            ->where('league_id', $leagueId)
            ->orderBy('week')
            ->orderBy('id')
            ->get();

        $byWeek = [];
        foreach ($matches as $m) {
            $byWeek[(int)$m->week][] = [
                'id' => (int)$m->id,
                'week' => (int)$m->week,
                'home' => ['id' => (int)$m->home_team_id, 'name' => (string)($m->homeTeam?->name ?? '')],
                'away' => ['id' => (int)$m->away_team_id, 'name' => (string)($m->awayTeam?->name ?? '')],
                'home_score' => $m->home_score,
                'away_score' => $m->away_score,
                'is_played' => (bool)$m->is_played,
                'is_edited' => (bool)$m->is_edited,
            ];
        }

        return $byWeek;
    }
}
