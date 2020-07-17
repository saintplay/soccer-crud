<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tournament extends Model
{
    protected $fillable = [
        'name',
        'location',
        'start_date',
        'end_date',
    ];

    protected $table = 'tournaments';
}
