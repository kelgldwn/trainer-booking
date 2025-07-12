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
    ];

    // Optional: define relationships
    public function coach()
    {
        return $this->belongsTo(User::class, 'coach_id');
    }
}
