<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\League\LeagueService;
use App\Services\League\StandingsService;
use App\Services\League\PredictionService;

final class LeagueController extends Controller
{
    public function __construct(
        private readonly LeagueService $league,
        private readonly StandingsService $standings,
        private readonly PredictionService $predictions,
    ) {}

    public function show()
    {
        $league = $this->league->ensureDefaultLeague();

        $rows = $this->standings->standings($league);

        $pred = [];
        // last 3 weeks: when current_week >= total_weeks - 2 (e.g. 4,5,6 for total 6)
        if ((int)$league->current_week >= ((int)$league->total_weeks - 2)) {
            $pred = $this->predictions->championshipPredictions($league, 1000);
        }

        return response()->json([
            'data' => [
                'league' => [
                    'id' => $league->id,
                    'name' => $league->name,
                    'current_week' => $league->current_week,
                    'total_weeks' => $league->total_weeks,
                    'is_started' => $league->is_started,
                    'is_finished' => $league->is_finished,
                ],
                'standings' => $rows,
                'predictions' => $pred,
            ],
        ]);
    }
}
