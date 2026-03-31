<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DemandeCongeController;
use App\Http\Controllers\CongeApprobationServiceController;
use App\Http\Controllers\CongeApprobationDepartementController;
use App\Http\Controllers\CongeApprobationDgRhController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\DepartementController;

Route::middleware('auth')->group(function () {

    // Employé — demandes de congé
    Route::get('/conges/voir', [DemandeCongeController::class, 'index'])->name('conges.voir');
    Route::get('/conges/create-conge', [DemandeCongeController::class, 'create'])->name('conges.create-conge');
    Route::get('/conges/{id}', [DemandeCongeController::class, 'show'])->name('conges.details');
    Route::post('/conges/store', [DemandeCongeController::class, 'store'])->name('conges.store');
    Route::put('/conges/{id}/resubmit', [DemandeCongeController::class, 'resubmit'])->name('conges.resubmit');

    // Responsable de service
    Route::get('/conges/a-valider/service', [ServiceController::class, 'approbationService'])->name('conges.approbation.service');
    Route::post('/service/conge/{id}/traiter', [DemandeCongeController::class, 'traiterService'])->name('service.conge.traiter');
    Route::get('/conges/traiter/service', [ServiceController::class, 'ListeDemandeCongeTraiter'])->name('conges.traiter.liste-service');

    // Responsable département
    Route::prefix('departement')->group(function () {
        Route::get('/conges/a-valider/departement', [DepartementController::class, 'departementIndex'])->name('conges.approbation.departement');
        Route::post('/conge/{id}/traiter', [DemandeCongeController::class, 'traiterDepartement'])->name('departement.conge.traiter');
        Route::get('/conges/traiter/departement', [DepartementController::class, 'ListeDemandeCongeTraiter'])->name('conges.traiter.liste');
    });

    // Directeur Général
    Route::prefix('dg')->group(function () {
        Route::get('/conges/a-valider/dg', [CongeApprobationDgRhController::class, 'dgRhIndex'])->name('conges.approbation.dgRh');
        Route::post('/leave/{id}/approve', [CongeApprobationDgRhController::class, 'approve']);
        Route::post('/leave/{id}/reject', [CongeApprobationDgRhController::class, 'reject']);
        Route::post('/conge/{id}/traiter', [DemandeCongeController::class, 'traiterDg'])->name('dg.conge.traiter');
        Route::get('/conges/traiter/dg', [CongeApprobationDgRhController::class, 'ListeDemandeCongeTraiter'])->name('conges.traiter.liste-dg');
    });
});
