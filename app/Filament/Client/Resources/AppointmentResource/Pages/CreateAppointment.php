<?php

namespace App\Filament\Client\Resources\AppointmentResource\Pages;

use App\Filament\Client\Resources\AppointmentResource;
use Filament\Resources\Pages\CreateRecord;
use App\Models\Appointment;
use App\Models\TrainingSession;
use Illuminate\Validation\ValidationException;
use Filament\Notifications\Notification;
use App\Notifications\NewTrainingSessionBooked; // ✅ Make sure this line exists

class CreateAppointment extends CreateRecord
{
    protected static string $resource = AppointmentResource::class;

    protected function beforeCreate(): void
    {
        $trainingSessionId = $this->data['training_session_id'];
        $userId = auth()->id();

        $alreadyBooked = Appointment::where('client_id', $userId)
            ->where('training_session_id', $trainingSessionId)
            ->whereIn('status', ['pending', 'approved'])
            ->exists();

        if ($alreadyBooked) {
            Notification::make()
                ->title('Duplicate Booking')
                ->body('You have already booked this session.')
                ->danger()
                ->send();

            throw ValidationException::withMessages([
                'training_session_id' => 'You already booked this session.',
            ]);
        }

        $session = TrainingSession::find($trainingSessionId);
        $currentCount = Appointment::where('training_session_id', $trainingSessionId)
            ->whereIn('status', ['pending', 'approved'])
            ->count();

        if ($currentCount >= $session->max_clients) {
            Notification::make()
                ->title('Session Full')
                ->body('This session is already full.')
                ->danger()
                ->send();

            throw ValidationException::withMessages([
                'training_session_id' => 'This session is already full.',
            ]);
        }

        $this->data['client_id'] = $userId;
        $this->data['status'] = 'pending';

        Notification::make()
            ->title('Booked!')
            ->body('Your session has been booked and is pending approval.')
            ->success()
            ->send();
    }

    // ✅ Coach Notification Here
    protected function afterCreate(): void
    {
        $appointment = $this->record; // This is the saved Appointment model
        $coach = $appointment->trainingSession->coach;

        if ($coach && $coach->email) {
            $coach->notify(new NewTrainingSessionBooked($appointment));
        }
    }
}