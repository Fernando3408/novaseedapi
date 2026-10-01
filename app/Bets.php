<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Bets extends Model
{	

	protected $fillable = [
        "team1",
		"player1",
		"team2",
		"player2",
		"fecha",
		"hora",
		"avg",
		"finals1",
		"finals2",
		"expected_l",
		"expected_v",
		"status_avg",
		"status_0",
		"status_1",
		"status_3",
    ];

}
