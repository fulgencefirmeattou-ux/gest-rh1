<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EmployeController;
use App\Http\Controllers\BadgeController;
use App\Http\Controllers\EmployeSpaceController;
use App\Http\Controllers\DepartementController;

// API interne : services d'un département (utilisé en AJAX)
Route::get('/departements/{id}/services', [EmployeController::class, 'getServicesByDepartement']);
Route::get('/departement/{id}/employes', [DepartementController::class, 'employesParDepartement']);
Route::get('/departement/{id}/liste-employes', [DepartementController::class, 'listeEmploye'])->name('departement.liste.employes');

Route::middleware('auth')->group(function () {

    // Fiche employé
    Route::get('/employes/{id}', [EmployeController::class, 'edit'])->name('employe.voir');
    Route::put('/modification/employes/{id}', [EmployeController::class, 'update'])->name('employe.update');

    // Badge
    Route::get('/badge/{id}/pdf', [BadgeController::class, 'telecharger'])->name('badge.pdf');

    // Espace employé
    Route::get('/mon-profil', [EmployeSpaceController::class, 'profil'])->name('employe.profil');
    Route::get('/mes-contrats', [EmployeSpaceController::class, 'contrats'])->name('employe.contrat');
    Route::get('/mes-bulletins', [EmployeSpaceController::class, 'bulletins'])->name('employe.bulletins');
    Route::get('/mes-bulletins/{bulletin}/download', [EmployeSpaceController::class, 'downloadBulletin'])->name('employe.bulletins.download');
});
