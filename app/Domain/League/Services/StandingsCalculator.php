<?php

namespace App\Domain\League\Services;

use App\Data\League\StandingsRowDTO;

final class StandingsCalculator
{
    /**
     * @param array<int, array{id:int,name:string}> $teams keyed by id
     * @param array<int, array{home:int,away:int,hs:int,as:int}> $playedMatches
     * @return StandingsRowDTO[]
     */
    public function calculate(array $teams, array $playedMatches): array
    {
        $rows = [];
        foreach ($teams as $id => $t) {
            $rows[$id] = [
                'teamId' => (int)$t['id'],
                'teamName' => (string)$t['name'],
                'played' => 0, 'win' => 0, 'draw' => 0, 'lose' => 0,
                'gf' => 0, 'ga' => 0, 'gd' => 0, 'points' => 0,
            ];
        }

        foreach ($playedMatches as $m) {
            $h = $m['home']; $a = $m['away'];
            $hs = $m['hs'];  $as = $m['as'];

            $rows[$h]['played']++;
            $rows[$a]['played']++;

            $rows[$h]['gf'] += $hs; $rows[$h]['ga'] += $as;
            $rows[$a]['gf'] += $as; $rows[$a]['ga'] += $hs;

            if ($hs > $as) {
                $rows[$h]['win']++;  $rows[$h]['points'] += 3;
                $rows[$a]['lose']++;
            } elseif ($hs < $as) {
                $rows[$a]['win']++;  $rows[$a]['points'] += 3;
                $rows[$h]['lose']++;
            } else {
                $rows[$h]['draw']++; $rows[$h]['points'] += 1;
                $rows[$a]['draw']++; $rows[$a]['points'] += 1;
            }
        }

        foreach ($rows as &$r) $r['gd'] = $r['gf'] - $r['ga'];
        unset($r);

        $list = array_values($rows);

        usort($list, function ($x, $y) {
            return
                [$y['points'], $y['gd'], $y['gf'], $x['teamName']]
                <=> [$x['points'], $x['gd'], $x['gf'], $y['teamName']];
        });

        return array_map(fn($r) => new StandingsRowDTO(
            $r['teamId'], $r['teamName'],
            $r['played'], $r['win'], $r['draw'], $r['lose'],
            $r['gf'], $r['ga'], $r['gd'], $r['points']
        ), $list);
    }
}
