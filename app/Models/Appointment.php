<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
    protected $fillable = [
        'client_id',
        'training_session_id',
        'status',
    ];

    // Optional: define relationships
    public function client()
{
    return $this->belongsTo(User::class, 'client_id');
}

    public function trainingSession()
{
    return $this->belongsTo(TrainingSession::class);
}
}
