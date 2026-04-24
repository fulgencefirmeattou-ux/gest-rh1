<?php

namespace Database\Seeders;

use App\Models\Absence;
use App\Models\Employe;
use App\Models\User;
use Illuminate\Database\Seeder;

class AbsenceSeeder extends Seeder
{
    public function run(): void
    {
        Absence::query()->delete();

        $employes = Employe::orderBy('id')->get();

        if ($employes->isEmpty()) {
            $this->command->warn('Aucun employé trouvé. Lancez d\'abord DatabaseSeeder.');
            return;
        }

        $traitePar = User::whereIn('role', ['admin', 'rh'])->value('id');

        // [index_employe(0-based), type, date_absence, date_fin, heure_debut, heure_fin, motif, statut]
        $absences = [
            // Maladies validées
            [1,  'maladie',               '2026-01-20', '2026-01-22', null,    null,    'Grippe avec fièvre.',             'validee'],
            [4,  'maladie',               '2026-02-10', '2026-02-11', null,    null,    'Indisposition gastrique.',         'validee'],
            [11, 'maladie',               '2026-03-02', '2026-03-03', null,    null,    'Migraine invalidante.',            'validee'],

            // Retards validés
            [0,  'retard',                '2026-02-05', null,         '08:00', '09:15', 'Embouteillage sur la voie rapide.','validee'],
            [3,  'retard',                '2026-03-12', null,         '08:00', '08:45', 'Panne de véhicule.',               'validee'],
            [6,  'retard',                '2026-04-02', null,         '08:00', '09:00', 'Transport en commun en panne.',    'validee'],
            [9,  'retard',                '2026-04-15', null,         '08:00', '08:30', 'Retard de bus.',                   'validee'],

            // Permissions courtes validées
            [2,  'permission_courte',     '2026-01-28', null,         '10:00', '12:00', 'Démarche à la mairie.',            'validee'],
            [7,  'permission_courte',     '2026-03-18', null,         '14:00', '16:00', 'RDV chez le médecin.',             'validee'],
            [12, 'permission_courte',     '2026-04-08', null,         '09:00', '11:00', 'Rendez-vous administratif.',       'validee'],

            // Absences non justifiées (en attente)
            [5,  'absence_non_justifiee', '2026-04-16', '2026-04-17', null,    null,    null,                               'en_attente'],
            [10, 'absence_non_justifiee', '2026-04-21', null,         null,    null,    null,                               'en_attente'],

            // Rendez-vous médicaux
            [8,  'rendez_vous',           '2026-02-26', null,         '11:00', '13:00', 'Consultation spécialiste.',        'validee'],
            [13, 'rendez_vous',           '2026-03-25', null,         '15:00', '16:30', 'Bilan de santé annuel.',           'validee'],

            // Rejetée
            [14, 'autre',                 '2026-03-30', '2026-03-31', null,    null,    'Raison personnelle non précisée.', 'rejetee'],
        ];

        $count = 0;
        foreach ($absences as [$idx, $type, $date, $dateFin, $hDeb, $hFin, $motif, $statut]) {
            $employe = $employes->get($idx);
            if (! $employe) continue;

            Absence::create([
                'employe_id'        => $employe->id,
                'type_absence'      => $type,
                'date_absence'      => $date,
                'date_fin_absence'  => $dateFin,
                'heure_debut'       => $hDeb,
                'heure_fin'         => $hFin,
                'motif'             => $motif,
                'statut'            => $statut,
                'commentaire_rh'    => $statut === 'rejetee' ? 'Motif insuffisant.' : null,
                'traite_par'        => in_array($statut, ['validee', 'rejetee']) ? $traitePar : null,
                'traite_le'         => in_array($statut, ['validee', 'rejetee']) ? now() : null,
            ]);

            $count++;
        }

        $this->command->info("✓ {$count} absence(s) générées.");
    }
}
