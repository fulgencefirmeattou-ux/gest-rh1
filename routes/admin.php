<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DepartementController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\EmployeController;
use App\Http\Controllers\DashboardController;

Route::middleware(['auth', 'admin'])->group(function () {

    // Dashboard
    Route::get('/admin/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');

    // Utilisateurs
    Route::get('/admin/users/create', [UserController::class, 'showCreateUserForm'])->name('admin.users.create');
    Route::post('/admin/create-user', [UserController::class, 'createUser'])->name('admin.createUser');
    Route::post('/admin/users', [AuthController::class, 'createUser'])->name('admin.users.store');
    Route::get('/liste/utilisateurs', [UserController::class, 'liste'])->name('users.liste');
    Route::get('/users/{id}', [UserController::class, 'show'])->name('users.voir');
    Route::put('/users/{id}', [UserController::class, 'update'])->name('users.update');
    Route::delete('/users/{id}', [UserController::class, 'destroy'])->name('users.destroy');

    // Départements
    Route::get('/admin/create-departement', [DepartementController::class, 'index'])->name('create.departement');
    Route::post('/admin/create-departement', [DepartementController::class, 'create'])->name('departement.create');
    Route::get('/liste/departements', [DepartementController::class, 'store'])->name('departements.liste');
    Route::get('/departements/{id}', [DepartementController::class, 'show'])->name('departement.voir');
    Route::put('/departements/{id}', [DepartementController::class, 'update'])->name('departement.update');
    Route::delete('/departements/{id}', [DepartementController::class, 'destroy'])->name('departement.destroy');

    // Services
    Route::get('/admin/create-service', [ServiceController::class, 'index'])->name('create.service');
    Route::post('/admin/create-service', [ServiceController::class, 'create'])->name('service.create');
    Route::get('/liste/services', [ServiceController::class, 'store'])->name('services.liste');
    Route::get('/services/{id}', [ServiceController::class, 'edit'])->name('service.voir');
    Route::put('/services/{id}', [ServiceController::class, 'update'])->name('service.update');
    Route::delete('/services/{id}', [ServiceController::class, 'destroy'])->name('service.destroy');

    // Employés (création)
    Route::get('/admin/create-employe', [EmployeController::class, 'index'])->name('create.employe');
    Route::post('/admin/create-employe', [EmployeController::class, 'create'])->name('employe.create');
    Route::get('/liste/employe', [EmployeController::class, 'store'])->name('employe.liste');
});
