<?php
namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;
use App\Models\Appointment;

class NewTrainingSessionBooked extends Notification
{
    use Queueable;

    public Appointment $appointment;

    public function __construct(Appointment $appointment)
    {
        $this->appointment = $appointment;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('New Training Session Booking')
            ->greeting('Hello Coach!')
            ->line('A client has booked one of your training sessions.')
            ->line('Session ID: ' . $this->appointment->training_session_id)
            ->line('Client: ' . $this->appointment->client->name)
            ->action('View Appointments', url('/login')) // customize if needed
            ->line('Thank you!');
    }
}
