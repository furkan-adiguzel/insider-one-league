<?php

namespace App\Http\Controllers\Api;

use App\Domain\League\Contracts\LeagueRepository;
use App\Domain\League\Contracts\MatchRepository;
use App\Http\Controllers\Controller;

final class MatchController extends Controller
{
    public function __construct(
        private readonly LeagueRepository $leagues,
        private readonly MatchRepository $matches,
    ) {}

    public function fixtures()
    {
        $league = $this->leagues->getDefault();
        return response()->json(['data' => $this->matches->fixturesGroupedByWeek((int)$league->id)]);
    }
}
