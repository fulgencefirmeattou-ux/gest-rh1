<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('pointages', function (Blueprint $table) {
            $table->id();

            // Relation employé (via users)
            $table->foreignId('employe_id')->constrained('users')->cascadeOnDelete();

            // Date du pointage
            $table->date('date');

            // Indique si l'employé était absent ce jour-là (pour les jours d'absence justifiée)
            $table->boolean('absent')->default(false);

            // Heures de travail
            $table->time('heure_arrivee')->nullable();
            $table->time('heure_depart')->nullable();

            // Pause (optionnelle)
            $table->time('heure_debut_pause')->nullable();
            $table->time('heure_fin_pause')->nullable();

            // Résultats calculés (en heures décimales)
            $table->decimal('heures_travaillees', 5, 2)->nullable();
            $table->decimal('heures_sup', 5, 2)->default(0);
            $table->decimal('heures_manquantes', 5, 2)->default(0);

            // Statut RH
            $table->enum('statut', ['EN_ATTENTE', 'VALIDE', 'REFUSE'])->default('EN_ATTENTE');

            // Nature du jour
            $table->enum('type_jour', ['NORMAL', 'CONGE', 'FERIE'])->default('NORMAL');

            // Validation RH
            $table->foreignId('valide_par')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('date_validation')->nullable();

            $table->timestamps();

            // Un seul pointage par employé par jour
            $table->unique(['employe_id', 'date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pointages');
    
    }
};
