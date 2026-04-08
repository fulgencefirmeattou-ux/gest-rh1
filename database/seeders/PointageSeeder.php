<?php

namespace Database\Seeders;

use App\Models\Pointage;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class PointageSeeder extends Seeder
{
    // Mois seedé : mars 2026
    const MOIS  = 3;
    const ANNEE = 2026;

    // Journée de référence = 8h
    const HEURES_JOURNEE = 8.0;

    public function run(): void
    {
        $employes = User::where('role', 'employe')->take(5)->get();

        if ($employes->isEmpty()) {
            $this->command->warn('Aucun employé trouvé. Lancez d\'abord le DatabaseSeeder complet.');
            return;
        }

        $validateur = User::whereIn('role', ['admin', 'rh'])->first();

        $debut = Carbon::createFromDate(self::ANNEE, self::MOIS, 1);
        $fin   = $debut->copy()->endOfMonth();

        // Scénarios : [heure_arrivee, heure_depart, heure_debut_pause, heure_fin_pause]
        $scenarios = [
            'normal'       => ['08:00', '17:00', '12:00', '13:00'],  // 8h pile
            'avance'       => ['07:30', '17:00', '12:00', '13:00'],  // 8.5h → +0.5h sup
            'tardif'       => ['08:30', '17:00', '12:00', '13:00'],  // 7.5h → -0.5h manquant
            'sup_soir'     => ['08:00', '17:30', '12:00', '13:00'],  // 8.5h → +0.5h sup
            'demi_journee' => ['08:00', '12:00', null,    null   ],   // 4h   → -4h manquant
            'long'         => ['07:00', '17:00', '12:00', '13:00'],  // 9h   → +1h sup
            'conge'        => null, // type_jour = CONGE
            'ferie'        => null, // type_jour = FERIE
        ];

        // Plan par employé — 22 jours ouvrés en mars 2026 (du lundi 2 au mardi 31)
        $plans = [
            0 => [ // Employé 1 — régulier, 1 absence, 1 congé
                'normal','normal','normal','normal','normal',
                'normal','avance','normal','normal','normal',
                'avance','normal','normal','normal','normal',
                'normal','normal','absent','normal','normal',
                'conge','normal',
            ],
            1 => [ // Employé 2 — retards et absences
                'normal','tardif','normal','normal','avance',
                'normal','normal','absent','normal','tardif',
                'normal','normal','absent','normal','normal',
                'sup_soir','normal','normal','tardif','normal',
                'normal','absent',
            ],
            2 => [ // Employé 3 — beaucoup d'heures sup
                'avance','long','normal','sup_soir','normal',
                'long','avance','normal','sup_soir','long',
                'normal','avance','normal','long','normal',
                'sup_soir','normal','avance','long','normal',
                'normal','sup_soir',
            ],
            3 => [ // Employé 4 — demi-journées et absences
                'normal','normal','demi_journee','normal','absent',
                'normal','normal','normal','demi_journee','normal',
                'absent','normal','normal','normal','demi_journee',
                'normal','absent','normal','normal','normal',
                'demi_journee','normal',
            ],
            4 => [ // Employé 5 — profil mixte avec un férié
                'normal','avance','normal','absent','normal',
                'sup_soir','normal','ferie','avance','tardif',
                'normal','normal','long','normal','absent',
                'normal','tardif','normal','normal','sup_soir',
                'normal','normal',
            ],
        ];

        Pointage::query()->delete();

        foreach ($employes as $empIndex => $employe) {
            $plan      = $plans[$empIndex] ?? $plans[0];
            $jourOuvre = 0;

            for ($date = $debut->copy(); $date->lte($fin); $date->addDay()) {
                if ($date->isWeekend()) {
                    continue;
                }

                $scenario  = $plan[$jourOuvre] ?? 'normal';
                $jourOuvre++;

                $estPasse = $date->lt(Carbon::today()->subDays(2));
                $statut   = $estPasse ? 'VALIDE' : 'EN_ATTENTE';
                $validePar = $estPasse ? $validateur?->id : null;
                $dateValid = $estPasse ? $date->copy()->setTime(18, 0) : null;

                // ── Absent ──────────────────────────────────────────
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

                // ── Congé ───────────────────────────────────────────
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

                // ── Férié ───────────────────────────────────────────
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

                // ── Jour travaillé ──────────────────────────────────
                [$arrivee, $depart, $debutPause, $finPause] = $scenarios[$scenario];

                $totalMin = Carbon::createFromTimeString($arrivee)
                    ->diffInMinutes(Carbon::createFromTimeString($depart));

                $pauseMin = 0;
                if ($debutPause && $finPause) {
                    $pauseMin = Carbon::createFromTimeString($debutPause)
                        ->diffInMinutes(Carbon::createFromTimeString($finPause));
                } elseif ($totalMin >= 360) {
                    $pauseMin = 60; // pause auto 1h si >= 6h de présence
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

        $this->command->info('Pointages de mars 2026 générés pour ' . $employes->count() . ' employé(s).');
    }
}
