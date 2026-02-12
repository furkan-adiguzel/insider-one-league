<?php

namespace Tests\Feature;

use App\Models\League;
use App\Models\Team;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

final class SimulationFlowTest extends TestCase
{
    use RefreshDatabase;

    private function seedLeague4Teams(): void
    {
        $league = League::query()->create([
            'name' => 'L',
            // id verme zorunlu değil, auto increment daha sağlıklı
        ]);

        Team::query()->create(['league_id' => $league->id, 'name' => 'A', 'power' => 90]);
        Team::query()->create(['league_id' => $league->id, 'name' => 'B', 'power' => 10]);
        Team::query()->create(['league_id' => $league->id, 'name' => 'C', 'power' => 60]);
        Team::query()->create(['league_id' => $league->id, 'name' => 'D', 'power' => 40]);
    }

    public function test_full_flow_generate_and_play_next_week(): void
    {
        $this->seedLeague4Teams();

        $this->postJson('/api/simulation/generate-fixtures')
            ->assertOk()
            ->assertJsonPath('ok', true);

        $this->postJson('/api/simulation/play-next-week')
            ->assertOk()
            ->assertJsonPath('ok', true);

        $res = $this->getJson('/api/league')
            ->assertOk()
            ->assertJsonPath('ok', true)
            ->assertJsonPath('data.league.current_week', 1);

        // Standings 4 takım dönmeli
        $this->assertCount(4, $res->json('data.standings'));
    }
}
