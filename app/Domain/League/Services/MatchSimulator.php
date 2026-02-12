<?php

namespace App\Domain\League\Services;

final class MatchSimulator
{
    public function __construct(
        private readonly int $homeAdvantage = 8,
        private readonly int $randomness = 18,
        private readonly int $maxGoals = 6,
    ) {}

    /** @return array{0:int,1:int} */
    public function simulateScore(int $homePower, int $awayPower): array
    {
        $homeStrength = max(1, $homePower + $this->homeAdvantage + random_int(-$this->randomness, $this->randomness));
        $awayStrength = max(1, $awayPower + random_int(-$this->randomness, $this->randomness));

        $homeXg = $this->clamp(($homeStrength / 100) * 2.2, 0.2, 3.8);
        $awayXg = $this->clamp(($awayStrength / 100) * 2.0, 0.2, 3.6);

        $hs = min($this->maxGoals, $this->poisson($homeXg));
        $as = min($this->maxGoals, $this->poisson($awayXg));

        return [$hs, $as];
    }

    private function clamp(float $v, float $min, float $max): float
    {
        return max($min, min($max, $v));
    }

    private function poisson(float $lambda): int
    {
        $L = exp(-$lambda);
        $k = 0;
        $p = 1.0;

        do {
            $k++;
            $p *= mt_rand() / mt_getrandmax();
        } while ($p > $L);

        return $k - 1;
    }
}
