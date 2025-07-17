<?php

namespace App\Notifications;

use App\Models\Appointment;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class AppointmentStatusUpdated extends Notification
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
        $status = ucfirst($this->appointment->status);

        return (new MailMessage)
            ->subject("Appointment {$status}")
            ->greeting("Hello {$notifiable->name},")
            ->line("Your appointment for the session \"{$this->appointment->trainingSession->title}\" has been {$this->appointment->status}.")
            ->action('View Appointment', url('/login')) // Or direct dashboard URL
            ->line('Thank you for using our training platform!');
    }
}
