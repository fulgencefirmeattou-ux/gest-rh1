<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Employe extends Model
{
    //
    protected $fillable = [
        'matricule', 
        'nom', 
        'prenom', 
        'email',
        'phone',
        'photo',
        // 'user_id', 
        'service_id', 
        'poste', 
        'date_embauche', 
        'salaire',
        'type_contrat',
        'duree',
        'date_fin'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    

    public function service()
    {
        return $this->belongsTo(Service::class);
    }

    protected $casts = [
        'date_embauche' => 'date',
        'date_fin' => 'date',
    ];

    public function contrats(){ 
        return $this->hasMany(Contrat::class); 
    }
    public function contratActif(){ 
        return $this->hasOne(Contrat::class)->where('statut','actif'); 
    }
    public function presences(){ 
        return $this->hasMany(Presence::class); 
    }
    public function bulletins(){ 
        return $this->hasMany(BulletinPaie::class); 
    }

    public function getBadgeCodeAttribute()
    {
        return 'EMP-' . str_pad($this->id, 5, '0', STR_PAD_LEFT);
    }

    public function departementResponsable()
    {
        return $this->hasOne(Departement::class, 'responsable_id');
    }
        public function serviceResponsable()
    {
        return $this->hasOne(Service::class, 'responsable_id');     

    }
}
