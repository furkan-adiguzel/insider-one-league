<?php

namespace App\Domain\League\Services;

use App\Data\League\PredictionDTO;

final class PredictionCalculator
{
    public function __construct(
        private readonly MatchSimulator $simulator,
        private readonly int $iterations = 1000,
    ) {}

    /**
     * @param array<int, array{id:int,name:string,power:int}> $teams keyed by id
     * @param array<int, array{teamId:int,points:int,gf:int,ga:int,name:string}> $baseTable keyed by teamId
     * @param array<int, array{home:int,away:int}> $remainingPairs
     * @return PredictionDTO[]
     */
    public function championship(array $teams, array $baseTable, array $remainingPairs): array
    {
        $champCount = [];
        foreach ($teams as $id => $t) $champCount[$id] = 0;

        $ids = array_keys($teams);

        for ($i=0; $i<$this->iterations; $i++) {
            $sim = $baseTable;

            foreach ($remainingPairs as $p) {
                $h = $p['home']; $a = $p['away'];

                [$hs, $as] = $this->simulator->simulateScore((int)$teams[$h]['power'], (int)$teams[$a]['power']);

                $sim[$h]['gf'] += $hs; $sim[$h]['ga'] += $as;
                $sim[$a]['gf'] += $as; $sim[$a]['ga'] += $hs;

                if ($hs > $as) $sim[$h]['points'] += 3;
                elseif ($hs < $as) $sim[$a]['points'] += 3;
                else { $sim[$h]['points'] += 1; $sim[$a]['points'] += 1; }
            }

            $sorted = $ids;
            usort($sorted, function ($a, $b) use ($sim, $teams) {
                $pa = $sim[$a]['points']; $pb = $sim[$b]['points'];
                $gda = $sim[$a]['gf'] - $sim[$a]['ga'];
                $gdb = $sim[$b]['gf'] - $sim[$b]['ga'];
                $gfa = $sim[$a]['gf']; $gfb = $sim[$b]['gf'];

                return
                    [$pb, $gdb, $gfb, $teams[$a]['name']]
                    <=> [$pa, $gda, $gfa, $teams[$b]['name']];
            });

            $champCount[$sorted[0]]++;
        }

        $out = [];
        foreach ($teams as $id => $t) {
            $pct = ($champCount[$id] / max(1, $this->iterations)) * 100.0;
            $out[] = new PredictionDTO((int)$id, (string)$t['name'], round($pct, 1));
        }

        usort($out, fn($a,$b) => $b->championshipPercent <=> $a->championshipPercent);
        return $out;
    }
}
