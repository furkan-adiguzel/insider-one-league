<?php

namespace Tests\Feature;

use App\Models\League;
use App\Models\Team;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

final class SimulationEditScoreRecalcTest extends TestCase
{
    use RefreshDatabase;

    private function seedScore(): void
    {
        $league = League::query()->create(['name' => 'L']);

        Team::query()->create(['league_id' => $league->id, 'name' => 'A', 'power' => 90]);
        Team::query()->create(['league_id' => $league->id, 'name' => 'B', 'power' => 10]);
        Team::query()->create(['league_id' => $league->id, 'name' => 'C', 'power' => 60]);
        Team::query()->create(['league_id' => $league->id, 'name' => 'D', 'power' => 40]);
    }

    public function test_edit_score_recalculates_table(): void
    {
        $this->seedScore();

        $this->postJson('/api/simulation/generate-fixtures')->assertOk();
        $this->postJson('/api/simulation/play-next-week')->assertOk();

        $state = $this->getJson('/api/league')->assertOk()->json('data');

        // week 1 fixture listinden ilk maç id’sini al
        $week1 = $state['fixtures_by_week'][0] ?? null;
        $this->assertNotNull($week1);

        $match = $week1['matches'][0] ?? null;
        $this->assertNotNull($match);

        $matchId = (int) $match['id'];

        // skoru abartılı set et (home 10 - away 0)
        $this->putJson("/api/matches/{$matchId}/score", [
            'home_score' => 10,
            'away_score' => 0,
        ])->assertOk()->assertJsonPath('ok', true);

        $after = $this->getJson('/api/league')->assertOk()->json('data');

        // Aşırı skor olunca GF artmalı: herhangi bir takımın gf’si 10+ olmalı
        $gfs = array_map(fn ($r) => (int) $r['gf'], $after['standings']);
        $this->assertTrue(max($gfs) >= 10);
    }
}
