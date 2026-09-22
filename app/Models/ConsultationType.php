<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ConsultationType extends Model
{
    protected $fillable = ['name', 'description', 'duration_minutes'];

    public function appointments()
    {
        return $this->hasMany(Appointment::class);
    }
}
