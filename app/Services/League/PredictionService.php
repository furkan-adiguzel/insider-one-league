<?php

namespace App\Services\League;

use App\Data\League\PredictionDTO;
use App\Models\GameMatch;
use App\Models\League;
use App\Models\Team;

final class PredictionService
{
    public function __construct(
        private readonly StandingsService $standings,
        private readonly MatchSimulator $simulator,
    ) {}

    /**
     * @return PredictionDTO[]
     */
    public function championshipPredictions(League $league, int $iterations = 1000): array
    {
        $teams = Team::query()
            ->where('league_id', $league->id)
            ->get(['id','name','power'])
            ->keyBy('id');

        $remaining = GameMatch::query()
            ->where('league_id', $league->id)
            ->where('is_played', false)
            ->get(['id','week','home_team_id','away_team_id']);

        // Base standings computed from already-played matches
        $baseRows = $this->standings->standings($league);
        $base = [];
        foreach ($baseRows as $r) {
            $base[$r->teamId] = [
                'points' => $r->points,
                'gf' => $r->gf,
                'ga' => $r->ga,
            ];
        }

        // Wins counter for being champion
        $champCount = [];
        foreach ($teams as $t) $champCount[$t->id] = 0;

        for ($i = 0; $i < $iterations; $i++) {
            $sim = $base;

            foreach ($remaining as $m) {
                $home = $teams[(int)$m->home_team_id];
                $away = $teams[(int)$m->away_team_id];

                [$hs, $as] = $this->simScoreFromPowers((int)$home->power, (int)$away->power, true);

                $sim[$home->id]['gf'] += $hs; $sim[$home->id]['ga'] += $as;
                $sim[$away->id]['gf'] += $as; $sim[$away->id]['ga'] += $hs;

                if ($hs > $as) $sim[$home->id]['points'] += 3;
                elseif ($hs < $as) $sim[$away->id]['points'] += 3;
                else { $sim[$home->id]['points'] += 1; $sim[$away->id]['points'] += 1; }
            }

            // Determine champion using tie-breaks
            $sorted = array_keys($sim);
            usort($sorted, function ($a, $b) use ($sim, $teams) {
                $pa = $sim[$a]['points']; $pb = $sim[$b]['points'];
                $gda = $sim[$a]['gf'] - $sim[$a]['ga'];
                $gdb = $sim[$b]['gf'] - $sim[$b]['ga'];
                $gfa = $sim[$a]['gf']; $gfb = $sim[$b]['gf'];

                return
                    [$pb, $gdb, $gfb, $teams[$a]->name]
                    <=> [$pa, $gda, $gfa, $teams[$b]->name];
            });

            $champId = $sorted[0];
            $champCount[$champId]++;
        }

        // to percent
        $out = [];
        foreach ($teams as $t) {
            $pct = ($champCount[$t->id] / max(1, $iterations)) * 100.0;
            $out[] = new PredictionDTO($t->id, $t->name, round($pct, 1));
        }

        // Sort by pct desc
        usort($out, fn($a,$b) => $b->championshipPercent <=> $a->championshipPercent);

        return $out;
    }

    /**
     * Lightweight sim for MC (avoid loading models per iteration).
     */
    private function simScoreFromPowers(int $homePower, int $awayPower, bool $home = true): array
    {
        $homeAdvantage = $home ? 8 : 0;
        $randomness = 18;
        $maxGoals = 6;

        $homeStrength = max(1, $homePower + $homeAdvantage + random_int(-$randomness, $randomness));
        $awayStrength = max(1, $awayPower + random_int(-$randomness, $randomness));

        $homeXg = max(0.2, min(3.8, ($homeStrength / 100) * 2.2));
        $awayXg = max(0.2, min(3.6, ($awayStrength / 100) * 2.0));

        $hs = min($maxGoals, $this->poisson($homeXg));
        $as = min($maxGoals, $this->poisson($awayXg));

        return [$hs, $as];
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
