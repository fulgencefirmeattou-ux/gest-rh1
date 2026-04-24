<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class InfoEntrepriseSeeder extends Seeder
{
    public function run(): void
    {
        if (DB::table('info_entreprise')->exists()) {
            return;
        }

        DB::table('info_entreprise')->insert([
            'nom'              => 'GestRH Consulting',
            'adresse'          => 'Plateau, Avenue Noguès, Immeuble SIPIM',
            'siege_social'     => 'Abidjan, Côte d\'Ivoire',
            'num_cnps'         => 'CNPS-123456-CI',
            'num_contribuable' => 'CI-2020-123456-A',
            'email'            => 'contact@gestrh.ci',
            'telephone'        => '+225 27 20 20 20 20',
            'logo'             => null,
            'created_at'       => now(),
            'updated_at'       => now(),
        ]);
    }
}
