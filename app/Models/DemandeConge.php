<?php

namespace App\Models;

use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Database\Eloquent\Model;

class DemandeConge extends Model
{
    protected $fillable = [
        'employe_id', 'type_conge',
        'date_debut_conge', 'date_fin_conge', 'date_retour',
        'raison', 'justification_absence',
        'statut', 'commentaire',
    ];

    protected $casts = [
        'date_debut_conge' => 'date',
        'date_fin_conge'   => 'date',
        'date_retour'      => 'date',
    ];

    public function employe()
    {
        return $this->belongsTo(Employe::class);
    }

    public function historiques()
    {
        return $this->hasMany(HistoriqueConge::class);
    }

    public function getJoursOuvresAttribute(): int
    {
        if (!$this->date_debut_conge || !$this->date_fin_conge) {
            return 0;
        }

        return CarbonPeriod::create($this->date_debut_conge, $this->date_fin_conge)
            ->filter(fn($date) => $date->isWeekday())
            ->count();
    }

    public function typeLabel(): string
    {
        return match($this->type_conge) {
            'conge_paye'       => 'Congé payé',
            'maladie'          => 'Maladie',
            'permission_courte'=> 'Permission courte',
            'exceptionnel'     => 'Congé exceptionnel',
            'special'          => 'Congé spécial',
            default            => 'Autre',
        };
    }

    public function statutLabel(): string
    {
        return match($this->statut) {
            'attente_service'     => 'En attente (Service)',
            'attente_departement' => 'En attente (Département)',
            'attente_dg'          => 'En attente (DG/RH)',
            'approuvee'           => 'Approuvée',
            'rejetee'             => 'Rejetée',
            'modification_demandee' => 'Modification demandée',
            default               => $this->statut,
        };
    }

    public function statutColor(): string
    {
        return match($this->statut) {
            'approuvee'           => 'success',
            'rejetee'             => 'danger',
            'modification_demandee' => 'warning',
            default               => 'info',
        };
    }

    public function estPermission(): bool
    {
        return $this->type_conge === 'permission_courte';
    }
}
