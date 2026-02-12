<?php


namespace App\Application\League;

use App\Domain\League\Contracts\LeagueRepository;

final class PlayAllWeeksAction
{
    public function __construct(
        private readonly LeagueRepository   $leagues,
        private readonly PlayNextWeekAction $playNextWeek,
    )
    {
    }

    public function execute(): void
    {
        $league = $this->leagues->getDefault();
        while (!$league->fresh()->is_finished) {
            $this->playNextWeek->execute();
        }
    }
}
