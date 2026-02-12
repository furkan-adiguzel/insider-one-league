<?php

namespace App\Http\Controllers\Api;

use App\Application\League\EditMatchScoreAction;
use App\Application\League\GenerateFixturesAction;
use App\Application\League\PlayAllWeeksAction;
use App\Application\League\PlayNextWeekAction;
use App\Application\League\ResetLeagueAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\EditMatchScoreRequest;

final class SimulationController extends Controller
{
    public function __construct(
        private readonly GenerateFixturesAction $generate,
        private readonly PlayNextWeekAction $playNext,
        private readonly PlayAllWeeksAction $playAll,
        private readonly ResetLeagueAction $reset,
        private readonly EditMatchScoreAction $editScore,
    ) {}

    public function generateFixtures()
    {
        $this->generate->execute();
        return response()->json(['ok' => true]);
    }

    public function playNextWeek()
    {
        $week = $this->playNext->execute();
        return response()->json(['data' => ['current_week' => $week]]);
    }

    public function playAllWeeks()
    {
        $this->playAll->execute();
        return response()->json(['ok' => true]);
    }

    public function resetAll()
    {
        $this->reset->execute();
        return response()->json(['ok' => true]);
    }

    public function editMatch(EditMatchScoreRequest $request, int $matchId)
    {
        $this->editScore->execute(
            $matchId,
            (int)$request->integer('home_score'),
            (int)$request->integer('away_score'),
        );
        // BUG: yukarıdaki away_score iki kez; aşağıdaki doğru versiyon:
    }
}
