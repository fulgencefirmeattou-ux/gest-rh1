<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\DepartementController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\EmployeController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\BadgeController;
use App\Http\Controllers\EmployeSpaceController;
use App\Http\Controllers\DemandeCongeController;
use App\Http\Controllers\CongeApprobationDgRhController;
use App\Http\Controllers\AbsenceController;
use App\Http\Controllers\ContratController;
use App\Http\Controllers\BulletinController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\PosteController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\TypeContratController;
use App\Http\Controllers\PointageController;
use App\Http\Controllers\InfoEntrepriseController;
use App\Http\Controllers\NotificationController;


// ---------------------------------------------------------------
// Page d'accueil → login
// ---------------------------------------------------------------
Route::get('/', fn() => redirect('/login'));

// ---------------------------------------------------------------
// AUTHENTIFICATION (routes publiques)
// ---------------------------------------------------------------

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);

    Route::get('/forgot-password', [AuthController::class, 'showForgotForm'])->name('password.request');
    Route::post('/forgot-password', [AuthController::class, 'sendResetLink'])->name('password.email');
    Route::get('/reset-password/{token}', [AuthController::class, 'showResetForm'])->name('password.reset');
    Route::post('/reset-password', [AuthController::class, 'resetPassword'])->name('password.update');

    Route::get('/demande/inscription', [AuthController::class, 'askInscription'])->name('contact_admin');
    Route::post('/contact-admin', [AuthController::class, 'send'])->name('contact.admin');
});

// Déconnexion
Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// Forcer la modification du mot de passe
Route::get('/force-password', [AuthController::class, 'showForcePassword'])->name('password.force')->middleware('auth');
Route::post('/force-password', [AuthController::class, 'updateForcePassword'])->name('password.update.force')->middleware('auth');


// ---------------------------------------------------------------
// ROUTES PROTÉGÉES (authentification requise)
// ---------------------------------------------------------------

Route::middleware('auth')->group(function () {

    // ── Notifications (tous les utilisateurs connectés) ──────────────────
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::get('/notifications/{id}/go', [NotificationController::class, 'go'])->name('notifications.go');
    Route::patch('/notifications/{id}/mark-read', [NotificationController::class, 'markRead'])->name('notifications.markRead');
    Route::post('/notifications/mark-all-read', [NotificationController::class, 'markAllRead'])->name('notifications.markAllRead');

    // ── Dashboard (tous) ──────────────────────────────────────────────────
    Route::get('/admin/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');

    // ── Espace personnel employé (tous) ───────────────────────────────────
    Route::get('/mon-profil', [EmployeSpaceController::class, 'profil'])->name('employe.profil');
    Route::post('/mon-profil/mot-de-passe', [EmployeSpaceController::class, 'updatePassword'])->name('employe.profil.password');
    Route::get('/mes-contrats', [EmployeSpaceController::class, 'contrats'])->name('employe.contrat');
    Route::get('/mes-bulletins', [EmployeSpaceController::class, 'bulletins'])->name('employe.bulletins');
    Route::get('/mes-bulletins/{bulletin}/download', [EmployeSpaceController::class, 'downloadBulletin'])->name('employe.bulletins.download');

    // ── API interne (tous) ────────────────────────────────────────────────
    Route::get('/departements/{id}/services', [EmployeController::class, 'getServicesByDepartement']);
    Route::get('/departement/{id}/employes', [DepartementController::class, 'employesParDepartement']);
    Route::get('/departement/{id}/liste-employes', [DepartementController::class, 'listeEmploye'])->name('departement.liste.employes');

    // ── Badge (tous) ──────────────────────────────────────────────────────
    Route::get('/badge/{id}/pdf', [BadgeController::class, 'telecharger'])->name('badge.pdf');

    // =========================================================
    // CONGÉS — validation (routes spécifiques avant /{id})
    // =========================================================

    // Responsable de service + admin/rh/dg
    Route::middleware('role.check:super-admin,rh,admin,responsable_service')->group(function () {
        Route::get('/conges/a-valider/service', [ServiceController::class, 'approbationService'])->name('conges.approbation.service');
        Route::post('/service/conge/{id}/traiter', [DemandeCongeController::class, 'traiterService'])->name('service.conge.traiter');
        Route::get('/conges/traiter/service', [ServiceController::class, 'ListeDemandeCongeTraiter'])->name('conges.traiter.liste-service');
    });

    // Responsable de département + admin/rh/dg
    Route::middleware('role.check:super-admin,rh,admin,responsable_departement')->group(function () {
        Route::prefix('departement')->group(function () {
            Route::get('/conges/a-valider/departement', [DepartementController::class, 'departementIndex'])->name('conges.approbation.departement');
            Route::post('/conge/{id}/traiter', [DemandeCongeController::class, 'traiterDepartement'])->name('departement.conge.traiter');
            Route::get('/conges/traiter/departement', [DepartementController::class, 'ListeDemandeCongeTraiter'])->name('conges.traiter.liste');
        });
    });

    // DG / RH / Admin — validation finale et gestion absences
    Route::middleware('role.check:super-admin,rh,admin')->group(function () {
        Route::prefix('dg')->group(function () {
            Route::get('/conges/a-valider/dg', [CongeApprobationDgRhController::class, 'dgRhIndex'])->name('conges.approbation.dgRh');
            Route::post('/leave/{id}/approve', [CongeApprobationDgRhController::class, 'approve']);
            Route::post('/leave/{id}/reject', [CongeApprobationDgRhController::class, 'reject']);
            Route::post('/conge/{id}/traiter', [DemandeCongeController::class, 'traiterDg'])->name('dg.conge.traiter');
            Route::get('/conges/traiter/dg', [CongeApprobationDgRhController::class, 'ListeDemandeCongeTraiter'])->name('conges.traiter.liste-dg');
        });

        Route::get('/rh', [AbsenceController::class, 'rhIndex'])->name('justisificatifs.absence.rh');
        Route::post('/approve/{id}', [AbsenceController::class, 'approve'])->name('absence.approve');
        Route::post('/reject/{id}', [AbsenceController::class, 'reject'])->name('absence.reject');
        Route::get('/justificatifs/absence/traiter', [AbsenceController::class, 'ListeDemandeAbsenceTraiter'])->name('justificatifs.absence.traiter');
    });

    // =========================================================
    // CONGÉS — demandes employé (routes générales, après les spécifiques)
    // =========================================================
    Route::get('/conges/voir', [DemandeCongeController::class, 'index'])->name('conges.voir');
    Route::get('/conges/create-conge', [DemandeCongeController::class, 'create'])->name('conges.create-conge');
    Route::post('/conges/store', [DemandeCongeController::class, 'store'])->name('conges.store');
    Route::get('/conges/{id}', [DemandeCongeController::class, 'show'])->name('conges.details');
    Route::put('/conges/{id}/resubmit', [DemandeCongeController::class, 'resubmit'])->name('conges.resubmit');

    // =========================================================
    // ABSENCES — espace employé
    // =========================================================
    Route::get('/justificatifs/absence', [AbsenceController::class, 'create'])->name('justificatifs.absence');
    Route::get('/justificatifs/absence/liste', [AbsenceController::class, 'index'])->name('justificatifs.absence.liste');
    Route::post('/justificatifs/absence/store', [AbsenceController::class, 'store'])->name('justificatifs.store');
    Route::get('/absences/{id}', [AbsenceController::class, 'show'])->name('absences.details');
    Route::put('/absences/{id}/resubmit', [AbsenceController::class, 'resubmit'])->name('absences.resubmit');
    Route::get('/justificatifs/absence/{absence}/download', [AbsenceController::class, 'download'])->name('justificatifs.absence.download');

    // =========================================================
    // FINANCE — Contrats, Bulletins, Pointages (admin + rh + dg)
    // =========================================================
    Route::middleware('role.check:super-admin,rh,admin')->group(function () {

        Route::get('employes/{employe}/historique-paiements', [BulletinController::class, 'historiqueEmploye'])->name('employes.historique');

        // Contrats
        Route::resource('contrats', ContratController::class);
        Route::get('contrats/{contrat}/download', [ContratController::class, 'downloadPdf'])->name('contrats.download');

        // Bulletins de paie (routes spécifiques avant /{bulletin})
        Route::get('bulletins',                         [BulletinController::class, 'index']      )->name('bulletins.index');
        Route::get('bulletins/create',                  [BulletinController::class, 'create']     )->name('bulletins.create');
        Route::post('bulletins',                        [BulletinController::class, 'store']      )->name('bulletins.store');
        Route::post('bulletins/calculer',               [BulletinController::class, 'calculer']   )->name('bulletins.calculer');
        Route::get('bulletins/employe/{id}/info',       [BulletinController::class, 'infoEmploye'])->name('bulletins.employe.info');
        Route::get('bulletins/{bulletin}',              [BulletinController::class, 'show']       )->name('bulletins.show');
        Route::delete('bulletins/{bulletin}',           [BulletinController::class, 'destroy']    )->name('bulletins.destroy');
        Route::post('bulletins/{bulletin}/valider',     [BulletinController::class, 'valider']    )->name('bulletins.valider');
        Route::post('bulletins/{bulletin}/payer',       [BulletinController::class, 'payer']      )->name('bulletins.payer');
        Route::get('bulletins/{bulletin}/download',     [BulletinController::class, 'downloadPdf'])->name('bulletins.download');

        // Pointages
        Route::prefix('pointages')->name('pointages.')->group(function () {
            Route::get('/',              [PointageController::class, 'index'])->name('index');
            Route::post('/sauvegarder',  [PointageController::class, 'sauvegarder'])->name('sauvegarder');
            Route::delete('/{id}/annuler', [PointageController::class, 'annuler'])->name('annuler');
            Route::get('/dashboard',     [PointageController::class, 'dashboard'])->name('dashboard');
            Route::post('/{id}/valider', [PointageController::class, 'valider'])->name('valider');
            Route::patch('/{id}/type',   [PointageController::class, 'updateType'])->name('type');
            Route::get('/statistiques',  [PointageController::class, 'statistiques'])->name('statistiques');
            Route::get('/calendrier',    [PointageController::class, 'calendrier'])->name('calendrier');
            Route::get('/export/pointages',  [PointageController::class, 'exportPdfPointages'])->name('export.pointages');
            Route::get('/export/calendrier', [PointageController::class, 'exportPdfCalendrier'])->name('export.calendrier');
        });
    });

    // =========================================================
    // GESTION RH — Employés, Départements, Postes, Contrats (admin + rh)
    // =========================================================
    Route::middleware('role.check:super-admin,rh')->group(function () {

        // Employés
        Route::resource('employes', EmployeController::class);
        Route::get('employes-corbeille', [EmployeController::class, 'trashed'])->name('employes.trashed');
        Route::post('employes/{id}/restore', [EmployeController::class, 'restore'])->name('employes.restore');
        Route::delete('employes/{id}/force-delete', [EmployeController::class, 'forceDelete'])->name('employes.forceDelete');

        Route::get('/admin/create-employe', [EmployeController::class, 'index'])->name('create.employe');
        Route::post('/admin/create-employe', [EmployeController::class, 'create'])->name('employe.create');
        Route::get('/liste/employe', [EmployeController::class, 'store'])->name('employe.liste');

        // Départements
        Route::resource('departements', DepartementController::class);

        // Postes
        Route::resource('postes', PosteController::class);

        // Types de contrats
        Route::resource('type_contrats', TypeContratController::class);

        // Services
        Route::get('/admin/create-service', [ServiceController::class, 'index'])->name('create.service');
        Route::post('/admin/create-service', [ServiceController::class, 'create'])->name('service.create');
        Route::get('/liste/services', [ServiceController::class, 'store'])->name('services.liste');
        Route::get('/services/{id}', [ServiceController::class, 'edit'])->name('service.voir');
        Route::put('/services/{id}', [ServiceController::class, 'update'])->name('service.update');
        Route::delete('/services/{id}', [ServiceController::class, 'destroy'])->name('service.destroy');

    });

    // Fiche employé — défini après Route::resource('employes') pour ne pas shadower GET /employes/create
    Route::middleware('role.check:super-admin,rh,admin')->group(function () {
        Route::get('/employes/{id}', [EmployeController::class, 'edit'])->name('employe.voir');
        Route::put('/modification/employes/{id}', [EmployeController::class, 'update'])->name('employe.update');
    });

    // =========================================================
    // GESTION UTILISATEURS (super-admin + rh)
    // =========================================================
    Route::middleware('role.check:super-admin,rh')->group(function () {
        Route::resource('utilisateurs', UserController::class)->except(['destroy']);
    });

    // =========================================================
    // ADMINISTRATION SYSTÈME — Rôles, Permissions, Suppression users (super-admin uniquement)
    // =========================================================
    Route::middleware('role.check:super-admin')->group(function () {

        Route::resource('permissions', PermissionController::class);
        Route::resource('roles', RoleController::class);

        Route::delete('utilisateurs/{utilisateur}', [UserController::class, 'destroy'])->name('utilisateurs.destroy');
        Route::get('utilisateurs-corbeille', [UserController::class, 'trashed'])->name('utilisateurs.trashed');
        Route::post('utilisateurs/{id}/restore', [UserController::class, 'restore'])->name('utilisateurs.restore');
        Route::delete('utilisateurs/{id}/force-delete', [UserController::class, 'forceDelete'])->name('utilisateurs.forceDelete');

        Route::get('info-entreprise', [InfoEntrepriseController::class, 'index'])->name('info-entreprise.index');
        Route::put('info-entreprise', [InfoEntrepriseController::class, 'update'])->name('info-entreprise.update');
    });
});
