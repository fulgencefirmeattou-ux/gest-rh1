<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            DepartementSeeder::class,    // 1. Départements
            ServiceSeeder::class,        // 2. Services (dépend des départements)
            EmployeUserSeeder::class,    // 3. Employés + Comptes utilisateurs
            ResponsablesSeeder::class,   // 4. Affectation responsables (depts + services)
            ContratSeeder::class,        // 5. Contrats + Primes
            TotalCongeSeeder::class,     // 6. Soldes de congés
            PresenceAbsenceSeeder::class,// 7. Présences + Absences
            DemandeCongeSeeder::class,   // 8. Demandes de congé + Historique
            BulletinPaieSeeder::class,   // 9. Bulletins de paie + Items
        ]);
    }
}
