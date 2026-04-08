<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Pointage extends Model
{
    protected $fillable = [
        'employe_id',
        'date',
        'heure_arrivee',
        'heure_depart',
        'heure_debut_pause',
        'heure_fin_pause',
        'heures_travaillees',
        'heures_sup',
        'heures_manquantes',
        'statut',
        'type_jour',
        'valide_par',
        'date_validation',
    ];

    protected $casts = [
        'date'            => 'date',
        'date_validation' => 'datetime',
        'heures_travaillees' => 'float',
        'heures_sup'         => 'float',
        'heures_manquantes'  => 'float',
    ];

    // Journée normale = 8h
    const HEURES_JOURNEE = 8.0;

    // ─── Relations ────────────────────────────────────────────────

    public function employe()
    {
        return $this->belongsTo(User::class, 'employe_id');
    }

    public function validateur()
    {
        return $this->belongsTo(User::class, 'valide_par');
    }

    // ─── Calcul des heures ────────────────────────────────────────

    /**
     * Recalcule heures_travaillees, heures_sup, heures_manquantes
     * et applique la pause automatique si besoin.
     */
    public function calculerHeures(): void
    {
        if (!$this->heure_arrivee || !$this->heure_depart) {
            return;
        }

        $arrivee = Carbon::createFromTimeString($this->heure_arrivee);
        $depart  = Carbon::createFromTimeString($this->heure_depart);

        $totalPresence = $arrivee->diffInMinutes($depart); // en minutes

        // Durée de pause en minutes
        $pauseMinutes = 0;
        if ($this->heure_debut_pause && $this->heure_fin_pause) {
            $debutPause = Carbon::createFromTimeString($this->heure_debut_pause);
            $finPause   = Carbon::createFromTimeString($this->heure_fin_pause);
            $pauseMinutes = $debutPause->diffInMinutes($finPause);
        } elseif ($totalPresence >= 360) {
            // Pause automatique de 60 min si présence >= 6h et pas de pause saisie
            $pauseMinutes = 60;
        }

        $heuresTravailleesMinutes = $totalPresence - $pauseMinutes;
        $heuresTravaillees = round($heuresTravailleesMinutes / 60, 2);

        $heuresSup       = max(0, round($heuresTravaillees - self::HEURES_JOURNEE, 2));
        $heuresManquantes = max(0, round(self::HEURES_JOURNEE - $heuresTravaillees, 2));

        $this->heures_travaillees = $heuresTravaillees;
        $this->heures_sup         = $heuresSup;
        $this->heures_manquantes  = $heuresManquantes;
    }

    // ─── Accesseurs utiles ────────────────────────────────────────

    public function getCouleurStatutAttribute(): string
    {
        return match($this->statut) {
            'VALIDE'    => 'success',
            'REFUSE'    => 'danger',
            default     => 'warning',
        };
    }

    public function getCouleurHeuresAttribute(): string
    {
        if ($this->heures_sup > 0)         return 'primary';   // bleu
        if ($this->heures_manquantes > 0)  return 'danger';    // rouge
        return 'success';                                        // vert
    }

    public function getHeuresTravailleesFormatAttribute(): string
    {
        if ($this->heures_travaillees === null) return '—';
        $h = floor($this->heures_travaillees);
        $m = round(($this->heures_travaillees - $h) * 60);
        return sprintf('%dh%02d', $h, $m);
    }
}
