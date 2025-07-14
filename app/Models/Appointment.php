<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
    protected $fillable = [
        'training_session_id',
        'client_id',
        'status',
    ];

    protected static function booted()
    {
        static::creating(function ($appointment) {
            // Make sure client_id is set if available
            if (empty($appointment->client_id) && auth()->check()) {
                $appointment->client_id = auth()->id(); // adjust guard if needed
            }
        });
    }
    

    public function trainingSession()
    {
        return $this->belongsTo(TrainingSession::class);
    }

    public function client()
    {
        return $this->belongsTo(User::class, 'client_id');
    }

    
    public function coach()
    {
        return $this->trainingSession->coach(); // Shortcut accessor
    }
}

