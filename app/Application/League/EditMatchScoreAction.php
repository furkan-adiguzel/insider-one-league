<?php

namespace App\Application\League;

use App\Domain\League\Contracts\LeagueRepository;
use App\Domain\League\Contracts\MatchRepository;
use Carbon\CarbonImmutable;

final class EditMatchScoreAction
{
    public function __construct(
        private readonly LeagueRepository $leagues,
        private readonly MatchRepository $matches,
    ) {}

    public function execute(int $matchId, int $homeScore, int $awayScore): void
    {
        if ($homeScore < 0 || $awayScore < 0 || $homeScore > 20 || $awayScore > 20) {
            abort(422, 'Scores must be between 0 and 20.');
        }

        $league = $this->leagues->getDefault();
        $match = $this->matches->findInLeague((int)$league->id, $matchId);

        $this->matches->update($match, [
            'home_score' => $homeScore,
            'away_score' => $awayScore,
            'is_played' => true,
            'is_edited' => true,
        ]);

        // league state update (optional but consistent)
        if ((int)$match->week > (int)$league->current_week) {
            $league->current_week = (int)$match->week;
        }
        if ((int)$league->current_week >= (int)$league->total_weeks) {
            $league->is_finished = true;
            $league->finished_at = CarbonImmutable::now();
            $league->current_week = (int)$league->total_weeks;
        }

        $this->leagues->save($league);
    }
}
