<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Employe extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'matricule',
        'nom',
        'prenom',
        'civilite',
        'nationalite',
        'situation_matrimoniale',
        'nombre_enfants',
        'date_naissance',
        'lieu_naissance',
        'telephone',
        'email',
        'adresse',
        'photo_profil',
        'curriculum_vitae',
        'lettre_motivation',
        'salaire',
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