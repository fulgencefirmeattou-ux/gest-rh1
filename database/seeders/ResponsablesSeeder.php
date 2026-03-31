<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ResponsablesSeeder extends Seeder
{
    public function run(): void
    {
        $employes = DB::table('employes')->pluck('id', 'matricule');
        $depts    = DB::table('departements')->pluck('id', 'nom');
        $services = DB::table('services')->pluck('id', 'nom');

        // Responsables de départements
        DB::table('departements')
            ->where('id', $depts['Direction Générale'])
            ->update(['responsable_id' => $employes['DG0101-01']]);

        DB::table('departements')
            ->where('id', $depts['Ressources Humaines'])
            ->update(['responsable_id' => $employes['RH0101-01']]);

        DB::table('departements')
            ->where('id', $depts['Informatique'])
            ->update(['responsable_id' => $employes['IT0101-01']]);

        DB::table('departements')
            ->where('id', $depts['Finance & Comptabilité'])
            ->update(['responsable_id' => $employes['FIN0101-01']]);

        DB::table('departements')
            ->where('id', $depts['Commercial'])
            ->update(['responsable_id' => $employes['COM0101-01']]);

        // Responsables de services
        DB::table('services')
            ->where('id', $services['Recrutement & Formation'])
            ->update(['responsable_id' => $employes['RH0101-01']]);

        DB::table('services')
            ->where('id', $services['Paie & Administration'])
            ->update(['responsable_id' => $employes['RH0101-03']]);

        DB::table('services')
            ->where('id', $services['Développement'])
            ->update(['responsable_id' => $employes['IT0101-01']]);

        DB::table('services')
            ->where('id', $services['Infrastructure & Sécurité'])
            ->update(['responsable_id' => $employes['IT0101-03']]);

        DB::table('services')
            ->where('id', $services['Comptabilité Générale'])
            ->update(['responsable_id' => $employes['FIN0101-01']]);

        DB::table('services')
            ->where('id', $services['Contrôle de Gestion'])
            ->update(['responsable_id' => $employes['FIN0101-02']]);

        DB::table('services')
            ->where('id', $services['Ventes'])
            ->update(['responsable_id' => $employes['COM0101-01']]);

        DB::table('services')
            ->where('id', $services['Marketing'])
            ->update(['responsable_id' => $employes['COM0101-03']]);
    }
}
