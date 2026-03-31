<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DemandeCongeSeeder extends Seeder
{
    public function run(): void
    {
        $employes = DB::table('employes')->get()->keyBy('matricule');

        // Responsables pour l'historique
        $respService = $employes['RH0101-01']->id;
        $respDept    = $employes['RH0101-01']->id;
        $dg          = $employes['DG0101-01']->id;

        // Demande 1 : congé annuel approuvé
        $d1 = DB::table('demande_conges')->insertGetId([
            'employe_id'          => $employes['IT0101-02']->id,
            'type_conge'          => 'annuel',
            'date_debut_conge'    => '2025-12-23',
            'date_fin_conge'      => '2026-01-05',
            'raison'              => 'Congés de fin d\'année',
            'statut'              => 'approuvee',
            'date_retour'         => '2026-01-06',
            'commentaire'         => null,
            'created_at'          => '2025-12-10 09:00:00',
            'updated_at'          => '2025-12-18 14:30:00',
        ]);
        $this->addHistorique($d1, $respService, 'service',     'approuvee',          null, '2025-12-11');
        $this->addHistorique($d1, $respDept,    'departement', 'approuvee',          null, '2025-12-13');
        $this->addHistorique($d1, $dg,          'dg',          'approuvee',          null, '2025-12-18');

        // Demande 2 : congé spécial approuvé
        $d2 = DB::table('demande_conges')->insertGetId([
            'employe_id'          => $employes['COM0101-02']->id,
            'type_conge'          => 'special',
            'date_debut_conge'    => '2025-11-10',
            'date_fin_conge'      => '2025-11-12',
            'raison'              => 'Mariage',
            'statut'              => 'approuvee',
            'date_retour'         => '2025-11-13',
            'commentaire'         => null,
            'created_at'          => '2025-11-03 08:00:00',
            'updated_at'          => '2025-11-07 16:00:00',
        ]);
        $this->addHistorique($d2, $respService, 'service',     'approuvee', null, '2025-11-04');
        $this->addHistorique($d2, $respDept,    'departement', 'approuvee', null, '2025-11-05');
        $this->addHistorique($d2, $dg,          'dg',          'approuvee', null, '2025-11-07');

        // Demande 3 : en attente au niveau service
        DB::table('demande_conges')->insertGetId([
            'employe_id'          => $employes['FIN0101-02']->id,
            'type_conge'          => 'annuel',
            'date_debut_conge'    => '2026-02-17',
            'date_fin_conge'      => '2026-02-28',
            'raison'              => 'Congés personnels',
            'statut'              => 'attente_service',
            'date_retour'         => null,
            'commentaire'         => null,
            'created_at'          => '2026-01-15 10:30:00',
            'updated_at'          => '2026-01-15 10:30:00',
        ]);

        // Demande 4 : rejetée
        $d4 = DB::table('demande_conges')->insertGetId([
            'employe_id'          => $employes['CAB0101-01']->id,
            'type_conge'          => 'exceptionnel',
            'date_debut_conge'    => '2025-10-06',
            'date_fin_conge'      => '2025-10-10',
            'raison'              => 'Raisons personnelles',
            'statut'              => 'rejetee',
            'date_retour'         => null,
            'commentaire'         => 'Période trop chargée pour la direction',
            'created_at'          => '2025-09-25 09:00:00',
            'updated_at'          => '2025-09-30 11:00:00',
        ]);
        $this->addHistorique($d4, $respService, 'service', 'approuvee', null, '2025-09-26');
        $this->addHistorique($d4, $dg, 'dg', 'rejetee', 'Période trop chargée pour la direction', '2025-09-30');

        // Demande 5 : en attente DG
        $d5 = DB::table('demande_conges')->insertGetId([
            'employe_id'          => $employes['RH0101-02']->id,
            'type_conge'          => 'annuel',
            'date_debut_conge'    => '2026-03-10',
            'date_fin_conge'      => '2026-03-21',
            'raison'              => 'Vacances familiales',
            'statut'              => 'attente_dg',
            'date_retour'         => null,
            'commentaire'         => null,
            'created_at'          => '2026-02-20 08:00:00',
            'updated_at'          => '2026-02-25 17:00:00',
        ]);
        $this->addHistorique($d5, $respService, 'service',     'approuvee', null, '2026-02-21');
        $this->addHistorique($d5, $respDept,    'departement', 'approuvee', null, '2026-02-25');
    }

    private function addHistorique(int $demandeId, int $responsableId, string $etape, string $decision, ?string $commentaire, string $date): void
    {
        DB::table('historique_conges')->insert([
            'demande_conge_id' => $demandeId,
            'responsable_id'   => $responsableId,
            'etape'            => $etape,
            'decision'         => $decision,
            'commentaire'      => $commentaire,
            'approved_at'      => $date,
            'created_at'       => now(),
            'updated_at'       => now(),
        ]);
    }
}
