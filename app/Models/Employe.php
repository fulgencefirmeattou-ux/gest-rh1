<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Employe extends Model
{
        protected $fillable = [
        'matricule',
        'nom',
        'prenom',
        'civilite',
        'nationalite',
        'situation_matrimoniale',
        'nombre_enfants',
        'telephone',
        'email',
        'date_naissance',
        'adresse',
        'photo_profil',
        'type_contrat_id',
        'departement_id',
        'poste_id',
        'user_id',
        'date_embauche',
    ];

    protected $dates = [
        'date_naissance',
        'date_embauche',
    ];



        public function departement()
    {
        return $this->belongsTo(Departement::class);
    }

    public function poste()
    {
        return $this->belongsTo(Poste::class);
    }

    public function typeContrat()
    {
        return $this->belongsTo(TypeContrat::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
}

}