<?php

namespace App\Notifications;

use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class ResetPasswordNotification extends Notification
{
    public $token;

    public function __construct($token)
    {
        $this->token = $token;
    }

    /**
     * Determine which communication channel to use (in this case, email).
     */
    public function via($notifiable)
    {
        return ['mail'];  // Defines that this notification will be sent via email
    }

    /**
     * Build the mail message for the password reset link.
     */
    public function toMail($notifiable)
    {
        // Generate the reset password URL
        $url = url('/reset-password/' . $this->token . '?email=' . urlencode($notifiable->email));

        // Return the email message
        return (new MailMessage)
            ->subject('Reset Password Notification')
            ->line('You are receiving this email because we received a password reset request for your account.')
            ->action('Reset Password', $url)
            ->line('If you did not request a password reset, no further action is required.');
    }

}
