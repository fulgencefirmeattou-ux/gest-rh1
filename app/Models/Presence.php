<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Presence extends Model
{
    protected $fillable = [
        'employe_id',
        'date',
        'heures_travaillees',
        'heures_sup',
    ];

    public function employe(){
        return $this->belongsTo(Employe::class);
    }

    protected $casts = [
        'date'=>'date',
    ];
}
