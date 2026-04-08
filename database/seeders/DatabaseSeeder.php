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
        $postesData = [
            ['name' => 'Développeur Web',          'description' => 'Développement et maintenance des applications web'],
            ['name' => 'Développeur Mobile',        'description' => 'Développement d\'applications mobiles'],
            ['name' => 'Comptable',                 'description' => 'Gestion de la comptabilité générale'],
            ['name' => 'Contrôleur de Gestion',     'description' => 'Pilotage budgétaire et reporting financier'],
            ['name' => 'Chargé RH',                 'description' => 'Gestion des ressources humaines'],
            ['name' => 'Responsable Marketing',     'description' => 'Stratégie et communication marketing'],
            ['name' => 'Commercial',                'description' => 'Prospection et vente'],
            ['name' => 'Assistant Administratif',   'description' => 'Soutien administratif et logistique'],
            ['name' => 'Chef de Projet',            'description' => 'Coordination et suivi de projets'],
            ['name' => 'Responsable Logistique',    'description' => 'Gestion des flux et de la supply chain'],
        ];
        foreach ($postesData as $p) {
            Poste::firstOrCreate(['name' => $p['name']], $p);
        }

        // ── 3. Types de contrats ───────────────────────────────────
        $contratsData = [
            ['name' => 'CDI',        'description' => 'Contrat à Durée Indéterminée'],
            ['name' => 'CDD',        'description' => 'Contrat à Durée Déterminée'],
            ['name' => 'Stage',      'description' => 'Convention de stage'],
            ['name' => 'Freelance',  'description' => 'Prestation indépendante'],
            ['name' => 'Alternance', 'description' => 'Contrat en alternance'],
        ];
        foreach ($contratsData as $c) {
            TypeContrat::firstOrCreate(['name' => $c['name']], $c);
        }

        // ── 4. Départements ────────────────────────────────────────
        $depsData = [
            ['nom' => 'Informatique',        'description' => 'Direction des systèmes d\'information'],
            ['nom' => 'Finance',             'description' => 'Direction financière et comptable'],
            ['nom' => 'Ressources Humaines', 'description' => 'Gestion du capital humain'],
            ['nom' => 'Marketing',           'description' => 'Communication et développement commercial'],
            ['nom' => 'Opérations',          'description' => 'Logistique et gestion des opérations'],
        ];
        foreach ($depsData as $d) {
            Departement::firstOrCreate(['nom' => $d['nom']], $d);
        }

        // ── 5. Comptes Admin & RH ──────────────────────────────────
        $admin = User::firstOrCreate(['email' => 'admin@gestRH.com'], [
            'nom' => 'Admin', 'prenom' => 'Super',
            'password' => Hash::make('password'),
            'role' => 'admin', 'login' => 'ADM000001',
        ]);
        $admin->syncRoles(['admin']);

        $rh = User::firstOrCreate(['email' => 'rh@gestRH.com'], [
            'nom' => 'Dupont', 'prenom' => 'Marie',
            'password' => Hash::make('password'),
            'role' => 'rh', 'login' => 'RH000001',
        ]);
        $rh->syncRoles(['rh']);

        // ── 6. 15 Employés ─────────────────────────────────────────
        $employes = [
            // Informatique
            ['nom'=>'Koné',       'prenom'=>'Abou',       'email'=>'a.kone@gestRH.com',       'tel'=>'+225 07 01 02 03', 'poste'=>'Développeur Web',        'dep'=>'Informatique',        'contrat'=>'CDI',        'embauche'=>'2023-01-15', 'salaire'=>650000, 'civ'=>'M.',   'nat'=>'Ivoirienne',  'sit'=>'Marié(e)',    'enf'=>2, 'naiss'=>'1990-05-12', 'lieu_naiss'=>'Abidjan',   'adr'=>'Cocody, Abidjan'],
            ['nom'=>'Yao',        'prenom'=>'Serge',      'email'=>'s.yao@gestRH.com',         'tel'=>'+225 05 22 33 44', 'poste'=>'Développeur Mobile',     'dep'=>'Informatique',        'contrat'=>'CDI',        'embauche'=>'2022-07-01', 'salaire'=>600000, 'civ'=>'M.',   'nat'=>'Ivoirienne',  'sit'=>'Célibataire', 'enf'=>0, 'naiss'=>'1994-09-18', 'lieu_naiss'=>'Abidjan',   'adr'=>'Plateau, Abidjan'],
            ['nom'=>'Assi',       'prenom'=>'Joëlle',     'email'=>'j.assi@gestRH.com',        'tel'=>'+225 01 55 66 77', 'poste'=>'Chef de Projet',         'dep'=>'Informatique',        'contrat'=>'CDI',        'embauche'=>'2021-03-10', 'salaire'=>780000, 'civ'=>'Mme',  'nat'=>'Ivoirienne',  'sit'=>'Marié(e)',    'enf'=>1, 'naiss'=>'1988-12-02', 'lieu_naiss'=>'Yamoussoukro','adr'=>'Riviera, Abidjan'],
            // Finance
            ['nom'=>'Traoré',     'prenom'=>'Fatoumata',  'email'=>'f.traore@gestRH.com',      'tel'=>'+225 07 11 22 33', 'poste'=>'Comptable',              'dep'=>'Finance',             'contrat'=>'CDI',        'embauche'=>'2022-03-01', 'salaire'=>580000, 'civ'=>'Mme',  'nat'=>'Ivoirienne',  'sit'=>'Célibataire', 'enf'=>0, 'naiss'=>'1993-08-22', 'lieu_naiss'=>'Bouaké',    'adr'=>'Yopougon, Abidjan'],
            ['nom'=>'Diomandé',   'prenom'=>'Lacina',     'email'=>'l.diomande@gestRH.com',    'tel'=>'+225 05 44 00 11', 'poste'=>'Contrôleur de Gestion',  'dep'=>'Finance',             'contrat'=>'CDI',        'embauche'=>'2020-11-15', 'salaire'=>700000, 'civ'=>'M.',   'nat'=>'Ivoirienne',  'sit'=>'Marié(e)',    'enf'=>3, 'naiss'=>'1985-03-07', 'lieu_naiss'=>'Man',        'adr'=>'Marcory, Abidjan'],
            ['nom'=>'Silué',      'prenom'=>'Nathalie',   'email'=>'n.silue@gestRH.com',       'tel'=>'+225 07 88 99 00', 'poste'=>'Comptable',              'dep'=>'Finance',             'contrat'=>'CDD',        'embauche'=>'2024-02-01', 'salaire'=>420000, 'civ'=>'Mlle', 'nat'=>'Ivoirienne',  'sit'=>'Célibataire', 'enf'=>0, 'naiss'=>'1998-06-25', 'lieu_naiss'=>'Abidjan',   'adr'=>'Adjamé, Abidjan'],
            // RH
            ['nom'=>'Bamba',      'prenom'=>'Aïcha',      'email'=>'a.bamba@gestRH.com',       'tel'=>'+225 01 66 77 88', 'poste'=>'Chargé RH',              'dep'=>'Ressources Humaines', 'contrat'=>'CDD',        'embauche'=>'2024-01-10', 'salaire'=>420000, 'civ'=>'Mlle', 'nat'=>'Ivoirienne',  'sit'=>'Célibataire', 'enf'=>0, 'naiss'=>'1997-02-14', 'lieu_naiss'=>'San-Pédro',  'adr'=>'Riviera, Abidjan'],
            ['nom'=>'Diallo',     'prenom'=>'Moussa',     'email'=>'m.diallo@gestRH.com',      'tel'=>'+225 07 99 00 11', 'poste'=>'Assistant Administratif','dep'=>'Ressources Humaines', 'contrat'=>'Stage',      'embauche'=>'2025-09-01', 'salaire'=>180000, 'civ'=>'M.',   'nat'=>'Guinéenne',   'sit'=>'Célibataire', 'enf'=>0, 'naiss'=>'2001-07-30', 'lieu_naiss'=>'Conakry',   'adr'=>'Adjamé, Abidjan'],
            // Marketing
            ['nom'=>'Coulibaly',  'prenom'=>'Ibrahim',    'email'=>'i.coulibaly@gestRH.com',   'tel'=>'+225 05 44 55 66', 'poste'=>'Responsable Marketing',  'dep'=>'Marketing',           'contrat'=>'CDI',        'embauche'=>'2021-06-01', 'salaire'=>720000, 'civ'=>'M.',   'nat'=>'Ivoirienne',  'sit'=>'Marié(e)',    'enf'=>3, 'naiss'=>'1987-11-03', 'lieu_naiss'=>'Korhogo',   'adr'=>'Marcory, Abidjan'],
            ['nom'=>'Ouedraogo',  'prenom'=>'Clarisse',   'email'=>'c.ouedraogo@gestRH.com',   'tel'=>'+225 01 23 45 67', 'poste'=>'Commercial',             'dep'=>'Marketing',           'contrat'=>'CDI',        'embauche'=>'2023-04-15', 'salaire'=>500000, 'civ'=>'Mlle', 'nat'=>'Burkinabè',   'sit'=>'Célibataire', 'enf'=>0, 'naiss'=>'1995-10-11', 'lieu_naiss'=>'Ouagadougou','adr'=>'Cocody, Abidjan'],
            ['nom'=>'Koffi',      'prenom'=>'Arnaud',     'email'=>'a.koffi@gestRH.com',       'tel'=>'+225 07 34 56 78', 'poste'=>'Commercial',             'dep'=>'Marketing',           'contrat'=>'Alternance', 'embauche'=>'2025-10-01', 'salaire'=>220000, 'civ'=>'M.',   'nat'=>'Ivoirienne',  'sit'=>'Célibataire', 'enf'=>0, 'naiss'=>'2002-04-19', 'lieu_naiss'=>'Abidjan',   'adr'=>'Yopougon, Abidjan'],
            // Opérations
            ['nom'=>'N\'Guessan', 'prenom'=>'Eric',       'email'=>'e.nguessan@gestRH.com',    'tel'=>'+225 05 67 89 01', 'poste'=>'Responsable Logistique', 'dep'=>'Opérations',          'contrat'=>'CDI',        'embauche'=>'2019-08-20', 'salaire'=>820000, 'civ'=>'M.',   'nat'=>'Ivoirienne',  'sit'=>'Marié(e)',    'enf'=>4, 'naiss'=>'1982-01-15', 'lieu_naiss'=>'Abidjan',   'adr'=>'Port-Bouët, Abidjan'],
            ['nom'=>'Touré',      'prenom'=>'Aminata',    'email'=>'am.toure@gestRH.com',      'tel'=>'+225 01 78 90 12', 'poste'=>'Assistant Administratif','dep'=>'Opérations',          'contrat'=>'CDI',        'embauche'=>'2023-09-01', 'salaire'=>380000, 'civ'=>'Mme',  'nat'=>'Malienne',    'sit'=>'Marié(e)',    'enf'=>2, 'naiss'=>'1991-07-08', 'lieu_naiss'=>'Bamako',    'adr'=>'Abobo, Abidjan'],
            ['nom'=>'Gnamba',     'prenom'=>'Rodrigue',   'email'=>'r.gnamba@gestRH.com',      'tel'=>'+225 07 89 01 23', 'poste'=>'Responsable Logistique', 'dep'=>'Opérations',          'contrat'=>'CDD',        'embauche'=>'2024-05-15', 'salaire'=>460000, 'civ'=>'M.',   'nat'=>'Ivoirienne',  'sit'=>'Célibataire', 'enf'=>0, 'naiss'=>'1996-03-22', 'lieu_naiss'=>'Abidjan',   'adr'=>'Treichville, Abidjan'],
            ['nom'=>'Meité',      'prenom'=>'Sandrine',   'email'=>'s.meite@gestRH.com',       'tel'=>'+225 05 90 12 34', 'poste'=>'Chef de Projet',         'dep'=>'Opérations',          'contrat'=>'CDI',        'embauche'=>'2022-01-03', 'salaire'=>680000, 'civ'=>'Mme',  'nat'=>'Ivoirienne',  'sit'=>'Divorcé(e)',  'enf'=>1, 'naiss'=>'1989-08-30', 'lieu_naiss'=>'Daloa',     'adr'=>'Deux Plateaux, Abidjan'],
        ];

        foreach ($employes as $index => $data) {
            $poste   = Poste::where('name', $data['poste'])->first();
            $dep     = Departement::where('nom', $data['dep'])->first();
            $contrat = TypeContrat::where('name', $data['contrat'])->first();

            // Supprimer les accents avant de générer le matricule
            $nomAscii  = iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE', $data['nom']);
            $prefix    = strtoupper(mb_substr($nomAscii, 0, 3));
            $datePart  = Carbon::parse($data['embauche'])->format('dmy');
            $counter   = str_pad($index + 1, 2, '0', STR_PAD_LEFT);
            $matricule = $prefix . $datePart . '-' . $counter;

            $user = User::firstOrCreate(['email' => $data['email']], [
                'nom'      => $data['nom'],
                'prenom'   => $data['prenom'],
                'password' => Hash::make('password'),
                'role'     => 'employe',
                'login'    => $matricule,
            ]);
            $user->syncRoles(['employe']);

            $employe = Employe::firstOrCreate(['email' => $data['email']], [
                'matricule'              => $matricule,
                'nom'                    => $data['nom'],
                'prenom'                 => $data['prenom'],
                'civilite'               => $data['civ'],
                'nationalite'            => $data['nat'],
                'situation_matrimoniale' => $data['sit'],
                'nombre_enfants'         => $data['enf'],
                'date_naissance'         => $data['naiss'],
                'lieu_naissance'         => $data['lieu_naiss'],
                'telephone'              => $data['tel'],
                'adresse'                => $data['adr'],
                'salaire'                => $data['salaire'],
                'departement_id'         => $dep->id,
                'poste_id'               => $poste->id,
                'type_contrat_id'        => $contrat->id,
                'user_id'                => $user->id,
                'date_embauche'          => $data['embauche'],
            ]);

            $user->update(['employe_id' => $employe->id]);
        }

        // ── 7. Pointages d'un mois ─────────────────────────────────
        $this->call(PointageSeeder::class);

        $this->command->info('');
        $this->command->info('✓ Seed terminé. Comptes disponibles :');
        $this->command->info('  admin@gestRH.com   → password  (admin)');
        $this->command->info('  rh@gestRH.com      → password  (rh)');
        $this->command->info('  + 15 employés      → password  (employe)');
        $this->command->info('  Login employé = matricule (ex: KON150123-01)');
    }
}
