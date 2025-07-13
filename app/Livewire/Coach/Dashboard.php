<?php

namespace App\Livewire\Coach;

use App\Models\Appointment;
use App\Models\TrainingSession;
use Livewire\Component;

class Dashboard extends Component
{
    public int $sessionCount = 0;
    public int $totalBookings = 0;
    public int $approved = 0;
    public int $pending = 0;
    public int $rejected = 0;

    public function mount()
    {
        $coachId = auth()->id();

        $this->sessionCount = TrainingSession::where('coach_id', $coachId)->count();

        $this->totalBookings = Appointment::whereHas('session', function ($q) use ($coachId) {
            $q->where('coach_id', $coachId);
        })->count();

        $this->approved = Appointment::whereHas('session', fn($q) => $q->where('coach_id', $coachId))
            ->where('status', 'approved')->count();

        $this->pending = Appointment::whereHas('session', fn($q) => $q->where('coach_id', $coachId))
            ->where('status', 'pending')->count();

        $this->rejected = Appointment::whereHas('session', fn($q) => $q->where('coach_id', $coachId))
            ->where('status', 'rejected')->count();
    }

    public function render()
    {
        return view('livewire.coach.dashboard');
    }
}

