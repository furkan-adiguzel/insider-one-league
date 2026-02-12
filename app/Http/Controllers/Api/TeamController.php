<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Team;
use App\Services\League\LeagueService;
use Illuminate\Http\Request;

final class TeamController extends Controller
{
    public function __construct(private readonly LeagueService $league) {}

    public function index()
    {
        $league = $this->league->ensureDefaultLeague();

        return response()->json([
            'data' => Team::query()
                ->where('league_id', $league->id)
                ->orderBy('id')
                ->get(['id','name','power']),
        ]);
    }

    public function store(Request $request)
    {
        $league = $this->league->ensureDefaultLeague();

        $data = $request->validate([
            'name' => ['required','string','max:80'],
            'power' => ['required','integer','min:1','max:200'],
        ]);

        $team = Team::query()->create([
            'league_id' => $league->id,
            'name' => $data['name'],
            'power' => (int)$data['power'],
        ]);

        return response()->json(['data' => $team], 201);
    }

    public function update(Request $request, int $teamId)
    {
        $league = $this->league->ensureDefaultLeague();

        $data = $request->validate([
            'name' => ['sometimes','string','max:80'],
            'power' => ['sometimes','integer','min:1','max:200'],
        ]);

        $team = Team::query()
            ->where('league_id', $league->id)
            ->where('id', $teamId)
            ->firstOrFail();

        $team->update($data);

        return response()->json(['data' => $team]);
    }

    public function destroy(int $teamId)
    {
        $league = $this->league->ensureDefaultLeague();

        $team = Team::query()
            ->where('league_id', $league->id)
            ->where('id', $teamId)
            ->firstOrFail();

        $team->delete();

        return response()->json(['ok' => true]);
    }
}
