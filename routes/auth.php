<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

// Connexion / Déconnexion
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Mot de passe oublié / Réinitialisation
Route::get('/forgot-password', [AuthController::class, 'showForgotForm'])->name('password.request');
Route::post('/forgot-password', [AuthController::class, 'sendResetLink'])->name('password.email');
Route::get('/reset-password/{token}', [AuthController::class, 'showResetForm'])->name('password.reset');
Route::post('/reset-password', [AuthController::class, 'resetPassword'])->name('password.update');

// Demande d'inscription
Route::get('/demande/inscription', [AuthController::class, 'askInscription'])->name('contact_admin');
Route::post('/contact-admin', [AuthController::class, 'send'])->name('contact.admin');

// Forcer la modification du mot de passe
Route::middleware('auth')->group(function () {
    Route::get('/force-password', [AuthController::class, 'showForcePassword'])->name('password.force');
    Route::post('/force-password', [AuthController::class, 'updateForcePassword'])->name('password.update.force');
});
