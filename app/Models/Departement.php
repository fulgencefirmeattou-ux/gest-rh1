<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Departement extends Model
{
    //

    protected $fillable = [
    'nom',
    'description',
    'responsable_id',
    'date_creation',
    'date_connexion'
    ];

    protected $casts = [
        'date_creation' => 'datetime',
        'date_connexion' => 'datetime',
    ];

    public function services()
    {
        return $this->hasMany(Service::class);
    }

    public function responsable()
    {
        return $this->belongsTo(User::class, 'responsable_id');
    }

     public function employes()
    {
        return $this->hasManyThrough(
            Employe::class,
            Service::class,
            'departement_id', // FK dans services
            //'service_id',     // FK dans employes
            'id',
            'id'
        );
    }

}
