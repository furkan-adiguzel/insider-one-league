<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\GameMatch;
use App\Services\League\LeagueService;

final class MatchController extends Controller
{
    public function __construct(private readonly LeagueService $league) {}

    public function fixtures()
    {
        $league = $this->league->ensureDefaultLeague();

        $matches = GameMatch::query()
            ->with(['homeTeam:id,name', 'awayTeam:id,name'])
            ->where('league_id', $league->id)
            ->orderBy('week')
            ->orderBy('id')
            ->get();

        $byWeek = [];
        foreach ($matches as $m) {
            $byWeek[$m->week][] = [
                'id' => $m->id,
                'week' => $m->week,
                'home' => ['id' => $m->home_team_id, 'name' => $m->homeTeam?->name],
                'away' => ['id' => $m->away_team_id, 'name' => $m->awayTeam?->name],
                'home_score' => $m->home_score,
                'away_score' => $m->away_score,
                'is_played' => $m->is_played,
                'is_edited' => $m->is_edited,
            ];
        }

        return response()->json(['data' => $byWeek]);
    }
}
