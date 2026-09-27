<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class WelcomeUser extends Notification
{
    use Queueable;

    public function __construct(
        public string $userName,
        public string $role
    ) {}

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $accountType = $this->role === 'driver' ? 'Driver' : 'Student';

        return (new MailMessage)
            ->subject('Your CampusRide account was created successfully')
            ->greeting('Hello ' . $this->userName . '!')
            ->line('Your CampusRide ' . $accountType . ' account has been created successfully.')
            ->line('Use the Gmail address and password you created during registration to log in.')
            ->action('Log In to CampusRide', url('/login'))
            ->line('For your security, your password is not included in this email.')
            ->line('Safe Rides • Better Campus Life')
            ->salutation('CampusRide — Safe Rides • Better Campus Life');
    }
}
