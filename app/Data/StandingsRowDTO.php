<?php

namespace App\Data;

final class StandingsRowDTO
{
    public function __construct(
        public readonly int $teamId,
        public readonly string $teamName,
        public readonly int $played,
        public readonly int $win,
        public readonly int $draw,
        public readonly int $lose,
        public readonly int $gf,
        public readonly int $ga,
        public readonly int $gd,
        public readonly int $points,
    ) {}
}
