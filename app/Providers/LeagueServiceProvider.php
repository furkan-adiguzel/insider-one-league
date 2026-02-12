<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Domain\League\Contracts\LeagueRepository;
use App\Domain\League\Contracts\TeamRepository;
use App\Domain\League\Contracts\MatchRepository;
use App\Infrastructure\League\EloquentLeagueRepository;
use App\Infrastructure\League\EloquentTeamRepository;
use App\Infrastructure\League\EloquentMatchRepository;

final class LeagueServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(LeagueRepository::class, EloquentLeagueRepository::class);
        $this->app->bind(TeamRepository::class, EloquentTeamRepository::class);
        $this->app->bind(MatchRepository::class, EloquentMatchRepository::class);
    }
}
