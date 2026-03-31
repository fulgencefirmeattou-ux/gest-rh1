<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ContratController;
use App\Http\Controllers\BulletinController;

Route::middleware('auth')->group(function () {

    // Contrats
    Route::resource('contrats', ContratController::class)->names([
        'index'  => 'contrats.index',
        'create' => 'contrats.create',
        'store'  => 'contrats.store',
        'show'   => 'contrats.show',
        'edit'   => 'contrats.edit',
        'update' => 'contrats.update',
    ]);
    Route::get('contrats/{contrat}/download', [ContratController::class, 'downloadPdf'])->name('contrats.download');

    // Bulletins de paie
    Route::get('bulletins/create', [BulletinController::class, 'create'])->name('bulletins.create');
    Route::post('bulletins/generate', [BulletinController::class, 'generate'])->name('bulletins.generate');
    Route::resource('bulletins', BulletinController::class)->only(['index', 'show']);
    Route::get('bulletins/{bulletin}/download', [BulletinController::class, 'downloadPdf'])->name('bulletins.download');
    Route::post('bulletins/{bulletin}/payer', [BulletinController::class, 'payer'])->name('bulletins.payer');
});
