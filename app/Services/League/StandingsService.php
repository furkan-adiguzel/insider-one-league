<?php

namespace App\Services\League;

use App\Data\StandingsRowDTO;
use App\Models\GameMatch;
use App\Models\League;
use App\Models\Team;

final class StandingsService
{
    /**
     * @return StandingsRowDTO[]
     */
    public function standings(League $league): array
    {
        $teams = Team::query()
            ->where('league_id', $league->id)
            ->get(['id','name'])
            ->keyBy('id');

        $rows = [];
        foreach ($teams as $t) {
            $rows[$t->id] = [
                'teamId' => $t->id,
                'teamName' => $t->name,
                'played' => 0, 'win' => 0, 'draw' => 0, 'lose' => 0,
                'gf' => 0, 'ga' => 0, 'gd' => 0, 'points' => 0,
            ];
        }

        $playedMatches = GameMatch::query()
            ->where('league_id', $league->id)
            ->where('is_played', true)
            ->get(['home_team_id','away_team_id','home_score','away_score']);

        foreach ($playedMatches as $m) {
            $h = (int)$m->home_team_id;
            $a = (int)$m->away_team_id;
            $hs = (int)$m->home_score;
            $as = (int)$m->away_score;

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

        foreach ($rows as &$r) {
            $r['gd'] = $r['gf'] - $r['ga'];
        }
        unset($r);

        // Sort: points desc, gd desc, gf desc, teamName asc
        usort($rows, function ($x, $y) {
            return
                [$y['points'], $y['gd'], $y['gf'], $x['teamName']]
                <=> [$x['points'], $x['gd'], $x['gf'], $y['teamName']];
        });

        return array_map(fn($r) => new StandingsRowDTO(
            $r['teamId'], $r['teamName'],
            $r['played'], $r['win'], $r['draw'], $r['lose'],
            $r['gf'], $r['ga'], $r['gd'], $r['points']
        ), $rows);
    }
}
