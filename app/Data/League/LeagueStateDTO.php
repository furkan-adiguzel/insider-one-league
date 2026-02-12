<?php

namespace App\Data\League;

final class LeagueStateDTO
{
    /**
     * @param StandingsRowDTO[] $standings
     * @param PredictionDTO[] $predictions
     * @param array<int, array<int, array<string,mixed>>> $fixturesByWeek
     */
    public function __construct(
        public readonly array $league,
        public readonly array $standings,
        public readonly array $predictions,
        public readonly array $fixturesByWeek,
    ) {}
}
