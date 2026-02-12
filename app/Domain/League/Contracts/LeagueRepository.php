<?php

namespace App\Domain\League\Contracts;

use App\Models\League;

interface LeagueRepository
{
    public function getDefault(): League;
    public function save(League $league): void;
}
