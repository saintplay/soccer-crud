<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PlayerDetail extends Model
{
    protected $fillable = [
        'full_name',
        'height',
        'weight',
        'birthdate',
        'nationality',
    ];

    protected $table = 'player_details';
}
