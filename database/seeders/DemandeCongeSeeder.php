<?php

namespace Database\Seeders;

use App\Models\DemandeConge;
use App\Models\Employe;
use App\Models\HistoriqueConge;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class DemandeCongeSeeder extends Seeder
{
    public function run(): void
    {
        DemandeConge::query()->delete();
        HistoriqueConge::query()->delete();

        $employes = Employe::with('user')->orderBy('id')->get();

        if ($employes->isEmpty()) {
            $this->command->warn('Aucun employé trouvé. Lancez d\'abord DatabaseSeeder.');
            return;
        }

        $approbateur = User::whereIn('role', ['admin', 'rh'])->first();

        // Données : [index_employe(0-based), type_conge, debut, fin, statut, commentaire]
        $demandes = [
            // Congés payés approuvés
            [0,  'conge_paye',          '2026-01-13', '2026-01-24', 'approuvee',              'Congés annuels validés.'],
            [4,  'conge_paye',          '2026-02-02', '2026-02-13', 'approuvee',              'Congés annuels.'],
            [11, 'conge_paye',          '2026-03-16', '2026-03-27', 'approuvee',              'Congés annuels approuvés.'],

            // Maladie avec justificatif
            [2,  'maladie',             '2026-02-17', '2026-02-21', 'approuvee',              'Certificat médical fourni.'],
            [6,  'maladie',             '2026-03-10', '2026-03-12', 'approuvee',              'Arrêt médical validé.'],

            // Permission courte approuvée
            [1,  'permission_courte',   '2026-02-24', '2026-02-24', 'approuvee',              'Démarche administrative.'],
            [8,  'permission_courte',   '2026-04-07', '2026-04-07', 'approuvee',              'RDV médical.'],

            // Congé exceptionnel
            [3,  'exceptionnel',        '2026-03-03', '2026-03-05', 'approuvee',              'Décès d\'un parent proche.'],

            // En cours de validation (circuit multi-niveaux)
            [7,  'conge_paye',          '2026-04-27', '2026-05-08', 'attente_departement',    null],
            [9,  'permission_courte',   '2026-04-24', '2026-04-24', 'attente_service',        null],
            [13, 'conge_paye',          '2026-05-04', '2026-05-15', 'attente_dg',             null],

            // Rejetées
            [5,  'conge_paye',          '2026-03-23', '2026-04-03', 'rejetee',                'Effectif insuffisant sur la période.'],
            [10, 'permission_courte',   '2026-04-14', '2026-04-14', 'rejetee',                'Demande non justifiée.'],

            // Modification demandée
            [14, 'conge_paye',          '2026-04-20', '2026-05-01', 'modification_demandee',  'Veuillez décaler d\'une semaine.'],
        ];

        $count = 0;
        foreach ($demandes as [$idx, $type, $debut, $fin, $statut, $commentaire]) {
            $employe = $employes->get($idx);
            if (! $employe) continue;

            $dateRetour = Carbon::parse($fin)->addDays(1)->toDateString();

            $demande = DemandeConge::create([
                'employe_id'        => $employe->id,
                'type_conge'        => $type,
                'date_debut_conge'  => $debut,
                'date_fin_conge'    => $fin,
                'date_retour'       => $dateRetour,
                'raison'            => $commentaire,
                'statut'            => $statut,
                'commentaire'       => $commentaire,
            ]);

            // Historique pour les demandes traitées
            if (in_array($statut, ['approuvee', 'rejetee', 'modification_demandee']) && $approbateur) {
                $decision = match ($statut) {
                    'approuvee'             => 'approuve',
                    'rejetee'               => 'rejete',
                    'modification_demandee' => 'modification_demandee',
                    default                 => 'approuve',
                };

                HistoriqueConge::create([
                    'demande_conge_id' => $demande->id,
                    'approver_id'      => $approbateur->id,
                    'etape'            => 'dg_rh',
                    'decision'         => $decision,
                    'commentaire'      => $commentaire,
                    'approved_at'      => now(),
                ]);
            }

            $count++;
        }

        $this->command->info("✓ {$count} demande(s) de congé générées.");
    }
}
