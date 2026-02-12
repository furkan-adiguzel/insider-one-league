<?php

namespace App\Data;

final class PredictionDTO
{
    public function __construct(
        public readonly int $teamId,
        public readonly string $teamName,
        public readonly float $championshipPercent, // 0..100
    ) {}
}
