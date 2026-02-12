<?php

namespace App\Http\Controllers\Api;

use App\Application\League\GetLeagueStateAction;
use App\Http\Controllers\Controller;

final class LeagueController extends Controller
{
    public function __construct(private readonly GetLeagueStateAction $getState) {}

    public function show()
    {
        $state = $this->getState->execute();
        return response()->json(['data' => $state]);
    }
}
