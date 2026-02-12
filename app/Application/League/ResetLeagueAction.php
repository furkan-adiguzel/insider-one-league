<?php

namespace App\Application\League;

use App\Domain\League\Contracts\LeagueRepository;
use App\Domain\League\Contracts\MatchRepository;
use App\Domain\League\Contracts\TeamRepository;
use Illuminate\Support\Facades\DB;

final class ResetLeagueAction
{
    public function __construct(
        private readonly LeagueRepository $leagues,
        private readonly MatchRepository $matches,
        private readonly TeamRepository $teams,
    ) {}

    public function execute(): void
    {
        $league = $this->leagues->getDefault();
        $leagueId = (int)$league->id;

        DB::transaction(function () use ($league, $leagueId) {
            $this->matches->deleteByLeague($leagueId);

            foreach ($this->teams->allByLeague($leagueId) as $t) {
                $this->teams->delete($leagueId, (int)$t->id);
            }

            $league->current_week = 0;
            $league->total_weeks = 6;
            $league->is_started = false;
            $league->is_finished = false;
            $league->started_at = null;
            $league->finished_at = null;

            $this->leagues->save($league);
        });
    }
}
