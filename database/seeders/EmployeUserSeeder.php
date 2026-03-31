<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class EmployeUserSeeder extends Seeder
{
    public function run(): void
    {
        $services = DB::table('services')->pluck('id', 'nom');

        // ─── Données des employés ────────────────────────────────────────
        // [matricule, nom, prenom, email, phone, service, poste, date_embauche, role]
        $employes = [
            // --- Direction Générale ---
            ['DG0101-01', 'KOUMBA', 'Jean-Pierre', 'jp.koumba@grh.test',  '0600000001', 'Cabinet DG',            'Directeur Général',          '2018-01-15', 'dg'],
            ['CAB0101-01','ONDO',   'Marie',        'marie.ondo@grh.test', '0600000002', 'Cabinet DG',            'Assistante de Direction',    '2019-03-10', 'employe'],

            // --- Ressources Humaines ---
            ['RH0101-01', 'NZAMBA', 'Paul',         'paul.nzamba@grh.test','0600000003', 'Recrutement & Formation','Responsable RH',             '2019-06-01', 'rh'],
            ['RH0101-02', 'MBOULA', 'Sandrine',     'sandrine.m@grh.test', '0600000004', 'Recrutement & Formation','Chargée de Recrutement',     '2020-02-15', 'employe'],
            ['RH0101-03', 'ENGOUNG','Thierry',       'thierry.e@grh.test',  '0600000005', 'Paie & Administration',  'Gestionnaire Paie',          '2020-09-01', 'employe'],

            // --- Informatique ---
            ['IT0101-01', 'OBAME',  'Cédric',       'cedric.obame@grh.test','0600000006','Développement',          'Responsable Développement',  '2019-09-01', 'employe'],
            ['IT0101-02', 'NKOGHE', 'Ariel',        'ariel.nk@grh.test',   '0600000007','Développement',          'Développeur Senior',         '2021-01-10', 'employe'],
            ['IT0101-03', 'MEZUI',  'Christelle',   'christelle.m@grh.test','0600000008','Infrastructure & Sécurité','Administratrice Système',  '2021-06-01', 'employe'],

            // --- Finance ---
            ['FIN0101-01','BIVEGHE','Léa',           'lea.biveghe@grh.test','0600000009', 'Comptabilité Générale',  'Responsable Comptable',      '2018-11-01', 'employe'],
            ['FIN0101-02','ASSEKO', 'Roger',         'roger.a@grh.test',    '0600000010','Contrôle de Gestion',    'Contrôleur de Gestion',      '2020-04-01', 'employe'],

            // --- Commercial ---
            ['COM0101-01','MINTSA', 'Béatrice',      'beatrice.m@grh.test', '0600000011','Ventes',                 'Responsable Commercial',     '2019-07-15', 'employe'],
            ['COM0101-02','NDONG',  'Franck',        'franck.nd@grh.test',  '0600000012','Ventes',                 'Commercial Terrain',         '2022-03-01', 'employe'],
            ['COM0101-03','EBOUMA', 'Gaelle',        'gaelle.eb@grh.test',  '0600000013','Marketing',              'Chargée Marketing Digital',  '2022-07-01', 'employe'],
        ];

        foreach ($employes as [$matricule, $nom, $prenom, $email, $phone, $serviceName, $poste, $dateEmbauche, $role]) {
            // Créer l'employé
            $employeId = DB::table('employes')->insertGetId([
                'matricule'    => $matricule,
                'nom'          => $nom,
                'prenom'       => $prenom,
                'email'        => $email,
                'phone'        => $phone,
                'service_id'   => $services[$serviceName],
                'poste'        => $poste,
                'date_embauche'=> $dateEmbauche,
                'created_at'   => now(),
                'updated_at'   => now(),
            ]);

            // Créer le compte utilisateur lié
            $userId = DB::table('users')->insertGetId([
                'employe_id'         => $employeId,
                'login'              => $matricule,
                'email'              => $email,
                'password'           => Hash::make('password123'),
                'role'               => $role,
                'must_change_password'=> false,
                'date_creation'      => now(),
                'created_at'         => now(),
                'updated_at'         => now(),
            ]);

            // Mettre à jour l'employé avec user_id
            DB::table('employes')->where('id', $employeId)->update(['user_id' => $userId]);
        }

        // Compte admin séparé (sans profil employé)
        DB::table('users')->insert([
            'employe_id'          => null,
            'login'               => 'admin',
            'email'               => 'admin@grh.test',
            'password'            => Hash::make('admin1234'),
            'role'                => 'admin',
            'must_change_password'=> false,
            'date_creation'       => now(),
            'created_at'          => now(),
            'updated_at'          => now(),
        ]);
    }
}
