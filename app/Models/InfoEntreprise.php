<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InfoEntreprise extends Model
{
    protected $table = 'info_entreprise';

    protected $fillable = [
        'nom', 'adresse', 'siege_social',
        'num_cnps', 'num_contribuable',
        'email', 'telephone', 'logo',
    ];

    /**
     * Retourne l'unique enregistrement, ou un objet vide si absent.
     */
    public static function instance(): self
    {
        return self::firstOrNew(['id' => 1]);
    }
}
