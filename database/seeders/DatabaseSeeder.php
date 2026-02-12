<?php

namespace Database\Seeders;

use App\Models\League;
use App\Models\Team;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $league = League::query()->firstOrCreate(['id' => 1], [
            'name' => 'Insider One League',
            'total_weeks' => 6,
        ]);

        if (Team::query()->where('league_id', $league->id)->count() === 0) {
            Team::query()->create(['league_id' => $league->id, 'name' => 'Chelsea', 'power' => 90]);
            Team::query()->create(['league_id' => $league->id, 'name' => 'Arsenal', 'power' => 82]);
            Team::query()->create(['league_id' => $league->id, 'name' => 'Manchester City', 'power' => 95]);
            Team::query()->create(['league_id' => $league->id, 'name' => 'Liverpool', 'power' => 88]);
        }
    }
}
