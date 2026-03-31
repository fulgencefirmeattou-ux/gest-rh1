<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Absence extends Model
{
    protected $fillable=[
        'employe_id',
        'type_absence',
        'date_absence',
        'motif',
        'justificatif',
        'statut',
    ];

    public function employe(){
        return $this->belongsTo(Employe::class);
    }

    protected $casts = [
        'date_absence'=>'date',
    ];

}
