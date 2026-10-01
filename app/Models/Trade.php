<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Trade extends Model
{
    protected $fillable = [
        'trade_date',
        'player1',
        'player2',
        'team1',
        'team2',
        'ht1',
        'ht2',
        'odds',
        'parcial1',
        'parcial2',
        'ft1',
        'ft2',
        'sp',
        'stake',
        #'result',
        'wl',
        'profit_pct',
        'profit_amount',
    ];

    protected $casts = [
        'trade_date' => 'date',
        'odds' => 'decimal:2',
        'sp' => 'decimal:2',
        'stake' => 'decimal:2',
        'profit_pct' => 'decimal:2',
        'profit_amount' => 'decimal:2',
    ];
}
