<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Poste extends Model
{
    //

    protected $fillable = [
        'name',
        'description',
    ];

    public function poste()
    {
        return $this->hasMany(Employe::class);
    }
    
}
