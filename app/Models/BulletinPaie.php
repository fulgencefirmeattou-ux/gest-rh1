<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BulletinPaie extends Model
{
    protected $table = 'bulletins_paie';
    protected $fillable = [
        'employe_id',
        'contrat_id',
        'mois',
        'periode',
        'salaire_base',
        'total_primes',
        'total_heures_sup',
        'montant_heures_sup',
        'montant_cnps',
        'total_reductions',
        'autres_retenues',
        'net_a_payer',
        'pdf_path',
        'statut',
        'cotisations',
    ];

    public function employe(){
        return $this->belongsTo(Employe::class);
    }

    public function contrat(){
        return $this->belongsTo(Contrat::class);
    }

    public function items(){
        return $this->hasMany(BulletinItem::class, 'bulletin_id');
    }

    protected $casts = [
        'periode' => 'date',
    ];

    public function getNetAPayerAttribute()
    {
        $plus = $this->items()
            ->whereIn('type', ['salaire', 'prime', 'total_heures_sup'])
            ->sum('montant');

        $moins = $this->items()
            ->whereIn('type', ['retenue', 'cotisation'])
            ->sum('montant');

        return $plus - $moins;
    }

    public function recalculerTotaux()
    {
        $this->total_primes = $this->items()
            ->where('type', 'prime')
            ->sum('montant');

        $this->montant_heures_sup = $this->items()
            ->where('type', 'total_heures_sup')
            ->sum('montant');

        $this->total_reductions = $this->items()
            ->whereIn('type', ['retenue', 'cotisation'])
            ->sum('montant');

        $this->net_a_payer =
            $this->salaire_base
            + $this->total_primes
            + $this->montant_heures_sup
            - $this->total_reductions;

        $this->save();
    }

}
