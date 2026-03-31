<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AbsenceController;

Route::middleware('auth')->group(function () {

    // Employé — justificatifs
    Route::get('/justificatifs/absence', [AbsenceController::class, 'create'])->name('justificatifs.absence');
    Route::get('/justificatifs/absence/liste', [AbsenceController::class, 'index'])->name('justificatifs.absence.liste');
    Route::post('/justificatifs/absence/store', [AbsenceController::class, 'store'])->name('justificatifs.store');
    Route::get('/absences/{id}', [AbsenceController::class, 'show'])->name('absences.details');
    Route::put('/absences/{id}/resubmit', [AbsenceController::class, 'resubmit'])->name('absences.resubmit');
    Route::get('/justificatifs/absence/{absence}/download', [AbsenceController::class, 'download'])->name('justificatifs.absence.download');

    // RH / Responsable — traitement
    Route::get('/rh', [AbsenceController::class, 'rhIndex'])->name('justisificatifs.absence.rh');
    Route::post('/approve/{id}', [AbsenceController::class, 'approve'])->name('absence.approve');
    Route::post('/reject/{id}', [AbsenceController::class, 'reject'])->name('absence.reject');
    Route::get('/justificatifs/absence/traiter', [AbsenceController::class, 'ListeDemandeAbsenceTraiter'])->name('justificatifs.absence.traiter');
});
