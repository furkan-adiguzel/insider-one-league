<?php

namespace App\Domain\League\Contracts;

use App\Models\Team;

interface TeamRepository
{
    /** @return Team[] */
    public function allByLeague(int $leagueId): array;

    public function create(int $leagueId, string $name, int $power): Team;
    public function update(int $leagueId, int $teamId, array $data): Team;
    public function delete(int $leagueId, int $teamId): void;

    /** @return array<int, Team> keyed by id */
    public function mapByLeague(int $leagueId): array;

    public function countByLeague(int $leagueId): int;
}
