<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Unavailability extends Model
{
    protected $fillable = ['date', 'start_time', 'end_time', 'reason'];

    protected $casts = [
        'date' => 'date',
    ];
}
