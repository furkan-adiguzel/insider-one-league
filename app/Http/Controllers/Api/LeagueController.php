<?php

namespace App\Http\Controllers\Api;

use App\Application\League\GetLeagueStateAction;
use App\Http\Controllers\Api\Concerns\HandlesApiErrors;
use App\Http\Controllers\Controller;
use Throwable;

final class LeagueController extends Controller
{
    use HandlesApiErrors;

    public function __construct(
        private readonly GetLeagueStateAction $getState
    ) {}

    public function show()
    {
        try {
            $state = $this->getState->execute();
            return $this->ok($state);
        } catch (Throwable $e) {
            return $this->handle($e);
        }
    }
}
