<?php

namespace Database\Seeders;

use App\Models\Pointage;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class PointageSeeder extends Seeder
{
    const MOIS           = 3;    // Mars 2026
    const ANNEE          = 2026;
    const HEURES_JOURNEE = 8.0;

    // Scénarios : [heure_arrivee, heure_depart, heure_debut_pause, heure_fin_pause]
    const SCENARIOS = [
        'normal'       => ['08:00', '17:00', '12:00', '13:00'],  // 8h pile
        'avance'       => ['07:30', '17:00', '12:00', '13:00'],  // 8.5h → +0.5h sup
        'tres_avance'  => ['07:00', '17:00', '12:00', '13:00'],  // 9h   → +1h sup
        'tardif'       => ['08:30', '17:00', '12:00', '13:00'],  // 7.5h → -0.5h manquant
        'tres_tardif'  => ['09:00', '17:00', '12:00', '13:00'],  // 7h   → -1h manquant
        'sup_soir'     => ['08:00', '17:30', '12:00', '13:00'],  // 8.5h → +0.5h sup
        'long'         => ['07:30', '18:00', '12:00', '13:00'],  // 9.5h → +1.5h sup
        'demi_journee' => ['08:00', '12:00', null,    null   ],   // 4h   → -4h manquant
        'conge'        => null,  // type_jour = CONGE
        'ferie'        => null,  // type_jour = FERIE
        'absent'       => null,  // absent = true
    ];

    // 22 jours ouvrés en mars 2026 (lun 2 → mar 31)
    // Plan par employé (index 0–14)
    const PLANS = [
        // Employé 1 — très régulier, modèle de ponctualité
        0 => ['normal','normal','normal','normal','normal',
              'normal','avance','normal','normal','normal',
              'avance','normal','normal','normal','normal',
              'normal','normal','normal','normal','normal',
              'conge','normal'],

        // Employé 2 — quelques retards, 2 absences
        1 => ['normal','tardif','normal','normal','avance',
              'normal','normal','absent','normal','tardif',
              'normal','normal','absent','normal','normal',
              'sup_soir','normal','normal','tardif','normal',
              'normal','normal'],

        // Employé 3 — beaucoup d'heures sup
        2 => ['avance','long','normal','sup_soir','normal',
              'long','avance','normal','sup_soir','long',
              'normal','avance','normal','long','normal',
              'sup_soir','normal','avance','long','normal',
              'normal','sup_soir'],

        // Employé 4 — demi-journées fréquentes, 3 absences
        3 => ['normal','normal','demi_journee','normal','absent',
              'normal','normal','normal','demi_journee','normal',
              'absent','normal','normal','normal','demi_journee',
              'normal','absent','normal','normal','normal',
              'demi_journee','normal'],

        // Employé 5 — profil mixte, férié et congé
        4 => ['normal','avance','normal','absent','normal',
              'sup_soir','normal','ferie','avance','tardif',
              'normal','normal','long','normal','absent',
              'normal','tardif','normal','normal','sup_soir',
              'conge','normal'],

        // Employé 6 — très régulier avec quelques heures sup
        5 => ['normal','normal','sup_soir','normal','normal',
              'avance','normal','normal','normal','sup_soir',
              'normal','normal','avance','normal','normal',
              'normal','sup_soir','normal','normal','avance',
              'normal','normal'],

        // Employé 7 — retards chroniques
        6 => ['tardif','tardif','normal','tres_tardif','tardif',
              'normal','tardif','normal','tardif','normal',
              'tres_tardif','tardif','normal','tardif','normal',
              'tardif','normal','tres_tardif','tardif','normal',
              'normal','tardif'],

        // Employé 8 — beaucoup d'absences et congés
        7 => ['normal','absent','absent','normal','conge',
              'absent','normal','normal','absent','normal',
              'conge','absent','normal','normal','absent',
              'normal','normal','conge','absent','normal',
              'normal','absent'],

        // Employé 9 — heures sup tous les jours
        8 => ['tres_avance','long','avance','long','tres_avance',
              'long','avance','sup_soir','long','tres_avance',
              'avance','long','normal','tres_avance','long',
              'avance','sup_soir','long','tres_avance','avance',
              'long','sup_soir'],

        // Employé 10 — profil stable, quelques demi-journées
        9 => ['normal','normal','normal','demi_journee','normal',
              'normal','normal','normal','normal','demi_journee',
              'normal','normal','normal','normal','normal',
              'demi_journee','normal','normal','normal','normal',
              'normal','demi_journee'],

        // Employé 11 — alternant, présence partielle
        10 => ['normal','absent','normal','absent','normal',
               'absent','normal','absent','normal','absent',
               'normal','absent','normal','absent','normal',
               'absent','normal','absent','normal','absent',
               'normal','absent'],

        // Employé 12 — régulier avec 1 congé et heures sup finales
        11 => ['normal','normal','normal','normal','avance',
               'normal','normal','normal','normal','normal',
               'conge','normal','normal','normal','normal',
               'avance','normal','normal','sup_soir','avance',
               'normal','long'],

        // Employé 13 — très ponctuel, légères heures sup
        12 => ['avance','normal','avance','normal','sup_soir',
               'normal','avance','normal','normal','avance',
               'normal','sup_soir','normal','avance','normal',
               'normal','avance','normal','sup_soir','normal',
               'avance','normal'],

        // Employé 14 — profil irrégulier (retards + absences + sup)
        13 => ['tardif','absent','sup_soir','tardif','normal',
               'absent','avance','tardif','normal','sup_soir',
               'tardif','absent','normal','long','tardif',
               'normal','absent','sup_soir','tardif','normal',
               'normal','absent'],

        // Employé 15 — stagiaire, présence correcte
        14 => ['normal','normal','tardif','normal','normal',
               'normal','normal','normal','tardif','normal',
               'normal','normal','normal','normal','tardif',
               'normal','normal','normal','normal','normal',
               'normal','normal'],
    ];

    public function run(): void
    {
        $employes = User::where('role', 'employe')->take(15)->get();

        if ($employes->isEmpty()) {
            $this->command->warn('Aucun employé trouvé. Lancez d\'abord DatabaseSeeder.');
            return;
        }

        $validateur = User::whereIn('role', ['admin', 'rh'])->first();
        $debut      = Carbon::createFromDate(self::ANNEE, self::MOIS, 1);
        $fin        = $debut->copy()->endOfMonth();

        Pointage::query()->delete();

        foreach ($employes as $empIndex => $employe) {
            $plan      = self::PLANS[$empIndex] ?? self::PLANS[0];
            $jourOuvre = 0;

            for ($date = $debut->copy(); $date->lte($fin); $date->addDay()) {
                if ($date->isWeekend()) continue;

                $scenario  = $plan[$jourOuvre] ?? 'normal';
                $jourOuvre++;

                $estPasse  = $date->lt(Carbon::today()->subDays(2));
                $statut    = $estPasse ? 'VALIDE' : 'EN_ATTENTE';
                $validePar = $estPasse ? $validateur?->id : null;
                $dateValid = $estPasse ? $date->copy()->setTime(18, 0) : null;

                // ── Absent ───────────────────────────────────────
                if ($scenario === 'absent') {
                    Pointage::create([
                        'employe_id'         => $employe->id,
                        'date'               => $date->toDateString(),
                        'absent'             => true,
                        'type_jour'          => 'NORMAL',
                        'statut'             => $statut,
                        'valide_par'         => $validePar,
                        'date_validation'    => $dateValid,
                        'heures_travaillees' => null,
                        'heures_sup'         => 0,
                        'heures_manquantes'  => self::HEURES_JOURNEE,
                    ]);
                    continue;
                }

                // ── Congé ────────────────────────────────────────
                if ($scenario === 'conge') {
                    Pointage::create([
                        'employe_id'         => $employe->id,
                        'date'               => $date->toDateString(),
                        'absent'             => false,
                        'type_jour'          => 'CONGE',
                        'statut'             => $statut,
                        'valide_par'         => $validePar,
                        'date_validation'    => $dateValid,
                        'heures_travaillees' => null,
                        'heures_sup'         => 0,
                        'heures_manquantes'  => 0,
                    ]);
                    continue;
                }

                // ── Férié ────────────────────────────────────────
                if ($scenario === 'ferie') {
                    Pointage::create([
                        'employe_id'         => $employe->id,
                        'date'               => $date->toDateString(),
                        'absent'             => false,
                        'type_jour'          => 'FERIE',
                        'statut'             => 'VALIDE',
                        'valide_par'         => $validateur?->id,
                        'date_validation'    => $date->copy()->setTime(8, 0),
                        'heures_travaillees' => null,
                        'heures_sup'         => 0,
                        'heures_manquantes'  => 0,
                    ]);
                    continue;
                }

                // ── Jour travaillé ───────────────────────────────
                [$arrivee, $depart, $debutPause, $finPause] = self::SCENARIOS[$scenario];

                $totalMin = Carbon::createFromTimeString($arrivee)
                    ->diffInMinutes(Carbon::createFromTimeString($depart));

                $pauseMin = 0;
                if ($debutPause && $finPause) {
                    $pauseMin = Carbon::createFromTimeString($debutPause)
                        ->diffInMinutes(Carbon::createFromTimeString($finPause));
                } elseif ($totalMin >= 360) {
                    $pauseMin = 60; // pause auto >= 6h
                }

                $heuresTravaillees = round(($totalMin - $pauseMin) / 60, 2);
                $heuresSup         = max(0, round($heuresTravaillees - self::HEURES_JOURNEE, 2));
                $heuresManquantes  = max(0, round(self::HEURES_JOURNEE - $heuresTravaillees, 2));

                Pointage::create([
                    'employe_id'         => $employe->id,
                    'date'               => $date->toDateString(),
                    'absent'             => false,
                    'heure_arrivee'      => $arrivee . ':00',
                    'heure_depart'       => $depart . ':00',
                    'heure_debut_pause'  => $debutPause ? $debutPause . ':00' : null,
                    'heure_fin_pause'    => $finPause   ? $finPause . ':00'   : null,
                    'heures_travaillees' => $heuresTravaillees,
                    'heures_sup'         => $heuresSup,
                    'heures_manquantes'  => $heuresManquantes,
                    'type_jour'          => 'NORMAL',
                    'statut'             => $statut,
                    'valide_par'         => $validePar,
                    'date_validation'    => $dateValid,
                ]);
            }
        }

        $this->command->info('✓ Pointages mars 2026 générés pour ' . $employes->count() . ' employé(s).');
    }
}
