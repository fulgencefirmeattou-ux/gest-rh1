<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\BulletinPaie;

class Employe extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'matricule', 'nom', 'prenom', 'civilite', 'nationalite',
        'situation_matrimoniale', 'nombre_enfants', 'date_naissance',
        'lieu_naissance', 'telephone', 'email', 'adresse', 'photo_profil',
        'curriculum_vitae', 'lettre_motivation', 'salaire', 'type_contrat_id',
        'departement_id', 'service_id', 'poste_id', 'user_id', 'date_embauche',
        'solde_conges', 'solde_permissions', 'conges_pris', 'permissions_prises',
    ];

    protected $casts = [
        'date_naissance' => 'date',
        'date_embauche'  => 'date',
    ];

    public function departement()
    {
        return $this->belongsTo(Departement::class);
    }

    public function service()
    {
        return $this->belongsTo(Service::class);
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

    public function contrats()
    {
        return $this->hasMany(Contrat::class);
    }

    public function absences()
    {
        return $this->hasMany(Absence::class);
    }

    public function demandesConge()
    {
        return $this->hasMany(DemandeConge::class);
    }

    public function bulletins()
    {
        return $this->hasMany(BulletinPaie::class);
    }

    public function soldeCongesRestant(): int
    {
        return max(0, $this->solde_conges - $this->conges_pris);
    }

    public function soldePermissionsRestant(): int
    {
        return max(0, $this->solde_permissions - $this->permissions_prises);
    }
}
