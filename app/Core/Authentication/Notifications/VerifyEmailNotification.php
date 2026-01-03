<?php

namespace App\Core\Authentication\Notifications;

use Illuminate\Auth\Notifications\VerifyEmail as BaseVerifyEmail;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\Facades\URL;

class VerifyEmailNotification extends BaseVerifyEmail
{
    /**
     * Get the verification URL for the given notifiable.
     */
    protected function verificationUrl($notifiable): string
    {
        // Generate a temporary signed URL for web verification
        return URL::temporarySignedRoute(
            'verification.verify',
            now()->addHours(24),
            [
                'id' => $notifiable->getKey(),
                'hash' => sha1($notifiable->getEmailForVerification()),
            ]
        );
    }

    /**
     * Build the mail representation of the notification.
     */
    public function toMail($notifiable): MailMessage
    {
        $verificationUrl = $this->verificationUrl($notifiable);

        return (new MailMessage)
            ->subject('Verifica tu email - Clinora')
            ->greeting("¡Hola {$notifiable->first_name}!")
            ->line('Gracias por registrarte en Clinora.')
            ->line('Por favor, verifica tu dirección de email haciendo clic en el botón de abajo:')
            ->action('Verificar Email', $verificationUrl)
            ->line('Este enlace expirará en 24 horas.')
            ->line('Si no creaste una cuenta, no es necesario que hagas nada.')
            ->salutation('Saludos, El equipo de Clinora');
    }
}
