<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ContratPrime extends Model
{
   protected $fillable = [
        'contrat_id',
        'libelle',
        'montant',
    ];

    public function contrat(){
        return $this->belongsTo(Contrats::class);
    }
}
