<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Ajouter colonnes manquantes dans users
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'login')) {
                $table->string('login')->nullable()->unique()->after('employe_id');
            }
            if (!Schema::hasColumn('users', 'email')) {
                $table->string('email')->nullable()->unique()->after('login');
            }
            if (!Schema::hasColumn('users', 'must_change_password')) {
                $table->boolean('must_change_password')->default(false)->after('role');
            }
        });

        // Ajouter colonnes manquantes dans employes
        Schema::table('employes', function (Blueprint $table) {
            if (!Schema::hasColumn('employes', 'matricule')) {
                $table->string('matricule')->nullable()->unique()->after('id');
            }
            if (!Schema::hasColumn('employes', 'nom')) {
                $table->string('nom')->nullable()->after('matricule');
            }
            if (!Schema::hasColumn('employes', 'prenom')) {
                $table->string('prenom')->nullable()->after('nom');
            }
            if (!Schema::hasColumn('employes', 'email')) {
                $table->string('email')->nullable()->unique()->after('prenom');
            }
            if (!Schema::hasColumn('employes', 'phone')) {
                $table->string('phone')->nullable()->after('email');
            }
            if (!Schema::hasColumn('employes', 'photo')) {
                $table->string('photo')->nullable()->after('phone');
            }
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['login', 'email', 'must_change_password']);
        });

        Schema::table('employes', function (Blueprint $table) {
            $table->dropColumn(['matricule', 'nom', 'prenom', 'email', 'phone', 'photo']);
        });
    }
};
