<?php

namespace Tests\Feature;

use App\Models\League;
use App\Models\Team;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SimulationFlowTest extends TestCase
{
    use RefreshDatabase;

    public function test_full_flow(): void
    {
        $league = League::query()->create(['id' => 1, 'name' => 'L']);
        Team::query()->create(['league_id'=>$league->id,'name'=>'A','power'=>90]);
        Team::query()->create(['league_id'=>$league->id,'name'=>'B','power'=>10]);
        Team::query()->create(['league_id'=>$league->id,'name'=>'C','power'=>60]);
        Team::query()->create(['league_id'=>$league->id,'name'=>'D','power'=>40]);

        $this->postJson('/api/simulation/generate-fixtures')->assertOk();
        $this->postJson('/api/simulation/play-next-week')->assertOk();

        $this->getJson('/api/league')
            ->assertOk()
            ->assertJsonPath('data.league.current_week', 1);
    }
}
