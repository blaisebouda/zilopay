<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class OtpNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected string $otpCode;

    protected string $type;

    /**
     * Create a new notification instance.
     */
    public function __construct(string $otpCode, string $type = 'registration')
    {
        $this->otpCode = $otpCode;
        $this->type = $type;
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
        $subject = $this->getSubject();
        $greeting = $this->getGreeting();
        $message = $this->getMessage();

        return (new MailMessage)
            ->subject($subject)
            ->greeting($greeting)
            ->line($message)
            ->line("**Code de vérification : {$this->otpCode}**")
            ->line('Ce code expire dans 10 minutes.')
            ->line("Si vous n'avez pas demandé ce code, veuillez ignorer cet email.")
            ->salutation('Cordialement, L\'équipe Union Halal');
    }

    /**
     * Get the subject based on type.
     */
    protected function getSubject(): string
    {
        return match ($this->type) {
            'registration' => 'Code de vérification d\'inscription',
            'login' => 'Code de vérification de connexion',
            'password_reset' => 'Code de réinitialisation de mot de passe',
            'phone_verification' => 'Code de vérification de téléphone',
            default => 'Code de vérification',
        };
    }

    /**
     * Get the greeting based on type.
     */
    protected function getGreeting(): string
    {
        return 'Bonjour,';
    }

    /**
     * Get the message based on type.
     */
    protected function getMessage(): string
    {
        return match ($this->type) {
            'registration' => 'Merci de vous être inscrit sur Union Halal. Voici votre code de vérification :',
            'login' => 'Voici votre code de vérification pour vous connecter :',
            'password_reset' => 'Vous avez demandé la réinitialisation de votre mot de passe. Voici votre code :',
            'phone_verification' => 'Voici votre code pour vérifier votre numéro de téléphone :',
            default => 'Voici votre code de vérification :',
        };
    }

    /**
     * Get the array representation of the notification.
     */
    public function toArray(object $notifiable): array
    {
        return [
            'otp_code' => $this->otpCode,
            'type' => $this->type,
        ];
    }
}
