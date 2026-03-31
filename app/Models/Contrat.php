<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Contrat extends Model
{
   protected $fillable = [
        'employe_id',
        'type_contrat',
        'date_debut',
        'date_fin',
        'duree',
        'salaire_base',
        'pdf_path',
        'heures_par_semaine',
        'mode_calcul',
        'statut',
    ];

    public function employe(){
        return $this->belongsTo(Employe::class);
    }

    public function primes(){
        return $this->hasMany(ContratPrime::class);
    }

    protected $casts = [
        'date_debut' => 'date',
        'date_fin' => 'date',
    ];
}
