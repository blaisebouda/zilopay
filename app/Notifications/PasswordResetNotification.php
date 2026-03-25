<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PasswordResetNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected string $token;

    protected string $otpCode;

    /**
     * Create a new notification instance.
     */
    public function __construct(string $token, string $otpCode)
    {
        $this->token = $token;
        $this->otpCode = $otpCode;
    }

    /**
     * Get the notification's delivery channels.
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
        $resetUrl = url(config('app.frontend_url').'/reset-password?token='.$this->token);

        return (new MailMessage)
            ->subject('Réinitialisation de votre mot de passe')
            ->greeting('Bonjour,')
            ->line('Vous recevez cet email car nous avons reçu une demande de réinitialisation de mot de passe pour votre compte.')
            ->line("**Code de vérification : {$this->otpCode}**")
            ->action('Réinitialiser le mot de passe', $resetUrl)
            ->line('Ce lien et ce code expireront dans 60 minutes.')
            ->line('Si vous n\'avez pas demandé de réinitialisation de mot de passe, aucune action n\'est requise.')
            ->salutation('Cordialement, L\'équipe Union Halal');
    }

    /**
     * Get the array representation of the notification.
     */
    public function toArray(object $notifiable): array
    {
        return [
            'token' => $this->token,
            'otp_code' => $this->otpCode,
        ];
    }
}
