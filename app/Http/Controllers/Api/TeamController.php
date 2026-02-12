<?php

namespace App\Http\Controllers\Api;

use App\Domain\League\Contracts\LeagueRepository;
use App\Domain\League\Contracts\TeamRepository;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTeamRequest;
use App\Http\Requests\UpdateTeamRequest;

final class TeamController extends Controller
{
    public function __construct(
        private readonly LeagueRepository $leagues,
        private readonly TeamRepository $teams,
    ) {}

    public function index()
    {
        $league = $this->leagues->getDefault();
        return response()->json(['data' => $this->teams->allByLeague((int)$league->id)]);
    }

    public function store(StoreTeamRequest $request)
    {
        $league = $this->leagues->getDefault();
        $team = $this->teams->create((int)$league->id, $request->string('name'), (int)$request->integer('power'));
        return response()->json(['data' => $team], 201);
    }

    public function update(UpdateTeamRequest $request, int $teamId)
    {
        $league = $this->leagues->getDefault();
        $team = $this->teams->update((int)$league->id, $teamId, $request->validated());
        return response()->json(['data' => $team]);
    }

    public function destroy(int $teamId)
    {
        $league = $this->leagues->getDefault();
        $this->teams->delete((int)$league->id, $teamId);
        return response()->json(['ok' => true]);
    }
}
