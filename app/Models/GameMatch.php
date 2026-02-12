<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GameMatch extends Model
{
    protected $table = 'matches';

    protected $fillable = [
        'league_id','week','home_team_id','away_team_id',
        'home_score','away_score','is_played','is_edited',
    ];

    protected $casts = [
        'week' => 'int',
        'home_score' => 'int',
        'away_score' => 'int',
        'is_played' => 'bool',
        'is_edited' => 'bool',
    ];

    public function league(): BelongsTo { return $this->belongsTo(League::class); }
    public function homeTeam(): BelongsTo { return $this->belongsTo(Team::class, 'home_team_id'); }
    public function awayTeam(): BelongsTo { return $this->belongsTo(Team::class, 'away_team_id'); }
}
