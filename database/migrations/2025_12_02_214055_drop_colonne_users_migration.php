<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
            Schema::table('users', function (Blueprint $table) {
        if (Schema::hasColumn('users', 'matricule')) {
            $table->dropColumn('matricule');
        }
        if (Schema::hasColumn('users', 'nom')) {
            $table->dropColumn('nom');
        }
        if (Schema::hasColumn('users', 'prenom')) {
            $table->dropColumn('prenom');
        }
        if (Schema::hasColumn('users', 'email')) {
            $table->dropColumn('email');
        }
        if (Schema::hasColumn('users', 'phone')) {
            $table->dropColumn('phone');
        }
        if (Schema::hasColumn('users', 'photo')) {
            $table->dropColumn('photo');
        }
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
          Schema::table('users', function (Blueprint $table) {
        $table->string('matricule')->nullable();
        $table->string('nom')->nullable();
        $table->string('prenom')->nullable();
        $table->string('email')->nullable();
        $table->string('phone')->nullable();
        $table->string('photo')->nullable();
    });
    }
};
