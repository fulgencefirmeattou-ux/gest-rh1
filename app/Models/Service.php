<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    //
    protected $fillable = [
        'nom', 
        'departement_id',
        'description',
        'responsable_id'];

    public function departement()
    {
        return $this->belongsTo(Departement::class);
    }

    public function employes()
    {
        return $this->hasMany(Employe::class);
    }

        public function responsable()
    {
        return $this->belongsTo(Employe::class, 'responsable_id');
    }
}
