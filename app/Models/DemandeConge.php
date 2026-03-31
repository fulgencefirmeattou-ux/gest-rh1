<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use Carbon\CarbonPeriod;

class DemandeConge extends Model
{
    protected $fillable = [
        'employe_id',
        'type_conge',
        'date_debut_conge',
        'date_fin_conge',
        'raison',
        'justification_absence',
        'statut',
        'date_retour',
    ];

    public function employe()
    {
        return $this->belongsTo(Employe::class, 'employe_id');
    }

    public function historiques()
    {
        return $this->hasMany(HistoriqueConge::class);
    }
  

    // public function getNombreJoursAttribute()
    // {
    //     $debut = Carbon::parse($this->date_debut_conge)->startOfDay();
    //     $fin   = Carbon::parse($this->date_fin_conge)->startOfDay();

    //     return $debut->diffInDays($fin) + 1; // Inclut le jour de début
    // }



    public function getJoursOuvresAttribute()
    {
        $start = $this->date_debut_conge;
        $end   = $this->date_fin_conge;

        if (!$start || !$end) {
            return 0;
        }

        return CarbonPeriod::create($start, $end)
            ->filter(fn ($date) => $date->isWeekday())
            ->count();
    }



    protected $casts = [
        'date_debut_conge' => 'date',
        'date_fin_conge' => 'date',
        'date_retour' => 'date',
    ];


}
