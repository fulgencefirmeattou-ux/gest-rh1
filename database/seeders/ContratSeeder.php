<?php

namespace Database\Seeders;

use App\Models\Contrat;
use App\Models\ContratPrime;
use App\Models\Employe;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class ContratSeeder extends Seeder
{
    // Primes indexées par position de l'employé (1-based) : [[libelle, montant], ...]
    const PRIMES = [
        1  => [['Prime de rendement', 50000], ['Prime de transport spéciale', 20000]],
        3  => [['Prime de management', 80000], ['Prime d\'ancienneté', 50000]],
        5  => [['Prime de performance', 70000], ['Prime d\'ancienneté', 40000]],
        9  => [['Prime commerciale', 100000]],
        12 => [['Prime de responsabilité', 120000], ['Prime d\'ancienneté', 80000]],
        15 => [['Prime de résultats', 60000]],
    ];

    public function run(): void
    {
        Contrat::query()->delete();
        ContratPrime::query()->delete();

        $employes = Employe::with('typeContrat')->orderBy('id')->get();

        if ($employes->isEmpty()) {
            $this->command->warn('Aucun employé trouvé. Lancez d\'abord DatabaseSeeder.');
            return;
        }

        $count = 0;
        foreach ($employes as $position => $employe) {
            $typeNom = $employe->typeContrat?->name ?? 'CDI';
            $debut   = Carbon::parse($employe->date_embauche);

            $dateFin = null;
            $duree   = null;

            if (in_array($typeNom, ['CDD', 'Stage'])) {
                $duree   = 12;
                $dateFin = $debut->copy()->addMonths($duree)->toDateString();
            } elseif ($typeNom === 'Alternance') {
                $duree   = 24;
                $dateFin = $debut->copy()->addMonths($duree)->toDateString();
            }

            $contrat = Contrat::create([
                'employe_id'         => $employe->id,
                'type_contrat'       => $typeNom,
                'date_debut'         => $debut->toDateString(),
                'date_fin'           => $dateFin,
                'duree'              => $duree,
                'salaire_base'       => $employe->salaire,
                'mode_calcul'        => 'mensuel',
                'heures_par_semaine' => 40,
                'statut'             => 'actif',
            ]);

            $positionKey = $position + 1; // 1-based
            foreach (self::PRIMES[$positionKey] ?? [] as [$libelle, $montant]) {
                ContratPrime::create([
                    'contrat_id' => $contrat->id,
                    'libelle'    => $libelle,
                    'montant'    => $montant,
                ]);
            }

            $count++;
        }

        $this->command->info("✓ {$count} contrat(s) générés.");
    }
}
