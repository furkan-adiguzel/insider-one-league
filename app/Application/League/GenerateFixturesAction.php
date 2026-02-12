<?php

namespace App\Application\League;

use App\Domain\League\Contracts\LeagueRepository;
use App\Domain\League\Contracts\MatchRepository;
use App\Domain\League\Contracts\TeamRepository;
use App\Domain\League\Services\FixtureGenerator;
use Illuminate\Support\Facades\DB;

final class GenerateFixturesAction
{
    public function __construct(
        private readonly LeagueRepository $leagues,
        private readonly TeamRepository $teams,
        private readonly MatchRepository $matches,
        private readonly FixtureGenerator $fixtureGen,
    ) {}

    public function execute(): void
    {
        $league = $this->leagues->getDefault();
        $leagueId = (int)$league->id;

        $teamCount = $this->teams->countByLeague($leagueId);
        if ($teamCount < 4) abort(422, 'At least 4 teams are required.');
        if ($teamCount % 2 !== 0) abort(422, 'Team count must be even.');

        $teamMap = $this->teams->mapByLeague($leagueId);
        $ids = array_map('intval', array_keys($teamMap));
        sort($ids);

        $weeks = $this->fixtureGen->generateDoubleRoundRobin($ids);

        DB::transaction(function () use ($league, $leagueId, $weeks) {
            $this->matches->deleteByLeague($leagueId);

            foreach ($weeks as $weekNo => $pairs) {
                foreach ($pairs as $p) {
                    $this->matches->create($leagueId, (int)$weekNo, (int)$p[0], (int)$p[1]);
                }
            }

            $league->current_week = 0;
            $league->total_weeks = count($weeks);
            $league->is_started = false;
            $league->is_finished = false;
            $league->started_at = null;
            $league->finished_at = null;

            $this->leagues->save($league);
        });
    }
}
