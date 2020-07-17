<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Player extends Model
{
    protected $fillable = [
        'name',
        'positions',
        'goals',
    ];

    protected $table = 'players';

    public function details()
    {
        return $this->hasOne('App\PlayerDetail');
    }

    public function teams()
    {
        return $this->belongsToMany('App\Team', 'players_teams');
    }
}
