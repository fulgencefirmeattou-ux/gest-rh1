<?php

namespace App\Notifications;

use App\Models\Absence;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AbsenceNotification extends Notification
{
    use Queueable;

    public function __construct(
        public Absence $absence,
        public string  $type  // 'nouvelle' | 'validee' | 'rejetee'
    ) {}

    public function via(object $notifiable): array
    {
        return ['database', 'mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $employe = $this->absence->employe;
        $nom = $employe->nom . ' ' . $employe->prenom;

        return match($this->type) {
            'nouvelle' => (new MailMessage)
                ->subject("Nouvelle déclaration d'absence — {$nom}")
                ->greeting("Bonjour,")
                ->line("L'employé **{$nom}** a soumis une déclaration d'absence.")
                ->line("**Type :** " . $this->absence->typeLabel())
                ->line("**Date :** " . $this->absence->date_absence->format('d/m/Y'))
                ->line("**Motif :** " . ($this->absence->motif ?? 'Non précisé'))
                ->action('Traiter la demande', url('/rh')),

            'validee' => (new MailMessage)
                ->subject("Votre absence a été validée")
                ->greeting("Bonjour {$nom},")
                ->line("Votre déclaration d'absence du **" . $this->absence->date_absence->format('d/m/Y') . "** a été **validée**.")
                ->line($this->absence->commentaire_rh ? "**Commentaire :** " . $this->absence->commentaire_rh : ''),

            'rejetee' => (new MailMessage)
                ->subject("Votre absence a été rejetée")
                ->greeting("Bonjour {$nom},")
                ->line("Votre déclaration d'absence du **" . $this->absence->date_absence->format('d/m/Y') . "** a été **rejetée**.")
                ->line($this->absence->commentaire_rh ? "**Motif :** " . $this->absence->commentaire_rh : ''),

            default => (new MailMessage)->line('Mise à jour de votre absence.'),
        };
    }

    public function toDatabase(object $notifiable): array
    {
        $employe = $this->absence->employe;
        $nom = $employe->nom . ' ' . $employe->prenom;

        return match($this->type) {
            'nouvelle' => [
                'titre'   => "Nouvelle absence — {$nom}",
                'message' => "Absence de type « " . $this->absence->typeLabel() . " » le " . $this->absence->date_absence->format('d/m/Y'),
                'motif'   => $this->absence->motif ?? null,
                'url'     => '/rh',
                'icone'   => 'ri-error-warning-line',
                'couleur' => 'warning',
            ],
            'validee' => [
                'titre'   => "Absence validée",
                'message' => "Votre absence du " . $this->absence->date_absence->format('d/m/Y') . " a été validée.",
                'motif'   => $this->absence->commentaire_rh ?? null,
                'url'     => route('justificatifs.absence.liste'),
                'icone'   => 'ri-checkbox-circle-line',
                'couleur' => 'success',
            ],
            'rejetee' => [
                'titre'   => "Absence rejetée",
                'message' => "Votre absence du " . $this->absence->date_absence->format('d/m/Y') . " a été rejetée.",
                'motif'   => $this->absence->commentaire_rh ?? null,
                'url'     => route('justificatifs.absence.liste'),
                'icone'   => 'ri-close-circle-line',
                'couleur' => 'danger',
            ],
            default => ['titre' => 'Absence', 'message' => '', 'motif' => null, 'url' => '#', 'icone' => 'ri-notification-line', 'couleur' => 'info'],
        };
    }

    public function toArray(object $notifiable): array
    {
        return $this->toDatabase($notifiable);
    }
}
