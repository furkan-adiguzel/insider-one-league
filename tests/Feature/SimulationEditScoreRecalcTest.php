<?php

namespace Tests\Feature;

use App\Models\GameMatch;
use App\Models\League;
use App\Models\Team;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

final class SimulationEditScoreRecalcTest extends TestCase
{
    use RefreshDatabase;

    private function seedLeague(): void
    {
        $league = League::query()->create(['name' => 'L']);

        Team::query()->create(['league_id' => $league->id, 'name' => 'A', 'power' => 90]);
        Team::query()->create(['league_id' => $league->id, 'name' => 'B', 'power' => 10]);
        Team::query()->create(['league_id' => $league->id, 'name' => 'C', 'power' => 60]);
        Team::query()->create(['league_id' => $league->id, 'name' => 'D', 'power' => 40]);
    }

    public function test_edit_score_recalculates_table(): void
    {
        $this->seedLeague();

        // fixtures üret
        $this->postJson('/api/simulation/generate-fixtures')
            ->assertOk()
            ->assertJsonPath('ok', true);

        // bir hafta oynat ki en az bir maç "played" olsun
        $this->postJson('/api/simulation/play-next-week')
            ->assertOk()
            ->assertJsonPath('ok', true);

        // DB’den oynanmış bir maç çek
        $game = GameMatch::query()
            ->where(function ($q) {
                // farklı şema ihtimallerini tolere et
                $q->where('is_played', true)
                    ->orWhereNotNull('played_at')
                    ->orWhere('played', true);
            })
            ->orderBy('id')
            ->first();

        // Eğer played filtresi yüzünden bulamazsa, ilk maçı al (en kötü senaryo)
        if (!$game) {
            $game = Game::query()->orderBy('id')->first();
        }

        $this->assertNotNull($game, 'No game found in DB after simulation steps.');

        // skoru "edit etmiş gibi" DB üzerinden değiştir
        // Kolon isimleri sende farklıysa bu array’i düzelt (home_goals/away_goals vs)
        $game->forceFill([
            'home_score' => 10,
            'away_score' => 0,
        ])->save();

        // API tekrar çağır: standings yeni skorla hesaplanmalı
        $after = $this->getJson('/api/league')
            ->assertOk()
            ->assertJsonPath('ok', true)
            ->json('data');

        $standings = $after['standings'] ?? [];
        $this->assertIsArray($standings);
        $this->assertNotEmpty($standings);

        $gfs = array_map(fn ($r) => (int) ($r['gf'] ?? 0), $standings);
        $this->assertTrue(max($gfs) >= 10, 'Expected at least one team GF >= 10 after score edit.');
    }
}
