<?php

namespace App\Application\League;

use App\Domain\League\Contracts\LeagueRepository;
use App\Domain\League\Contracts\MatchRepository;
use App\Domain\League\Contracts\TeamRepository;
use App\Domain\League\Services\MatchSimulator;
use Carbon\CarbonImmutable;
use Illuminate\Support\Facades\DB;

final class PlayNextWeekAction
{
    public function __construct(
        private readonly LeagueRepository $leagues,
        private readonly TeamRepository $teams,
        private readonly MatchRepository $matches,
        private readonly MatchSimulator $simulator,
    ) {}

    public function execute(): int
    {
        $league = $this->leagues->getDefault();
        $leagueId = (int)$league->id;

        if (!$league->is_started) {
            $league->is_started = true;
            $league->started_at = CarbonImmutable::now();
            $this->leagues->save($league);
        }

        if ($league->is_finished) {
            return (int)$league->current_week;
        }

        $nextWeek = (int)$league->current_week + 1;
        if ($nextWeek > (int)$league->total_weeks) {
            $league->is_finished = true;
            $league->finished_at = CarbonImmutable::now();
            $league->current_week = (int)$league->total_weeks;
            $this->leagues->save($league);
            return (int)$league->current_week;
        }

        DB::transaction(function () use ($league, $leagueId, $nextWeek) {
            $weekMatches = $this->matches->weekMatches($leagueId, $nextWeek);
            if (count($weekMatches) === 0) abort(422, "No matches for week {$nextWeek}. Generate fixtures first.");

            $teamMap = $this->teams->mapByLeague($leagueId);

            foreach ($weekMatches as $m) {
                if ($m->is_played) continue;

                $home = $teamMap[(int)$m->home_team_id] ?? null;
                $away = $teamMap[(int)$m->away_team_id] ?? null;
                if (!$home || !$away) abort(500, 'Team not found for match.');

                [$hs, $as] = $this->simulator->simulateScore((int)$home->power, (int)$away->power);

                $this->matches->update($m, [
                    'home_score' => $hs,
                    'away_score' => $as,
                    'is_played' => true,
                    'is_edited' => false,
                ]);
            }

            $league->current_week = $nextWeek;

            if ($nextWeek >= (int)$league->total_weeks) {
                $league->is_finished = true;
                $league->finished_at = CarbonImmutable::now();
                $league->current_week = (int)$league->total_weeks;
            }

            $this->leagues->save($league);
        });

        return (int)$league->fresh()->current_week;
    }
}
