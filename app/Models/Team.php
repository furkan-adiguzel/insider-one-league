<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Team extends Model
{
    protected $fillable = ['league_id','name','power'];

    protected $casts = [
        'power' => 'int',
        'league_id' => 'int',
    ];

    public function league(): BelongsTo
    {
        return $this->belongsTo(League::class);
    }
}
