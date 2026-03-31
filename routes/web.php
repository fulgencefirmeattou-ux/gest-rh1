<?php

use Illuminate\Support\Facades\Route;

Route::get('/', fn() => redirect('/login'));

require __DIR__ . '/auth.php';
require __DIR__ . '/admin.php';
require __DIR__ . '/employes.php';
require __DIR__ . '/conges.php';
require __DIR__ . '/absences.php';
require __DIR__ . '/paie.php';
