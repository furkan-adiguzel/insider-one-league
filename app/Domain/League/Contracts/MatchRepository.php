<?php

namespace App\Domain\League\Contracts;

use App\Models\GameMatch;

interface MatchRepository
{
    public function deleteByLeague(int $leagueId): void;

    public function create(int $leagueId, int $week, int $homeId, int $awayId): GameMatch;

    /** @return GameMatch[] */
    public function weekMatches(int $leagueId, int $week): array;

    /** @return GameMatch[] */
    public function playedMatches(int $leagueId): array;

    /** @return GameMatch[] */
    public function remainingMatches(int $leagueId): array;

    public function findInLeague(int $leagueId, int $matchId): GameMatch;

    public function update(GameMatch $match, array $data): void;

    /**
     * @return array<int, array<int, array<string,mixed>>> fixtures grouped by week
     */
    public function fixturesGroupedByWeek(int $leagueId): array;
}
