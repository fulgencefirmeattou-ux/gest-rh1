<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TypeContrat extends Model
{
    //
    protected $fillable = [
        'name',
        'description'
    ];

    public function type()
    {
        return $this->hasMany(Employe::class);
    }
}
