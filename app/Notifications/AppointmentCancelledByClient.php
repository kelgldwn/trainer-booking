<?php

namespace App\Notifications;

use App\Models\Appointment;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class AppointmentCancelledByClient extends Notification
{
    use Queueable;

    public Appointment $appointment;

    public function __construct(Appointment $appointment)
    {
        $this->appointment = $appointment;
    }

    public function via($notifiable)
    {
        return ['mail']; // Add 'database' if using in-app notifications
    }

    public function toMail($notifiable)
{
    $session = $this->appointment->trainingSession;
    $client = $this->appointment->client;

    return (new MailMessage)
        ->subject('A Client Cancelled Their Appointment')
        ->greeting('Hello Coach!')
        ->line('Client ' . ($client->name ?? 'Unknown') . ' has cancelled their booking.')
        ->line('Session: ' . ($session->title ?? 'Unknown Session'))
        ->line('Scheduled on: ' . ($session?->scheduled_at ? $session->scheduled_at->format('F j, Y g:i A') : 'Not Scheduled'))
        ->action('View Bookings', url('/coach/appointments'))
        ->line('Thank you for using our service!');
}
}
