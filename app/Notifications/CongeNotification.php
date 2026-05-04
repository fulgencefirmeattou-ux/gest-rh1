<?php

namespace App\Notifications;

use App\Models\DemandeConge;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class CongeNotification extends Notification
{
    use Queueable;

    public function __construct(
        public DemandeConge $conge,
        public string       $type  // 'nouvelle' | 'approuvee' | 'rejetee' | 'modification_demandee' | 'etape_suivante'
    ) {}

    public function via(object $notifiable): array
    {
        return ['database', 'mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $employe = $this->conge->employe;
        $nom = $employe->nom . ' ' . $employe->prenom;

        return match($this->type) {
            'nouvelle' => (new MailMessage)
                ->subject("Nouvelle demande de congé — {$nom}")
                ->greeting("Bonjour,")
                ->line("**{$nom}** a soumis une demande de congé.")
                ->line("**Type :** " . $this->conge->typeLabel())
                ->line("**Du :** " . $this->conge->date_debut_conge->format('d/m/Y') . " **au** " . $this->conge->date_fin_conge->format('d/m/Y'))
                ->line("**Durée :** " . $this->conge->jours_ouvres . " jours ouvrés")
                ->action('Traiter la demande', url('/conges/a-valider/service')),

            'etape_suivante' => (new MailMessage)
                ->subject("Demande de congé à valider — {$nom}")
                ->greeting("Bonjour,")
                ->line("Une demande de congé de **{$nom}** vous a été transmise pour validation.")
                ->line("**Type :** " . $this->conge->typeLabel())
                ->line("**Du :** " . $this->conge->date_debut_conge->format('d/m/Y') . " **au** " . $this->conge->date_fin_conge->format('d/m/Y')),

            'approuvee' => (new MailMessage)
                ->subject("Votre congé a été approuvé")
                ->greeting("Bonjour {$nom},")
                ->line("Votre demande de congé du **" . $this->conge->date_debut_conge->format('d/m/Y') . "** au **" . $this->conge->date_fin_conge->format('d/m/Y') . "** a été **approuvée**.")
                ->action('Voir mes congés', route('conges.voir')),

            'rejetee' => (new MailMessage)
                ->subject("Votre congé a été rejeté")
                ->greeting("Bonjour {$nom},")
                ->line("Votre demande de congé du **" . $this->conge->date_debut_conge->format('d/m/Y') . "** au **" . $this->conge->date_fin_conge->format('d/m/Y') . "** a été **rejetée**.")
                ->line($this->conge->commentaire ? "**Motif :** " . $this->conge->commentaire : '')
                ->action('Voir mes congés', route('conges.voir')),

            'modification_demandee' => (new MailMessage)
                ->subject("Modification demandée pour votre congé")
                ->greeting("Bonjour {$nom},")
                ->line("Une modification vous est demandée pour votre congé du **" . $this->conge->date_debut_conge->format('d/m/Y') . "**.")
                ->line($this->conge->commentaire ? "**Commentaire :** " . $this->conge->commentaire : '')
                ->action('Modifier ma demande', route('conges.details', $this->conge->id)),

            default => (new MailMessage)->line('Mise à jour de votre congé.'),
        };
    }

    public function toDatabase(object $notifiable): array
    {
        $employe = $this->conge->employe;
        $nom = $employe->nom . ' ' . $employe->prenom;

        return match($this->type) {
            'nouvelle' => [
                'titre'          => "Nouvelle demande de congé — {$nom}",
                'message'        => $this->conge->typeLabel() . " du " . $this->conge->date_debut_conge->format('d/m/Y') . " au " . $this->conge->date_fin_conge->format('d/m/Y'),
                'raison'         => $this->conge->raison ?? null,
                'motif'          => null,
                'url'            => match($this->conge->statut) {
                                        'attente_dg'          => '/dg/conges/a-valider/dg',
                                        'attente_departement' => '/departement/conges/a-valider/departement',
                                        default               => '/conges/a-valider/service',
                                    },
                'icone'          => 'ri-calendar-event-line',
                'couleur'        => 'info',
            ],
            'etape_suivante' => [
                'titre'          => "Demande de congé à valider — {$nom}",
                'message'        => "En attente de votre validation.",
                'raison'         => $this->conge->raison ?? null,
                'motif'          => null,
                'url'            => '/dg/conges/a-valider/dg',
                'icone'          => 'ri-calendar-check-line',
                'couleur'        => 'warning',
            ],
            'approuvee' => [
                'titre'          => "Congé approuvé",
                'message'        => "Votre congé du " . $this->conge->date_debut_conge->format('d/m/Y') . " au " . $this->conge->date_fin_conge->format('d/m/Y') . " est approuvé.",
                'raison'         => $this->conge->raison ?? null,
                'motif'          => null,
                'url'            => route('conges.voir'),
                'icone'          => 'ri-checkbox-circle-line',
                'couleur'        => 'success',
            ],
            'rejetee' => [
                'titre'          => "Congé rejeté",
                'message'        => "Votre congé du " . $this->conge->date_debut_conge->format('d/m/Y') . " a été rejeté.",
                'raison'         => $this->conge->raison ?? null,
                'motif'          => $this->conge->commentaire ?? null,
                'url'            => route('conges.voir'),
                'icone'          => 'ri-close-circle-line',
                'couleur'        => 'danger',
            ],
            'modification_demandee' => [
                'titre'          => "Modification demandée",
                'message'        => "Votre demande de congé requiert une modification.",
                'raison'         => $this->conge->raison ?? null,
                'motif'          => $this->conge->commentaire ?? null,
                'url'            => route('conges.details', $this->conge->id),
                'icone'          => 'ri-edit-line',
                'couleur'        => 'warning',
            ],
            default => ['titre' => 'Congé', 'message' => '', 'raison' => null, 'motif' => null, 'url' => '#', 'icone' => 'ri-notification-line', 'couleur' => 'info'],
        };
    }

    public function toArray(object $notifiable): array
    {
        return $this->toDatabase($notifiable);
    }
}
