<?php

namespace App\Services\League;

use App\Models\GameMatch;
use App\Models\Team;

final class MatchSimulator
{
    public function simulate(GameMatch $match, Team $home, Team $away): array
    {
        // Tunable knobs
        $homeAdvantage = 8;        // home boost
        $randomness = 18;          // bigger => more upset chance
        $maxGoals = 6;

        $homeStrength = max(1, $home->power + $homeAdvantage + random_int(-$randomness, $randomness));
        $awayStrength = max(1, $away->power + random_int(-$randomness, $randomness));

        // Expected goals baseline (scale with strength)
        $homeXg = $this->clamp(($homeStrength / 100) * 2.2, 0.2, 3.8);
        $awayXg = $this->clamp(($awayStrength / 100) * 2.0, 0.2, 3.6);

        $homeGoals = min($maxGoals, $this->poisson($homeXg));
        $awayGoals = min($maxGoals, $this->poisson($awayXg));

        return [$homeGoals, $awayGoals];
    }

    private function clamp(float $v, float $min, float $max): float
    {
        return max($min, min($max, $v));
    }

    /**
     * Simple Poisson sampler (Knuth).
     */
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
