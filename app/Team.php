<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Team extends Model
{
    protected $fillable = [
        'name',
    ];

    protected $table = 'teams';

    public function tournaments()
    {
        return $this->belongsToMany('App\Tournaments', 'teams_tournaments');
    }
}
