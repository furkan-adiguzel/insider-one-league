<?php

namespace App\Application\League;

use App\Data\League\LeagueStateDTO;
use App\Domain\League\Contracts\LeagueRepository;
use App\Domain\League\Contracts\MatchRepository;
use App\Domain\League\Contracts\TeamRepository;
use App\Domain\League\Services\PredictionCalculator;
use App\Domain\League\Services\StandingsCalculator;

final class GetLeagueStateAction
{
    public function __construct(
        private readonly LeagueRepository $leagues,
        private readonly TeamRepository $teams,
        private readonly MatchRepository $matches,
        private readonly StandingsCalculator $standingsCalc,
        private readonly PredictionCalculator $predictionCalc,
    ) {}

    public function execute(): LeagueStateDTO
    {
        $league = $this->leagues->getDefault();

        $teams = $this->teams->allByLeague((int)$league->id);
        $teamsMap = [];
        foreach ($teams as $t) {
            $teamsMap[(int)$t->id] = ['id'=>(int)$t->id,'name'=>(string)$t->name,'power'=>(int)$t->power];
        }

        $played = $this->matches->playedMatches((int)$league->id);
        $playedArr = [];
        foreach ($played as $m) {
            $playedArr[] = [
                'home' => (int)$m->home_team_id,
                'away' => (int)$m->away_team_id,
                'hs' => (int)$m->home_score,
                'as' => (int)$m->away_score,
            ];
        }

        $standings = $this->standingsCalc->calculate(
            array_map(fn($t) => ['id'=>$t['id'],'name'=>$t['name']], $teamsMap),
            $playedArr
        );

        $predictions = [];
        if ((int)$league->current_week >= ((int)$league->total_weeks - 3)) {
            // base table for MC
            $base = [];
            foreach ($standings as $r) {
                $base[$r->teamId] = [
                    'teamId' => $r->teamId,
                    'name' => $r->teamName,
                    'points' => $r->points,
                    'gf' => $r->gf,
                    'ga' => $r->ga,
                ];
            }

            $remaining = $this->matches->remainingMatches((int)$league->id);
            $pairs = [];
            foreach ($remaining as $m) {
                $pairs[] = ['home'=>(int)$m->home_team_id,'away'=>(int)$m->away_team_id];
            }

            $predictions = $this->predictionCalc->championship($teamsMap, $base, $pairs);
        }

        $fixtures = $this->matches->fixturesGroupedByWeek((int)$league->id);

        return new LeagueStateDTO(
            league: [
                'id' => (int)$league->id,
                'name' => (string)$league->name,
                'current_week' => (int)$league->current_week,
                'total_weeks' => (int)$league->total_weeks,
                'is_started' => (bool)$league->is_started,
                'is_finished' => (bool)$league->is_finished,
            ],
            standings: $standings,
            predictions: $predictions,
            fixturesByWeek: $fixtures,
        );
    }
}
