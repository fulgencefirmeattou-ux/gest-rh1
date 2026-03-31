<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ServiceSeeder extends Seeder
{
    public function run(): void
    {
        // Récupérer les IDs des départements par nom
        $depts = DB::table('departements')->pluck('id', 'nom');

        DB::table('services')->insert([
            // Direction Générale
            [
                'nom'            => 'Cabinet DG',
                'departement_id' => $depts['Direction Générale'],
                'description'    => 'Secrétariat et assistanat de direction',
                'created_at'     => now(),
                'updated_at'     => now(),
            ],
            // Ressources Humaines
            [
                'nom'            => 'Recrutement & Formation',
                'departement_id' => $depts['Ressources Humaines'],
                'description'    => 'Gestion des recrutements et plans de formation',
                'created_at'     => now(),
                'updated_at'     => now(),
            ],
            [
                'nom'            => 'Paie & Administration',
                'departement_id' => $depts['Ressources Humaines'],
                'description'    => 'Traitement des salaires et gestion administrative RH',
                'created_at'     => now(),
                'updated_at'     => now(),
            ],
            // Informatique
            [
                'nom'            => 'Développement',
                'departement_id' => $depts['Informatique'],
                'description'    => 'Conception et développement des applications',
                'created_at'     => now(),
                'updated_at'     => now(),
            ],
            [
                'nom'            => 'Infrastructure & Sécurité',
                'departement_id' => $depts['Informatique'],
                'description'    => 'Gestion des serveurs, réseaux et cybersécurité',
                'created_at'     => now(),
                'updated_at'     => now(),
            ],
            // Finance & Comptabilité
            [
                'nom'            => 'Comptabilité Générale',
                'departement_id' => $depts['Finance & Comptabilité'],
                'description'    => 'Tenue des livres comptables et clôtures',
                'created_at'     => now(),
                'updated_at'     => now(),
            ],
            [
                'nom'            => 'Contrôle de Gestion',
                'departement_id' => $depts['Finance & Comptabilité'],
                'description'    => 'Reporting, budget et analyse de performance',
                'created_at'     => now(),
                'updated_at'     => now(),
            ],
            // Commercial
            [
                'nom'            => 'Ventes',
                'departement_id' => $depts['Commercial'],
                'description'    => 'Gestion du portefeuille clients et des ventes',
                'created_at'     => now(),
                'updated_at'     => now(),
            ],
            [
                'nom'            => 'Marketing',
                'departement_id' => $depts['Commercial'],
                'description'    => 'Communication, marketing digital et événementiel',
                'created_at'     => now(),
                'updated_at'     => now(),
            ],
        ]);
    }
}
