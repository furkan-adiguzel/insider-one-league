<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\League\LeagueService;
use Illuminate\Http\Request;

final class SimulationController extends Controller
{
    public function __construct(private readonly LeagueService $league) {}

    public function generateFixtures()
    {
        $league = $this->league->ensureDefaultLeague();
        $this->league->generateFixtures($league);

        return response()->json(['ok' => true]);
    }

    public function playNextWeek()
    {
        $league = $this->league->ensureDefaultLeague();
        $week = $this->league->playNextWeek($league);

        return response()->json(['data' => ['current_week' => $week]]);
    }

    public function playAllWeeks()
    {
        $league = $this->league->ensureDefaultLeague();
        $this->league->playAll($league);

        return response()->json(['ok' => true]);
    }

    public function resetAll()
    {
        $league = $this->league->ensureDefaultLeague();
        $this->league->resetLeague($league);

        return response()->json(['ok' => true]);
    }

    public function editMatch(Request $request, int $matchId)
    {
        $league = $this->league->ensureDefaultLeague();

        $data = $request->validate([
            'home_score' => ['required','integer','min:0','max:20'],
            'away_score' => ['required','integer','min:0','max:20'],
        ]);

        $this->league->editMatchScore($league, $matchId, (int)$data['home_score'], (int)$data['away_score']);

        return response()->json(['ok' => true]);
    }
}
