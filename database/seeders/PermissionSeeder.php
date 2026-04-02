<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use App\Models\User;

class PermissionSeeder extends Seeder
{
    public function run(): void
    {
        // Vider le cache des permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // -------------------------
        // PERMISSIONS
        // -------------------------

        // Employés
        Permission::firstOrCreate(['name' => 'telecharger badge']);
        Permission::firstOrCreate(['name' => 'voir employes']);
        Permission::firstOrCreate(['name' => 'creer employes']);
        Permission::firstOrCreate(['name' => 'modifier employes']);
        Permission::firstOrCreate(['name' => 'supprimer employes']);

        // Utilisateurs
        Permission::firstOrCreate(['name' => 'voir utilisateurs']);
        Permission::firstOrCreate(['name' => 'creer utilisateurs']);
        Permission::firstOrCreate(['name' => 'modifier utilisateurs']);
        Permission::firstOrCreate(['name' => 'supprimer utilisateurs']);

        // Départements
        Permission::firstOrCreate(['name' => 'voir departements']);
        Permission::firstOrCreate(['name' => 'creer departements']);
        Permission::firstOrCreate(['name' => 'modifier departements']);
        Permission::firstOrCreate(['name' => 'supprimer departements']);

        // Services
        Permission::firstOrCreate(['name' => 'voir services']);
        Permission::firstOrCreate(['name' => 'creer services']);
        Permission::firstOrCreate(['name' => 'modifier services']);
        Permission::firstOrCreate(['name' => 'supprimer services']);

        // Congés
        Permission::firstOrCreate(['name' => 'demander conges']);
        Permission::firstOrCreate(['name' => 'voir conges propres']);
        Permission::firstOrCreate(['name' => 'voir tous conges']);
        Permission::firstOrCreate(['name' => 'valider conges service']);
        Permission::firstOrCreate(['name' => 'valider conges departement']);
        Permission::firstOrCreate(['name' => 'valider conges dgrh']);

        // Absences
        Permission::firstOrCreate(['name' => 'soumettre absences']);
        Permission::firstOrCreate(['name' => 'voir absences propres']);
        Permission::firstOrCreate(['name' => 'voir toutes absences']);
        Permission::firstOrCreate(['name' => 'valider absences']);

        // Bulletins de paie
        Permission::firstOrCreate(['name' => 'voir bulletins']);
        Permission::firstOrCreate(['name' => 'creer bulletins']);
        Permission::firstOrCreate(['name' => 'modifier bulletins']);
        Permission::firstOrCreate(['name' => 'payer bulletins']);
        Permission::firstOrCreate(['name' => 'telecharger bulletins']);

        // Contrats
        Permission::firstOrCreate(['name' => 'voir contrats']);
        Permission::firstOrCreate(['name' => 'creer contrats']);
        Permission::firstOrCreate(['name' => 'modifier contrats']);
        Permission::firstOrCreate(['name' => 'telecharger contrats']);

        // -------------------------
        // RÔLES
        // -------------------------

        $admin           = Role::firstOrCreate(['name' => 'admin']);
        $dg              = Role::firstOrCreate(['name' => 'dg']);
        $rh              = Role::firstOrCreate(['name' => 'rh']);
        $respDepartement = Role::firstOrCreate(['name' => 'responsable-departement']);
        $respService     = Role::firstOrCreate(['name' => 'responsable-service']);
        $employe         = Role::firstOrCreate(['name' => 'employe']);

        // -------------------------
        // PERMISSIONS PAR RÔLE
        // -------------------------

        // ADMIN → tout
        $admin->givePermissionTo([
            // Employés
            'telecharger badge',
            'voir employes', 'creer employes', 'modifier employes', 'supprimer employes',
            // Utilisateurs
            'voir utilisateurs', 'creer utilisateurs', 'modifier utilisateurs', 'supprimer utilisateurs',
            // Départements
            'voir departements', 'creer departements', 'modifier departements', 'supprimer departements',
            // Services
            'voir services', 'creer services', 'modifier services', 'supprimer services',
            // Congés
            'demander conges', 'voir conges propres', 'voir tous conges',
            'valider conges service', 'valider conges departement', 'valider conges dgrh',
            // Absences
            'soumettre absences', 'voir absences propres', 'voir toutes absences', 'valider absences',
            // Bulletins
            'voir bulletins', 'creer bulletins', 'modifier bulletins', 'payer bulletins', 'telecharger bulletins',
            // Contrats
            'voir contrats', 'creer contrats', 'modifier contrats', 'telecharger contrats',
        ]);

        // DIRECTEUR GÉNÉRAL
        $dg->givePermissionTo([
            'telecharger badge',
            'voir employes',
            'voir departements',
            'voir services',
            'demander conges', 'voir conges propres', 'voir tous conges', 'valider conges dgrh',
            'soumettre absences', 'voir absences propres', 'voir toutes absences', 'valider absences',
            'voir bulletins',
            'voir contrats', 'telecharger contrats',
        ]);

        // RESPONSABLE RH
        $rh->givePermissionTo([
            'telecharger badge',
            'voir employes', 'creer employes', 'modifier employes', 'supprimer employes',
            'voir utilisateurs', 'creer utilisateurs', 'modifier utilisateurs',
            'voir departements', 'creer departements', 'modifier departements', 'supprimer departements',
            'voir services', 'creer services', 'modifier services', 'supprimer services',
            'demander conges', 'voir conges propres', 'voir tous conges', 'valider conges dgrh',
            'soumettre absences', 'voir absences propres', 'voir toutes absences', 'valider absences',
            'voir bulletins', 'creer bulletins', 'modifier bulletins', 'payer bulletins', 'telecharger bulletins',
            'voir contrats', 'creer contrats', 'modifier contrats', 'telecharger contrats',
        ]);

        // RESPONSABLE DE DÉPARTEMENT
        $respDepartement->givePermissionTo([
            'telecharger badge',
            'voir employes',
            'voir departements',
            'voir services',
            'demander conges', 'voir conges propres', 'valider conges departement',
            'soumettre absences', 'voir absences propres',
        ]);

        // RESPONSABLE DE SERVICE
        $respService->givePermissionTo([
            'telecharger badge',
            'voir employes',
            'voir services',
            'demander conges', 'voir conges propres', 'valider conges service',
            'soumettre absences', 'voir absences propres',
        ]);

        // EMPLOYÉ
        $employe->givePermissionTo([
            'telecharger badge',
            'demander conges', 'voir conges propres',
            'soumettre absences', 'voir absences propres',
            'voir bulletins', 'telecharger bulletins',
            'voir contrats', 'telecharger contrats',
        ]);

        // -------------------------
        // ASSIGNER LES RÔLES AUX UTILISATEURS EXISTANTS
        // -------------------------

        User::where('role', 'admin')->each(fn($u) => $u->assignRole('admin'));
        User::where('role', 'dg')->each(fn($u) => $u->assignRole('dg'));
        User::where('role', 'rh')->each(fn($u) => $u->assignRole('rh'));
        User::where('role', 'responsable-departement')->each(fn($u) => $u->assignRole('responsable-departement'));
        User::where('role', 'responsable-service')->each(fn($u) => $u->assignRole('responsable-service'));
        User::where('role', 'employe')->each(fn($u) => $u->assignRole('employe'));
    }
}
