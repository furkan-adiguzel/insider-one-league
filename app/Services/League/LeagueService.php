<?php

namespace App\Services\League;

use App\Models\GameMatch;
use App\Models\League;
use App\Models\Team;
use Carbon\CarbonImmutable;
use Illuminate\Support\Facades\DB;

final class LeagueService
{
    public function __construct(
        private readonly FixtureGenerator $fixtures,
        private readonly MatchSimulator $simulator,
    ) {}

    public function ensureDefaultLeague(): League
    {
        return League::query()->firstOrCreate(['id' => 1], ['name' => 'Insider One League']);
    }

    public function generateFixtures(League $league): void
    {
        $this->fixtures->generate($league);
    }

    public function resetLeague(League $league): void
    {
        DB::transaction(function () use ($league) {
            GameMatch::query()->where('league_id', $league->id)->delete();
            Team::query()->where('league_id', $league->id)->delete();

            $league->update([
                'current_week' => 0,
                'is_started' => false,
                'is_finished' => false,
                'started_at' => null,
                'finished_at' => null,
            ]);
        });
    }

    public function start(League $league): void
    {
        if ($league->is_started) return;

        $league->update([
            'is_started' => true,
            'started_at' => CarbonImmutable::now(),
            'current_week' => max(0, (int)$league->current_week),
        ]);
    }

    public function playNextWeek(League $league): int
    {
        $this->start($league);

        $nextWeek = (int)$league->current_week + 1;

        if ($league->is_finished) return (int)$league->current_week;
        if ($nextWeek > (int)$league->total_weeks) {
            $this->finish($league);
            return (int)$league->current_week;
        }

        DB::transaction(function () use ($league, $nextWeek) {
            $matches = GameMatch::query()
                ->where('league_id', $league->id)
                ->where('week', $nextWeek)
                ->orderBy('id')
                ->get();

            if ($matches->isEmpty()) {
                abort(422, "No matches found for week {$nextWeek}. Generate fixtures first.");
            }

            $teams = Team::query()
                ->where('league_id', $league->id)
                ->get(['id','power'])
                ->keyBy('id');

            foreach ($matches as $m) {
                if ($m->is_played) continue;

                $home = $teams[(int)$m->home_team_id];
                $away = $teams[(int)$m->away_team_id];

                [$hs, $as] = $this->simulator->simulate($m, $home, $away);

                $m->update([
                    'home_score' => $hs,
                    'away_score' => $as,
                    'is_played' => true,
                    'is_edited' => false,
                ]);
            }

            $league->update(['current_week' => $nextWeek]);

            if ($nextWeek >= (int)$league->total_weeks) {
                $this->finish($league);
            }
        });

        return (int)$league->current_week;
    }

    public function playAll(League $league): void
    {
        while (!$league->fresh()->is_finished) {
            $this->playNextWeek($league->fresh());
        }
    }

    public function editMatchScore(League $league, int $matchId, int $homeScore, int $awayScore): void
    {
        if ($homeScore < 0 || $awayScore < 0 || $homeScore > 20 || $awayScore > 20) {
            abort(422, 'Scores must be between 0 and 20.');
        }

        $match = GameMatch::query()
            ->where('league_id', $league->id)
            ->where('id', $matchId)
            ->firstOrFail();

        $match->update([
            'home_score' => $homeScore,
            'away_score' => $awayScore,
            'is_played' => true,
            'is_edited' => true,
        ]);

        // adjust current week if edited match is in the future:
        if ((int)$match->week > (int)$league->current_week) {
            $league->update(['current_week' => (int)$match->week]);
        }

        if ((int)$league->current_week >= (int)$league->total_weeks) {
            $this->finish($league);
        }
    }

    private function finish(League $league): void
    {
        $league->update([
            'is_finished' => true,
            'finished_at' => CarbonImmutable::now(),
            'current_week' => (int)$league->total_weeks,
        ]);
    }
}
