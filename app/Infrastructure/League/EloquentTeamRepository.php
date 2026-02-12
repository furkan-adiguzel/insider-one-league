<?php

namespace App\Infrastructure\League;

use App\Domain\League\Contracts\TeamRepository;
use App\Models\Team;

final class EloquentTeamRepository implements TeamRepository
{
    public function allByLeague(int $leagueId): array
    {
        return Team::query()
            ->where('league_id', $leagueId)
            ->orderBy('id')
            ->get(['id','league_id','name','power'])
            ->all();
    }

    public function mapByLeague(int $leagueId): array
    {
        $teams = Team::query()
            ->where('league_id', $leagueId)
            ->get(['id','league_id','name','power']);

        $map = [];
        foreach ($teams as $t) $map[(int)$t->id] = $t;
        return $map;
    }

    public function countByLeague(int $leagueId): int
    {
        return (int) Team::query()->where('league_id', $leagueId)->count();
    }

    public function create(int $leagueId, string $name, int $power): Team
    {
        return Team::query()->create([
            'league_id' => $leagueId,
            'name' => $name,
            'power' => $power,
        ]);
    }

    public function update(int $leagueId, int $teamId, array $data): Team
    {
        $team = Team::query()
            ->where('league_id', $leagueId)
            ->where('id', $teamId)
            ->firstOrFail();

        $team->update($data);
        return $team;
    }

    public function delete(int $leagueId, int $teamId): void
    {
        Team::query()
            ->where('league_id', $leagueId)
            ->where('id', $teamId)
            ->firstOrFail()
            ->delete();
    }
}
