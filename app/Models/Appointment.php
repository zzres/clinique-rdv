<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
    protected $fillable = ['user_id', 'consultation_type_id', 'date', 'start_time', 'end_time', 'status', 'notes'];

    protected $casts = [
        'date' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function consultationType()
    {
        return $this->belongsTo(ConsultationType::class);
    }
}
