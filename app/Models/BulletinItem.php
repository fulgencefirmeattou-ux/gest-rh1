<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BulletinItem extends Model
{
    //
    protected $fillable = [
        'bulletin_id',
        'type_item',
        'type',
        'libelle',
        'montant',
    ];

    public function bulletin(){
        return $this->belongsTo(BulletinPaie::class);
    }
}
