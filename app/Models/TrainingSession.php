<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TrainingSession extends Model
{
    protected $fillable = [
        'coach_id',
        'title',
        'description',
        'starts_at',
        'ends_at',
        'max_clients'
    ];

    protected static function booted()
    {
        static::creating(function ($trainingSession) {
            if (auth()->check() && empty($trainingSession->coach_id)) {
                $trainingSession->coach_id = auth()->id();
            }
        });
    }

    // Optional: define relationships
    public function coach()
    {
        return $this->belongsTo(User::class, 'coach_id');
    }

    protected $dates = ['start_time']; 
}
