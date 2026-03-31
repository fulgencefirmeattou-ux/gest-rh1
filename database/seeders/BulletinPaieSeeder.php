<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BulletinPaieSeeder extends Seeder
{
    public function run(): void
    {
        $employes = DB::table('employes')->get();

        // Générer des bulletins pour les 3 derniers mois
        $mois = [
            '2025-11' => 'Novembre 2025',
            '2025-12' => 'Décembre 2025',
            '2026-01' => 'Janvier 2026',
        ];

        foreach ($employes as $employe) {
            // Chercher le contrat actif de l'employé
            $contrat = DB::table('contrats')
                ->where('employe_id', $employe->id)
                ->where('statut', 'actif')
                ->first();

            if (!$contrat) continue;

            // Primes du contrat
            $primes = DB::table('contrat_primes')
                ->where('contrat_id', $contrat->id)
                ->get();
            $totalPrimes = $primes->sum('montant');

            foreach ($mois as $periode => $libelle) {
                // Calcul simplifié
                $salaireBase    = $contrat->salaire_base;
                $heuresSup      = rand(0, 8) * 0.5;
                $tauxHoraire    = $salaireBase / (40 * 4.33);
                $montantHeureSup= round($heuresSup * $tauxHoraire * 1.25);
                $cotisations    = round($salaireBase * 0.028); // CNPS ~2.8%
                $autresRetenues = 0;
                $netAPayer      = $salaireBase + $totalPrimes + $montantHeureSup - $cotisations - $autresRetenues;

                $bulletinId = DB::table('bulletins_paie')->insertGetId([
                    'employe_id'       => $employe->id,
                    'contrat_id'       => $contrat->id,
                    'mois'             => $libelle,
                    'salaire_base'     => $salaireBase,
                    'total_primes'     => $totalPrimes,
                    'total_heures_sup' => $heuresSup,
                    'total_reductions' => 0,
                    'cotisations'      => $cotisations,
                    'montant_heures_sup'=> $montantHeureSup,
                    'montant_cnps'     => $cotisations,
                    'autres_retenues'  => $autresRetenues,
                    'net_a_payer'      => $netAPayer,
                    'statut'           => 'payé',
                    'created_at'       => now(),
                    'updated_at'       => now(),
                ]);

                // Items du bulletin
                // Salaire de base
                DB::table('bulletin_items')->insert([
                    'bulletin_id' => $bulletinId,
                    'type'        => 'base',
                    'libelle'     => 'Salaire de base',
                    'montant'     => $salaireBase,
                    'meta'        => null,
                    'created_at'  => now(),
                    'updated_at'  => now(),
                ]);

                // Primes
                foreach ($primes as $prime) {
                    DB::table('bulletin_items')->insert([
                        'bulletin_id' => $bulletinId,
                        'type'        => 'prime',
                        'libelle'     => $prime->libelle,
                        'montant'     => $prime->montant,
                        'meta'        => null,
                        'created_at'  => now(),
                        'updated_at'  => now(),
                    ]);
                }

                // Heures supplémentaires
                if ($montantHeureSup > 0) {
                    DB::table('bulletin_items')->insert([
                        'bulletin_id' => $bulletinId,
                        'type'        => 'heures_sup',
                        'libelle'     => 'Heures supplémentaires (' . $heuresSup . 'h)',
                        'montant'     => $montantHeureSup,
                        'meta'        => json_encode(['heures' => $heuresSup, 'taux' => 1.25]),
                        'created_at'  => now(),
                        'updated_at'  => now(),
                    ]);
                }

                // Cotisation CNPS
                DB::table('bulletin_items')->insert([
                    'bulletin_id' => $bulletinId,
                    'type'        => 'cotisation',
                    'libelle'     => 'Cotisation CNPS (2.8%)',
                    'montant'     => -$cotisations,
                    'meta'        => json_encode(['taux' => 0.028]),
                    'created_at'  => now(),
                    'updated_at'  => now(),
                ]);
            }
        }
    }
}
