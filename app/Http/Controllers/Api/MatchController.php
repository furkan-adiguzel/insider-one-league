<?php

namespace App\Http\Controllers\Api;

use App\Domain\League\Contracts\LeagueRepository;
use App\Domain\League\Contracts\MatchRepository;
use App\Http\Controllers\Api\Concerns\HandlesApiErrors;
use App\Http\Controllers\Controller;
use Throwable;

final class MatchController extends Controller
{
    use HandlesApiErrors;

    public function __construct(
        private readonly LeagueRepository $leagues,
        private readonly MatchRepository $matches,
    ) {}

    public function fixtures()
    {
        try {
            $league = $this->leagues->getDefault();
            return $this->ok(['ok' => true, 'data' => $this->matches->fixturesGroupedByWeek((int)$league->id)]);
        } catch (Throwable $e) {
            return $this->handle($e);
        }
    }
}
