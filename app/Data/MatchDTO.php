<?php

namespace App\Data;

final class MatchDTO
{
    public function __construct(
        public readonly int $id,
        public readonly int $week,
        public readonly int $homeTeamId,
        public readonly int $awayTeamId,
        public readonly ?int $homeScore,
        public readonly ?int $awayScore,
        public readonly bool $isPlayed,
        public readonly bool $isEdited,
    ) {}

    public static function fromArray(array $a): self
    {
        return new self(
            (int)$a['id'],
            (int)$a['week'],
            (int)$a['home_team_id'],
            (int)$a['away_team_id'],
            isset($a['home_score']) ? (int)$a['home_score'] : null,
            isset($a['away_score']) ? (int)$a['away_score'] : null,
            (bool)$a['is_played'],
            (bool)$a['is_edited'],
        );
    }
}
