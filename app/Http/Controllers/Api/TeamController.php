<?php

namespace App\Http\Controllers\Api;

use App\Domain\League\Contracts\LeagueRepository;
use App\Domain\League\Contracts\TeamRepository;
use App\Http\Controllers\Api\Concerns\HandlesApiErrors;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTeamRequest;
use App\Http\Requests\UpdateTeamRequest;
use Throwable;

final class TeamController extends Controller
{
    use HandlesApiErrors;

    public function __construct(
        private readonly LeagueRepository $leagues,
        private readonly TeamRepository $teams,
    ) {}

    public function index()
    {
        try {
            $league = $this->leagues->getDefault();
            return $this->ok(['ok' => true, 'data' => $this->teams->allByLeague((int)$league->id)]);
        } catch (Throwable $e) {
            return $this->handle($e);
        }
    }

    public function store(StoreTeamRequest $request)
    {
        try {
            $league = $this->leagues->getDefault();
            $team = $this->teams->create(
                (int)$league->id,
                (string)$request->string('name'),
                (int)$request->integer('power')
            );

            return $this->ok(['ok' => true, 'data' => $team], 201);
        } catch (Throwable $e) {
            return $this->handle($e);
        }
    }

    public function update(UpdateTeamRequest $request, int $teamId)
    {
        try {
            $league = $this->leagues->getDefault();
            $team = $this->teams->update((int)$league->id, $teamId, $request->validated());
            return $this->ok(['ok' => true, 'data' => $team]);
        } catch (Throwable $e) {
            return $this->handle($e);
        }
    }

    public function destroy(int $teamId)
    {
        try {
            $league = $this->leagues->getDefault();
            $this->teams->delete((int)$league->id, $teamId);
            return $this->ok(['ok' => true]);
        } catch (Throwable $e) {
            return $this->handle($e);
        }
    }
}
