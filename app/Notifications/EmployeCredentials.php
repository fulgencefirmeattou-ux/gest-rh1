<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Employe;

class EmployeCredentials extends Notification
{
    use Queueable;
    public string $login;
    public string $password;

    /**
     * Create a new notification instance.
     */
    public function __construct($login, $password)
    {
        $this->login = $login;
        $this->password = $password;
    }

    


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
        return (new MailMessage)
            ->subject('Accès portail RH')
            ->greeting('Bonjour ' . ($notifiable->employe->prenom ?? $notifiable->login))
            ->line('Votre compte a été créé.')
            ->line('Matricule : ' . ($notifiable->employe->matricule ?? ''))
            ->line('Mot de passe temporaire : ' . $this->password)
            ->line('Vous devrez changer votre mot de passe à la première connexion.')
            ->action('Se connecter', url('/login'))
            ->salutation('Service RH');
    }


    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
