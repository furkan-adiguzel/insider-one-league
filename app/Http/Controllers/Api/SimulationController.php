<?php

namespace App\Http\Controllers\Api;

use App\Application\League\EditMatchScoreAction;
use App\Application\League\GenerateFixturesAction;
use App\Application\League\PlayAllWeeksAction;
use App\Application\League\PlayNextWeekAction;
use App\Application\League\ResetLeagueAction;
use App\Http\Controllers\Api\Concerns\HandlesApiErrors;
use App\Http\Controllers\Controller;
use App\Http\Requests\EditMatchScoreRequest;
use Throwable;

final class SimulationController extends Controller
{
    use HandlesApiErrors;

    public function __construct(
        private readonly GenerateFixturesAction $generate,
        private readonly PlayNextWeekAction $playNext,
        private readonly PlayAllWeeksAction $playAll,
        private readonly ResetLeagueAction $reset,
        private readonly EditMatchScoreAction $editScore,
    ) {}

    public function generateFixtures()
    {
        try {
            $this->generate->execute();
            return $this->ok(['ok' => true]);
        } catch (Throwable $e) {
            return $this->handle($e);
        }
    }

    public function playNextWeek()
    {
        try {
            $week = $this->playNext->execute();
            return $this->ok(['ok' => true, 'data' => ['current_week' => $week]]);
        } catch (Throwable $e) {
            return $this->handle($e);
        }
    }

    public function playAllWeeks()
    {
        try {
            $this->playAll->execute();
            return $this->ok(['ok' => true]);
        } catch (Throwable $e) {
            return $this->handle($e);
        }
    }

    public function resetAll()
    {
        try {
            $this->reset->execute();
            return $this->ok(['ok' => true]);
        } catch (Throwable $e) {
            return $this->handle($e);
        }
    }

    public function editMatch(EditMatchScoreRequest $request, int $matchId)
    {
        try {
            $this->editScore->execute(
                $matchId,
                (int)$request->integer('home_score'),
                (int)$request->integer('away_score'),
            );

            return $this->ok(['ok' => true]);
        } catch (Throwable $e) {
            return $this->handle($e);
        }
    }
}
