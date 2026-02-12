<?php

namespace App\Data\League;

final class PredictionDTO
{
    public function __construct(
        public readonly int $teamId,
        public readonly string $teamName,
        public readonly float $championshipPercent,
    ) {}
}
