<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HistoriqueConge extends Model
{
    protected $fillable = [
        'demande_conge_id', 'approver_id',
        'etape', 'decision', 'commentaire', 'approved_at',
    ];

    protected $casts = [
        'approved_at' => 'datetime',
    ];

    public function demandeConge()
    {
        return $this->belongsTo(DemandeConge::class);
    }

    public function approver()
    {
        return $this->belongsTo(User::class, 'approver_id');
    }

    public function etapeLabel(): string
    {
        return match($this->etape) {
            'service'     => 'Responsable de service',
            'departement' => 'Responsable de département',
            'dg_rh'       => 'DG / RH',
            default       => $this->etape,
        };
    }

    public function decisionLabel(): string
    {
        return match($this->decision) {
            'approuve'             => 'Approuvé',
            'rejete'               => 'Rejeté',
            'modification_demandee'=> 'Modification demandée',
            default                => $this->decision,
        };
    }
}
