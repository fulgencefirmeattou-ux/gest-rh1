<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class PresenceAbsenceSeeder extends Seeder
{
    public function run(): void
    {
        $employes = DB::table('employes')->get();

        // Générer les présences pour les 3 derniers mois
        $start = Carbon::now()->subMonths(3)->startOfMonth();
        $end   = Carbon::now()->subMonth()->endOfMonth();

        foreach ($employes as $employe) {
            $current = $start->copy();

            while ($current->lte($end)) {
                // Ignorer week-ends
                if ($current->isWeekday()) {
                    $heuresTravaillees = 8.0;
                    $heuresSup = 0.0;

                    // 10% de chance d'heures sup
                    if (rand(1, 10) === 1) {
                        $heuresSup = rand(1, 3) * 0.5;
                    }

                    DB::table('presences')->insert([
                        'employe_id'        => $employe->id,
                        'date'              => $current->toDateString(),
                        'heures_travaillees'=> $heuresTravaillees,
                        'heures_sup'        => $heuresSup,
                        'created_at'        => now(),
                        'updated_at'        => now(),
                    ]);
                }

                $current->addDay();
            }
        }

        // Quelques absences
        $absences = [
            ['matricule' => 'IT0101-02', 'type' => 'maladie',              'date' => '2026-01-08', 'motif' => 'Grippe', 'statut' => 'validee_service'],
            ['matricule' => 'COM0101-02','type' => 'retard',               'date' => '2026-01-15', 'motif' => 'Problème de transport', 'statut' => 'validee_service'],
            ['matricule' => 'FIN0101-02','type' => 'rendez_vous',          'date' => '2026-02-03', 'motif' => 'Rendez-vous médical', 'statut' => 'en_attente'],
            ['matricule' => 'RH0101-02', 'type' => 'absence_non_justifiee','date' => '2026-02-10', 'motif' => null, 'statut' => 'en_attente'],
        ];

        $employesMap = DB::table('employes')->pluck('id', 'matricule');

        foreach ($absences as $abs) {
            DB::table('absences')->insert([
                'employe_id'    => $employesMap[$abs['matricule']],
                'type_absence'  => $abs['type'],
                'date_absence'  => $abs['date'],
                'motif'         => $abs['motif'],
                'justificatif'  => null,
                'statut'        => $abs['statut'],
                'created_at'    => now(),
                'updated_at'    => now(),
            ]);
        }
    }
}
