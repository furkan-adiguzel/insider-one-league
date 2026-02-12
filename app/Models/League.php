<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class League extends Model
{
    protected $fillable = [
        'name','current_week','total_weeks','is_started','is_finished','started_at','finished_at',
    ];

    protected $casts = [
        'current_week' => 'int',
        'total_weeks' => 'int',
        'is_started' => 'bool',
        'is_finished' => 'bool',
        'started_at' => 'datetime',
        'finished_at' => 'datetime',
    ];

    public function teams(): HasMany
    {
        return $this->hasMany(Team::class);
    }

    public function matches(): HasMany
    {
        return $this->hasMany(GameMatch::class, 'league_id');
    }
}
