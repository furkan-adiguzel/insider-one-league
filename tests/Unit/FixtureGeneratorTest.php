<?php

namespace Tests\Unit;

use App\Models\GameMatch;
use App\Models\League;
use App\Models\Team;
use App\Services\League\FixtureGenerator;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FixtureGeneratorTest extends TestCase
{
    use RefreshDatabase;

    public function test_generates_double_round_robin_for_four_teams(): void
    {
        $league = League::query()->create(['name' => 'L']);
        $teams = collect([
            ['name' => 'A','power' => 50],
            ['name' => 'B','power' => 50],
            ['name' => 'C','power' => 50],
            ['name' => 'D','power' => 50],
        ])->map(fn($t) => Team::query()->create(['league_id'=>$league->id] + $t));

        app(FixtureGenerator::class)->generate($league->fresh());

        $this->assertDatabaseCount('matches', 12); // 4 teams => 12 matches total
        $this->assertEquals(6, $league->fresh()->total_weeks);

        // each week has 2 matches
        for ($w=1; $w<=6; $w++) {
            $this->assertEquals(2, GameMatch::query()->where('league_id',$league->id)->where('week',$w)->count());
        }
    }
}
