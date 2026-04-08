<?php

namespace Database\Seeders;

use App\Models\Departement;
use App\Models\Employe;
use App\Models\Poste;
use App\Models\TypeContrat;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // ── 1. Permissions & Rôles ─────────────────────────────────
        $this->call(PermissionSeeder::class);

        // ── 2. Postes ──────────────────────────────────────────────
        $postes = [
            ['name' => 'Développeur Web',        'description' => 'Développement et maintenance des applications web'],
            ['name' => 'Comptable',               'description' => 'Gestion de la comptabilité générale'],
            ['name' => 'Chargé RH',               'description' => 'Gestion des ressources humaines'],
            ['name' => 'Responsable Marketing',   'description' => 'Stratégie et communication marketing'],
            ['name' => 'Assistant Administratif', 'description' => 'Soutien administratif et logistique'],
        ];
        foreach ($postes as $p) {
            Poste::firstOrCreate(['name' => $p['name']], $p);
        }

        // ── 3. Types de contrats ───────────────────────────────────
        $contrats = [
            ['name' => 'CDI',             'description' => 'Contrat à Durée Indéterminée'],
            ['name' => 'CDD',             'description' => 'Contrat à Durée Déterminée'],
            ['name' => 'Stage',           'description' => 'Convention de stage'],
            ['name' => 'Freelance',       'description' => 'Prestation indépendante'],
            ['name' => 'Alternance',      'description' => 'Contrat en alternance'],
        ];
        foreach ($contrats as $c) {
            TypeContrat::firstOrCreate(['name' => $c['name']], $c);
        }

        // ── 4. Départements ────────────────────────────────────────
        $deps = [
            ['nom' => 'Informatique',    'description' => 'Direction des systèmes d\'information'],
            ['nom' => 'Finance',         'description' => 'Direction financière et comptable'],
            ['nom' => 'Ressources Humaines', 'description' => 'Gestion du capital humain'],
            ['nom' => 'Marketing',       'description' => 'Communication et développement commercial'],
        ];
        foreach ($deps as $d) {
            Departement::firstOrCreate(['nom' => $d['nom']], $d);
        }

        // ── 5. Utilisateur Admin ───────────────────────────────────
        $admin = User::firstOrCreate(
            ['email' => 'admin@gestRH.com'],
            [
                'nom'    => 'Admin',
                'prenom' => 'Super',
                'password' => Hash::make('password'),
                'role'   => 'admin',
                'login'  => 'ADM000001',
            ]
        );
        $admin->assignRole('admin');

        // ── 6. Utilisateur RH ──────────────────────────────────────
        $rh = User::firstOrCreate(
            ['email' => 'rh@gestRH.com'],
            [
                'nom'    => 'Dupont',
                'prenom' => 'Marie',
                'password' => Hash::make('password'),
                'role'   => 'rh',
                'login'  => 'RH000001',
            ]
        );
        $rh->assignRole('rh');

        // ── 7. 5 Employés ──────────────────────────────────────────
        $employes = [
            [
                'nom'            => 'Koné',
                'prenom'         => 'Abou',
                'email'          => 'a.kone@gestRH.com',
                'telephone'      => '+225 07 01 02 03',
                'poste'          => 'Développeur Web',
                'departement'    => 'Informatique',
                'type_contrat'   => 'CDI',
                'date_embauche'  => '2023-01-15',
                'salaire'        => 650000,
                'civilite'       => 'M.',
                'nationalite'    => 'Ivoirienne',
                'situation_matrimoniale' => 'Marié(e)',
                'nombre_enfants' => 2,
                'date_naissance' => '1990-05-12',
                'lieu_naissance' => 'Abidjan',
                'adresse'        => 'Cocody, Abidjan',
            ],
            [
                'nom'            => 'Traoré',
                'prenom'         => 'Fatoumata',
                'email'          => 'f.traore@gestRH.com',
                'telephone'      => '+225 07 11 22 33',
                'poste'          => 'Comptable',
                'departement'    => 'Finance',
                'type_contrat'   => 'CDI',
                'date_embauche'  => '2022-03-01',
                'salaire'        => 580000,
                'civilite'       => 'Mme',
                'nationalite'    => 'Ivoirienne',
                'situation_matrimoniale' => 'Célibataire',
                'nombre_enfants' => 0,
                'date_naissance' => '1993-08-22',
                'lieu_naissance' => 'Bouaké',
                'adresse'        => 'Yopougon, Abidjan',
            ],
            [
                'nom'            => 'Coulibaly',
                'prenom'         => 'Ibrahim',
                'email'          => 'i.coulibaly@gestRH.com',
                'telephone'      => '+225 05 44 55 66',
                'poste'          => 'Responsable Marketing',
                'departement'    => 'Marketing',
                'type_contrat'   => 'CDI',
                'date_embauche'  => '2021-06-01',
                'salaire'        => 720000,
                'civilite'       => 'M.',
                'nationalite'    => 'Ivoirienne',
                'situation_matrimoniale' => 'Marié(e)',
                'nombre_enfants' => 3,
                'date_naissance' => '1987-11-03',
                'lieu_naissance' => 'Korhogo',
                'adresse'        => 'Marcory, Abidjan',
            ],
            [
                'nom'            => 'Bamba',
                'prenom'         => 'Aïcha',
                'email'          => 'a.bamba@gestRH.com',
                'telephone'      => '+225 01 66 77 88',
                'poste'          => 'Chargé RH',
                'departement'    => 'Ressources Humaines',
                'type_contrat'   => 'CDD',
                'date_embauche'  => '2024-01-10',
                'salaire'        => 420000,
                'civilite'       => 'Mlle',
                'nationalite'    => 'Ivoirienne',
                'situation_matrimoniale' => 'Célibataire',
                'nombre_enfants' => 0,
                'date_naissance' => '1997-02-14',
                'lieu_naissance' => 'San-Pédro',
                'adresse'        => 'Riviera, Abidjan',
            ],
            [
                'nom'            => 'Diallo',
                'prenom'         => 'Moussa',
                'email'          => 'm.diallo@gestRH.com',
                'telephone'      => '+225 07 99 00 11',
                'poste'          => 'Assistant Administratif',
                'departement'    => 'Ressources Humaines',
                'type_contrat'   => 'Stage',
                'date_embauche'  => '2025-09-01',
                'salaire'        => 180000,
                'civilite'       => 'M.',
                'nationalite'    => 'Guinéenne',
                'situation_matrimoniale' => 'Célibataire',
                'nombre_enfants' => 0,
                'date_naissance' => '2001-07-30',
                'lieu_naissance' => 'Conakry',
                'adresse'        => 'Adjamé, Abidjan',
            ],
        ];

        foreach ($employes as $index => $data) {
            $poste      = Poste::where('name', $data['poste'])->first();
            $dep        = Departement::where('nom', $data['departement'])->first();
            $contrat    = TypeContrat::where('name', $data['type_contrat'])->first();

            // Générer le matricule
            $prefix   = strtoupper(substr($data['nom'], 0, 3));
            $date     = Carbon::parse($data['date_embauche'])->format('dmy');
            $counter  = str_pad($index + 1, 2, '0', STR_PAD_LEFT);
            $matricule = $prefix . $date . '-' . $counter;

            // Créer le compte utilisateur
            $user = User::firstOrCreate(
                ['email' => $data['email']],
                [
                    'nom'      => $data['nom'],
                    'prenom'   => $data['prenom'],
                    'password' => Hash::make('password'),
                    'role'     => 'employe',
                    'login'    => $matricule,
                ]
            );
            $user->assignRole('employe');

            // Créer la fiche employé
            $employe = Employe::firstOrCreate(
                ['email' => $data['email']],
                [
                    'matricule'              => $matricule,
                    'nom'                    => $data['nom'],
                    'prenom'                 => $data['prenom'],
                    'civilite'               => $data['civilite'],
                    'nationalite'            => $data['nationalite'],
                    'situation_matrimoniale' => $data['situation_matrimoniale'],
                    'nombre_enfants'         => $data['nombre_enfants'],
                    'date_naissance'         => $data['date_naissance'],
                    'lieu_naissance'         => $data['lieu_naissance'],
                    'telephone'              => $data['telephone'],
                    'adresse'                => $data['adresse'],
                    'salaire'                => $data['salaire'],
                    'departement_id'         => $dep->id,
                    'poste_id'               => $poste->id,
                    'type_contrat_id'        => $contrat->id,
                    'user_id'                => $user->id,
                    'date_embauche'          => $data['date_embauche'],
                ]
            );

            // Lier l'employé au compte utilisateur
            $user->update(['employe_id' => $employe->id]);
        }

        // ── 8. Pointages d'un mois ─────────────────────────────────
        $this->call(PointageSeeder::class);

        $this->command->info('✓ Seed terminé. Comptes créés :');
        $this->command->info('  admin@gestRH.com    → mot de passe : password  (rôle : admin)');
        $this->command->info('  rh@gestRH.com       → mot de passe : password  (rôle : rh)');
        $this->command->info('  a.kone@gestRH.com   → mot de passe : password  (rôle : employe)');
        $this->command->info('  f.traore@gestRH.com → mot de passe : password  (rôle : employe)');
        $this->command->info('  i.coulibaly@gestRH.com → mot de passe : password  (rôle : employe)');
        $this->command->info('  a.bamba@gestRH.com  → mot de passe : password  (rôle : employe)');
        $this->command->info('  m.diallo@gestRH.com → mot de passe : password  (rôle : employe)');
    }
}
