<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ContratSeeder extends Seeder
{
    public function run(): void
    {
        $employes = DB::table('employes')->get()->keyBy('matricule');

        // [matricule => [type, date_debut, date_fin, salaire_base, mode_calcul, heures/semaine]]
        $contrats = [
            'DG0101-01'  => ['CDI', '2018-01-15', null,         850000, 'mensuel', 40],
            'CAB0101-01' => ['CDI', '2019-03-10', null,         350000, 'mensuel', 40],
            'RH0101-01'  => ['CDI', '2019-06-01', null,         550000, 'mensuel', 40],
            'RH0101-02'  => ['CDI', '2020-02-15', null,         320000, 'mensuel', 40],
            'RH0101-03'  => ['CDI', '2020-09-01', null,         310000, 'mensuel', 40],
            'IT0101-01'  => ['CDI', '2019-09-01', null,         600000, 'mensuel', 40],
            'IT0101-02'  => ['CDI', '2021-01-10', null,         480000, 'mensuel', 40],
            'IT0101-03'  => ['CDI', '2021-06-01', null,         420000, 'mensuel', 40],
            'FIN0101-01' => ['CDI', '2018-11-01', null,         520000, 'mensuel', 40],
            'FIN0101-02' => ['CDI', '2020-04-01', null,         400000, 'mensuel', 40],
            'COM0101-01' => ['CDI', '2019-07-15', null,         490000, 'mensuel', 40],
            'COM0101-02' => ['CDD', '2022-03-01', '2024-03-01', 280000, 'mensuel', 40],
            'COM0101-03' => ['CDD', '2022-07-01', '2024-07-01', 290000, 'mensuel', 40],
        ];

        foreach ($contrats as $matricule => [$type, $debut, $fin, $salaire, $mode, $heures]) {
            $employe = $employes[$matricule] ?? null;
            if (!$employe) continue;

            $contratId = DB::table('contrats')->insertGetId([
                'employe_id'       => $employe->id,
                'type_contrat'     => $type,
                'date_debut'       => $debut,
                'date_fin'         => $fin,
                'salaire_base'     => $salaire,
                'mode_calcul'      => $mode,
                'heures_par_semaine'=> $heures,
                'statut'           => 'actif',
                'created_at'       => now(),
                'updated_at'       => now(),
            ]);

            // Primes associées
            $primes = $this->getPrimes($matricule, $salaire);
            foreach ($primes as [$libelle, $montant]) {
                DB::table('contrat_primes')->insert([
                    'contrat_id' => $contratId,
                    'libelle'    => $libelle,
                    'montant'    => $montant,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }

    private function getPrimes(string $matricule, float $salaire): array
    {
        $primes = [
            ['Prime de transport', 30000],
            ['Prime de logement',  50000],
        ];

        // Primes supplémentaires pour les responsables
        if (in_array($matricule, ['DG0101-01', 'RH0101-01', 'IT0101-01', 'FIN0101-01', 'COM0101-01'])) {
            $primes[] = ['Prime de responsabilité', round($salaire * 0.1)];
        }

        return $primes;
    }
}
