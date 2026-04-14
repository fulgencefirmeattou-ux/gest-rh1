<?php

namespace Database\Seeders;

use App\Models\BulletinPaie;
use App\Models\Employe;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class BulletinPaieSeeder extends Seeder
{
    /**
     * Profils variables par employé :
     * [avantage_nature, indemnite_transport, nbre_parts, mode_reglement]
     */
    const PROFILS = [
        1  => [23472,  25000, 1.00, 'Virement'],   // Koné Abou
        2  => [0,      25000, 2.00, 'Virement'],   // Yao Serge
        3  => [30000,  25000, 1.50, 'Virement'],   // Assi Joëlle
        4  => [0,      20000, 1.00, 'Virement'],   // Traoré Fatoumata
        5  => [15000,  25000, 2.00, 'Virement'],   // Diomandé Lacina
        6  => [0,      20000, 1.00, 'Espèces'],    // Silué Nathalie
        7  => [0,      20000, 1.00, 'Virement'],   // Bamba Aïcha
        8  => [0,      15000, 1.00, 'Espèces'],    // Diallo Moussa
        9  => [20000,  25000, 2.50, 'Virement'],   // Coulibaly Ibrahim
        10 => [10000,  25000, 1.00, 'Virement'],   // Ouedraogo Clarisse
        11 => [0,      15000, 1.00, 'Espèces'],    // Koffi Arnaud
        12 => [35000,  25000, 2.00, 'Virement'],   // N'Guessan Eric
        13 => [0,      20000, 1.00, 'Virement'],   // Touré Aminata
        14 => [12000,  25000, 1.00, 'Virement'],   // Gnamba Rodrigue
        15 => [18000,  25000, 1.50, 'Virement'],   // Meité Sandrine
    ];

    /**
     * Mois à générer :
     * [mois, statut, reference_virement]
     */
    const MOIS_GENERES = [
        ['2026-02', 'paye',      '01001'],
        ['2026-03', 'valide',    '01002'],
        ['2026-04', 'brouillon', null   ],
    ];

    public function run(): void
    {
        BulletinPaie::query()->delete();

        $employes = Employe::orderBy('id')->get();

        if ($employes->isEmpty()) {
            $this->command->warn('Aucun employé trouvé.');
            return;
        }

        $count = 0;

        foreach ($employes as $employe) {
            $profil = self::PROFILS[$employe->id] ?? [0, 25000, 1.00, 'Virement'];
            [$avantageNature, $indemTransport, $nbreParts, $modeReglement] = $profil;

            foreach (self::MOIS_GENERES as [$mois, $statut, $refBase]) {
                $debut = Carbon::createFromFormat('Y-m', $mois)->startOfMonth();
                $fin   = $debut->copy()->endOfMonth();

                $data = BulletinPaie::calculer([
                    'employe_id'          => $employe->id,
                    'mois'                => $mois,
                    'periode_debut'       => $debut->toDateString(),
                    'periode_fin'         => $fin->toDateString(),
                    'nbre_parts'          => $nbreParts,
                    'salaire_base'        => $employe->salaire,
                    'avantage_nature'     => $avantageNature,
                    'indemnite_transport' => $indemTransport,
                    'brut_imposable'      => null, // calculé = salaire_brut
                    'cnps_plafond'        => 70000,
                    'cmu_base'            => 1000,
                ]);

                // Cumul : on simule le cumul depuis le début de l'année
                $moisIndex     = (int) Carbon::createFromFormat('Y-m', $mois)->format('n');
                $gainsMensuel  = $data['salaire_brut'] + $indemTransport;
                $retenuMensuel = $data['total_retenues'];

                $data['cumul_gains']          = $gainsMensuel  * $moisIndex;
                $data['cumul_retenues']       = $retenuMensuel * $moisIndex;
                $data['brut_imposable_cumul'] = $data['brut_imposable'] * $moisIndex;
                $data['jours_fiscaux']        = 30 * $moisIndex;
                $data['base_conge']           = round($data['salaire_brut'] * $moisIndex * 0.0417, 0);

                // Référence virement
                $data['mode_reglement']    = $modeReglement;
                $data['reference_virement'] = $refBase
                    ? $refBase . ' ' . str_pad($employe->id, 10, '0', STR_PAD_LEFT) . ' 00'
                    : null;

                $data['statut']   = $statut;
                $data['pdf_path'] = null;

                BulletinPaie::create($data);
                $count++;
            }
        }

        $this->command->info("✓ {$count} bulletins générés pour {$employes->count()} employé(s) sur " . count(self::MOIS_GENERES) . " mois.");
    }
}
