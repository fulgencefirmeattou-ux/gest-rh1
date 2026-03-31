<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DepartementController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\EmployeController;
use App\Http\Controllers\DemandeCongeController;
use App\Http\Controllers\CongeApprobationServiceController;
use App\Http\Controllers\CongeApprobationDepartementController;
use App\Http\Controllers\CongeApprobationDgRhController;
use App\Http\Controllers\AbsenceController;
use App\Http\Controllers\ContratController;
use App\Http\Controllers\BulletinController;
use App\Http\Controllers\EmployeSpaceController;
use App\Http\Controllers\BadgeController;


// =============================
    // PUBLIC ROUTES
// =============================

    Route::get('/', function () {
        return view('welcome');
    });

// =============================
    // AUTHENTIFICATION
// =============================

    // Connexion / Déconnexion
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    

    // Mot de passe oublié / Réinitialisation
    Route::get('/forgot-password', [AuthController::class, 'showForgotForm'])->name('password.request');
    Route::post('/forgot-password', [AuthController::class, 'sendResetLink'])->name('password.email');
    Route::get('/reset-password/{token}', [AuthController::class, 'showResetForm'])->name('password.reset');
    Route::post('/reset-password', [AuthController::class, 'resetPassword'])->name('password.update');

    // Demende d'inscription
    Route::get('/demande/inscription', [AuthController::class, 'askInscription'])->name('contact_admin');
    Route::post('/contact-admin', [AuthController::class, 'send'])->name('contact.admin');    



// -----------------------------
// ADMIN ROUTES
// -----------------------------


    Route::middleware(['auth', 'admin'])->group(function () {
        // création, modification ou suppression de compte utilisateur
            Route::get('/admin/users/create', [UserController::class,  'showCreateUserForm'])->name('admin.users.create');
            Route::post('/admin/create-user', [UserController::class, 'createUser'])->name('admin.createUser');
            Route::post('/admin/users', [AuthController::class, 'createUser'])->name('admin.users.store');
            Route::get('/liste/utilisateurs', [UserController::class, 'liste'])->name('users.liste');
            // Modification
            Route::get('/users/{id}', [UserController::class, 'show'])->name('users.voir');
            Route::put('/users/{id}', [UserController::class, 'update'])->name('users.update');

            // Suppression
            Route::delete('/users/{id}', [UserController::class, 'destroy'])->name('users.destroy');

        // création, vue de département
            Route::get('/admin/create-departement', [DepartementController::class, 'index'])->name('create.departement');
            Route::get('/liste/departements', [DepartementController::class, 'store'])->name('departements.liste');
            Route::post('/admin/create-departement', [DepartementController::class, 'create'])->name('departement.create');
            Route::get('/departements/{id}',[DepartementController::class, 'show'])->name('departement.voir');
            Route::put('/departements/{id}', [DepartementController::class,  'update'])->name('departement.update');
            Route::delete('/departements/{id}', [DepartementController::class, 'destroy'])->name('departement.destroy');

        // création, vue de service
            Route::get('/admin/create-service', [ServiceController::class, 'index'])->name('create.service');
            Route::post('/admin/create-service', [ServiceController::class, 'create'])->name('service.create');
            Route::get('/liste/services', [ServiceController::class, 'store'])->name('services.liste');
            Route::get('/services/{id}',[ServiceController::class, 'edit'])->name('service.voir');
            Route::put('/services/{id}', [ServiceController::class,  'update'])->name('service.update');
            Route::delete('/services/{id}', [ServiceController::class, 'destroy'])->name('service.destroy');

        // création, vue d'employé
            Route::get('/admin/create-employe', [EmployeController::class,'create'])->name('create.employe');
            Route::post('/admin/create-employe', [EmployeController::class,'store'])->name('employe.store');
            Route::get('/liste/employe', [EmployeController::class, 'store'])->name('employe.liste');  
   
        
    
    });     



// =============================
    // DASHBOARD TEMPORAIRE
// =============================

    Route::middleware(['auth'])->group(function () {
        Route::get('/admin/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');

    });



// =============================
    // Departement
// =============================

    Route::get('/departement/{id}/employes', [DepartementController::class, 'employesParDepartement']);
    Route::get('/departement/{id}/liste-employes', [DepartementController::class, 'listeEmploye'])->name('departement.liste.employes');



// =============================
    // Services
// =============================








// =============================
    // Employés
// =============================

    Route::get('/departements/{id}/services', [EmployeController::class, 'getServicesByDepartement']);
    Route::get('/employes/{id}',[EmployeController::class, 'edit'])->name('employe.voir');
    Route::put('/modification/employes/{id}', [EmployeController::class, 'update'])->name('employe.update');
    // Permettre à l'employé de voir son contrat (middleware 'auth' +autorisation)
    Route::get('mon/contrat', function(){
        $user = auth()->user();
        $employe = App\Models\Employe::where('user_uuid',$user->id ?? $user->uuid ?? $user->getKey())->first();
        if(!$employe) abort(404);
            $contrat = $employe->contratActif()->with('primes')->first();
        return view('employe.contrat', compact('contrat'));
    })->middleware('auth')->name('mon.contrat');

// =============================
    // FORCER LA MODIFICATION DU MOT DE PASSE EMPLOYÉ
// =============================
    Route::get('/force-password', [AuthController::class,'showForcePassword'])->name('password.force')->middleware('auth');
    Route::post('/force-password', [AuthController::class,'updateForcePassword'])->name('password.update.force')->middleware('auth');


// =============================
    // CONTRATS & BULLETINS
// =============================


Route::middleware(['auth'])->group(function(){
    // Contrats
    Route::resource('contrats', ContratController::class)->names([
        'index'   => 'contrats.index',
        'create'  => 'contrats.create',
        'store'   => 'contrats.store',
        'show'    => 'contrats.show',
        'edit'    => 'contrats.edit',
        'update'  => 'contrats.update',
    ]);

    Route::get('contrats/{contrat}/download',[ContratController::class,'downloadPdf'])->name('contrats.download');
    Route::get('bulletins/create', [BulletinController::class,'create'])->name('bulletins.create'); 
    Route::post('bulletins/generate',[BulletinController::class,'generate'])->name('bulletins.generate');
    Route::resource('bulletins', BulletinController::class)->only(['index','show']);
    Route::get('bulletins/{bulletin}/download',[BulletinController::class,'downloadPdf'])->name('bulletins.download');

    // Bulletin de paie - paiement
    Route::resource('bulletins', BulletinController::class);
    Route::post('bulletins/{bulletin}/payer', [BulletinController::class,'payer'])->name('bulletins.payer');
});



// ============================
    // CONGÉS
// ============================

    Route::middleware(['auth'])->group(function () {

        route::get('/conges/voir', [DemandeCongeController::class, 'index'])->name('conges.voir');
        route::get('/conges/create-conge', [DemandeCongeController::class, 'create'])->name('conges.create-conge');
        route::get('/conges/{id}', [DemandeCongeController::class, 'show'])->name('conges.details');
        route::post('/conges/store', [DemandeCongeController::class, 'store'])->name('conges.store');
        // Employé
        Route::resource('conges', DemandeCongeController::class);
        Route::put('/conges/{id}/resubmit', [DemandeCongeController::class, 'resubmit'])->name('conges.resubmit');

        // Responsable de service

        Route::get('/conges/a-valider/service', [ServiceController::class, 'approbationService'])->name('conges.approbation.service');
            // Route::post('/conge/{id}/approve', [CongeApprobationServiceController::class, 'approve']);
            // Route::post('/conge/{id}/reject', [CongeApprobationServiceController::class, 'reject']);
            // Route::post('/conge/{id}/modify', [CongeApprobationServiceController::class, 'requestModification']);
        Route::post('/service/conge/{id}/traiter', [DemandeCongeController::class, 'traiterService'])->name('service.conge.traiter');
        Route::get('/conges/traiter/service', [ServiceController::class, 'ListeDemandeCongeTraiter'])->name('conges.traiter.liste-service');

    
        // Route::prefix('service')->group(function () { });
            

        // Responsable département
        Route::prefix('departement')->group(function () {
            Route::get('/conges/a-valider/departement', [DepartementController::class, 'departementIndex'])->name('conges.approbation.departement');
            // Route::post('/conge/{id}/approve', [CongeApprobationDepartementController::class, 'approve'])->name('departement.conge.approve');
            // Route::post('/conge/{id}/reject', [CongeApprobationDepartementController::class, 'reject'])->name('departement.conge.reject');
            Route::post('/conge/{id}/traiter', [DemandeCongeController::class, 'traiterDepartement'])->name('departement.conge.traiter');
            Route::get('/conges/traiter/departement', [DepartementController::class, 'ListeDemandeCongeTraiter'])->name('conges.traiter.liste');
        });

        // DG
        Route::prefix('dg')->group(function () {
            Route::get('/conges/a-valider/dg', [CongeApprobationDgRhController::class, 'dgRhIndex'])
                ->name('conges.approbation.dgRh');
            Route::post('/leave/{id}/approve', [CongeApprobationDgRhController::class, 'approve']);
            Route::post('/leave/{id}/reject', [CongeApprobationDgRhController::class, 'reject']);
            Route::post('/conge/{id}/traiter', [DemandeCongeController::class, 'traiterDg'])
        ->name('dg.conge.traiter');
        Route::get('/conges/traiter/dg', [CongeApprobationDgRhController::class, 'ListeDemandeCongeTraiter'])
        ->name('conges.traiter.liste-dg');
        });


        // justificatifs d'absence
        Route::get('/justificatifs/absence', [AbsenceController::class, 'create'])->name('justificatifs.absence');
        Route::get('/justificatifs/absence/liste', [AbsenceController::class, 'index'])->name('justificatifs.absence.liste');
        Route::post('/justificatifs/absence/store', [AbsenceController::class, 'store'])->name('justificatifs.store');

        Route::get('/rh', [AbsenceController::class, 'rhIndex'])->name('justisificatifs.absence.rh');
        Route::post('/approve/{id}', [AbsenceController::class, 'approve'])->name('absence.approve');
        Route::post('/reject/{id}', [AbsenceController::class, 'reject'])->name('absence.reject');
        Route::get('/justificatifs/absence/traiter', [AbsenceController::class, 'ListeDemandeAbsenceTraiter'])->name('justificatifs.absence.traiter');

        Route::get('/absences/{id}', [AbsenceController::class, 'show'])->name('absences.details');
        Route::put('/absences/{id}/resubmit',
            [AbsenceController::class, 'resubmit'])->name('absences.resubmit');
        Route::get(
            '/justificatifs/absence/{absence}/download',
            [AbsenceController::class, 'download']
        )->name('justificatifs.absence.download');
        
    });


// =============================
    // ESPACE EMPLOYÉ
// =============================

Route::middleware(['auth'])->group(function () {

    // Route::get('/mon-espace', [EmployeSpaceController::class,'dashboard'])
    //     ->name('employe.dashboard');

    Route::get('/mon-profil', [EmployeSpaceController::class,'profil'])
        ->name('employe.profil');

    Route::get('/mes-contrats', [EmployeSpaceController::class,'contrats'])
        ->name('employe.contrat');

    Route::get('/mes-bulletins', [EmployeSpaceController::class,'bulletins'])
        ->name('employe.bulletins');

    Route::get('/mes-bulletins/{bulletin}/download',
        [EmployeSpaceController::class,'downloadBulletin'])
        ->name('employe.bulletins.download');

    Route::get('/badge/{id}/pdf', [BadgeController::class, 'telecharger'])
    ->name('badge.pdf');

});
