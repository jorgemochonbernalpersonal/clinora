<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Collection;

class RecordDeletionNotification extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(
        public Collection $clinicalNotes
    ) {}

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $count = $this->clinicalNotes->count();
        $oldestDate = $this->clinicalNotes->min('session_date');
        
        return (new MailMessage)
            ->subject('Aviso: Eliminación Programada de Historiales Clínicos')
            ->greeting('Hola ' . $notifiable->name)
            ->line("Te informamos que {$count} notas clínicas de tu consulta están programadas para eliminación según la política de retención de datos.")
            ->line("**Fecha más antigua**: {$oldestDate}")
            ->line('Estas notas tienen más de 7 años y han sido archivadas durante al menos 2 años.')
            ->line('**Acción requerida**: Si necesitas conservar alguna de estas notas por razones legales o clínicas, contacta con soporte en los próximos 30 días.')
            ->line('Después de este período, las notas serán eliminadas permanentemente del sistema.')
            ->action('Ver Notas Archivadas', url('/psychologist/clinical-notes?filter=archived'))
            ->line('Esta eliminación es parte de nuestra política de cumplimiento con la normativa de protección de datos (RGPD/LOPDGDD).')
            ->salutation('Equipo de Clinora');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'record_deletion_warning',
            'count' => $this->clinicalNotes->count(),
            'oldest_date' => $this->clinicalNotes->min('session_date'),
            'deletion_date' => now()->addDays(30)->toDateString(),
        ];
    }
}
