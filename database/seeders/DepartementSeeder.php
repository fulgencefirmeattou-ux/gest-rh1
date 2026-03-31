<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DepartementSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('departements')->insert([
            [
                'nom'         => 'Direction Générale',
                'description' => 'Pilotage stratégique et coordination de l\'entreprise',
                'created_at'  => now(),
                'updated_at'  => now(),
            ],
            [
                'nom'         => 'Ressources Humaines',
                'description' => 'Gestion du personnel, recrutement et formation',
                'created_at'  => now(),
                'updated_at'  => now(),
            ],
            [
                'nom'         => 'Informatique',
                'description' => 'Développement logiciel, infrastructure et support technique',
                'created_at'  => now(),
                'updated_at'  => now(),
            ],
            [
                'nom'         => 'Finance & Comptabilité',
                'description' => 'Gestion financière, comptabilité et contrôle de gestion',
                'created_at'  => now(),
                'updated_at'  => now(),
            ],
            [
                'nom'         => 'Commercial',
                'description' => 'Ventes, prospection et relation client',
                'created_at'  => now(),
                'updated_at'  => now(),
            ],
        ]);
    }
}
