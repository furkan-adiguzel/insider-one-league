<?php

namespace App\Services\League;

use App\Models\GameMatch;
use App\Models\League;
use App\Models\Team;
use Illuminate\Support\Facades\DB;

final class FixtureGenerator
{
    /**
     * Generates double round-robin fixtures.
     * Assumes even team count (4 in this project).
     */
    public function generate(League $league): void
    {
        $teams = Team::query()
            ->where('league_id', $league->id)
            ->orderBy('id')
            ->get(['id'])
            ->pluck('id')
            ->all();

        if (count($teams) < 4) {
            abort(422, 'At least 4 teams are required.');
        }
        if (count($teams) % 2 !== 0) {
            abort(422, 'Team count must be even.');
        }

        DB::transaction(function () use ($league, $teams) {
            GameMatch::query()->where('league_id', $league->id)->delete();

            $singleRound = $this->circleMethod($teams); // weeks => [[home,away],...]
            $totalWeeksSingle = count($singleRound);

            // 1st half: as produced
            $weekNo = 1;
            foreach ($singleRound as $weekPairs) {
                foreach ($weekPairs as [$homeId, $awayId]) {
                    GameMatch::query()->create([
                        'league_id' => $league->id,
                        'week' => $weekNo,
                        'home_team_id' => $homeId,
                        'away_team_id' => $awayId,
                    ]);
                }
                $weekNo++;
            }

            // 2nd half: swap home/away
            foreach ($singleRound as $weekPairs) {
                foreach ($weekPairs as [$homeId, $awayId]) {
                    GameMatch::query()->create([
                        'league_id' => $league->id,
                        'week' => $weekNo,
                        'home_team_id' => $awayId,
                        'away_team_id' => $homeId,
                    ]);
                }
                $weekNo++;
            }

            $league->update([
                'total_weeks' => $totalWeeksSingle * 2,
                'current_week' => 0,
                'is_started' => false,
                'is_finished' => false,
                'started_at' => null,
                'finished_at' => null,
            ]);
        });
    }

    /**
     * Circle method for round-robin scheduling (single round).
     * Returns array weeks => array of pairs [home, away]
     */
    private function circleMethod(array $teamIds): array
    {
        $n = count($teamIds);
        $half = (int)($n / 2);

        $fixed = $teamIds[0];
        $rotating = array_slice($teamIds, 1); // n-1

        $weeks = [];

        for ($round = 0; $round < ($n - 1); $round++) {
            $left = [$fixed, ...array_slice($rotating, 0, $half - 1)];
            $right = array_reverse(array_slice($rotating, $half - 1));

            $pairs = [];
            for ($i = 0; $i < $half; $i++) {
                $home = $left[$i];
                $away = $right[$i];
                // Alternate home/away for fairness
                if ($round % 2 === 1) {
                    [$home, $away] = [$away, $home];
                }
                $pairs[] = [$home, $away];
            }

            $weeks[] = $pairs;

            // rotate: move last of rotating to front
            $last = array_pop($rotating);
            array_unshift($rotating, $last);
        }

        return $weeks;
    }
}
