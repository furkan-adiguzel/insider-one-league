<?php

namespace App\Infrastructure\League;

use App\Domain\League\Contracts\LeagueRepository;
use App\Models\League;

final class EloquentLeagueRepository implements LeagueRepository
{
    public function getDefault(): League
    {
        return League::query()->firstOrCreate(['id' => 1], [
            'name' => 'Insider One League',
            'total_weeks' => 6,
            'current_week' => 0,
        ]);
    }

    public function save(League $league): void
    {
        $league->save();
    }
}
