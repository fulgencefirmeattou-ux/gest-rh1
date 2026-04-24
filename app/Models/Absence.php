<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Absence extends Model
{
    protected $fillable = [
        'employe_id', 'type_absence', 'date_absence', 'date_fin_absence',
        'heure_debut', 'heure_fin', 'motif', 'justificatif',
        'statut', 'commentaire_rh', 'traite_par', 'traite_le',
    ];

    protected $casts = [
        'date_absence'     => 'date',
        'date_fin_absence' => 'date',
        'traite_le'        => 'datetime',
    ];

    public function employe()
    {
        return $this->belongsTo(Employe::class);
    }

    public function traitePar()
    {
        return $this->belongsTo(User::class, 'traite_par');
    }

    public function typeLabel(): string
    {
        return match($this->type_absence) {
            'maladie'               => 'Maladie',
            'retard'                => 'Retard',
            'permission_courte'     => 'Permission courte',
            'absence_non_justifiee' => 'Absence non justifiée',
            'rendez_vous'           => 'Rendez-vous',
            default                 => 'Autre',
        };
    }

    public function statutLabel(): string
    {
        return match($this->statut) {
            'en_attente' => 'En attente',
            'validee'    => 'Validée',
            'rejetee'    => 'Rejetée',
            default      => $this->statut,
        };
    }

    public function statutColor(): string
    {
        return match($this->statut) {
            'en_attente' => 'warning',
            'validee'    => 'success',
            'rejetee'    => 'danger',
            default      => 'secondary',
        };
    }
}
