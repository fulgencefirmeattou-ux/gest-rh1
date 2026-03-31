<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HistoriqueConge extends Model
{
    protected $fillable = [
        'demande_conge_id',
        'approver_id',
        'etape',
        'decision',
        'commentaire',
        'approved_at'
    ];

    public function leave()
    {
        return $this->belongsTo(HistoriqueConge::class, 'historique_conge_id');
    }

    public function responsable()
    {
        return $this->belongsTo(Employe::class, 'responsable_id');
    }
}
