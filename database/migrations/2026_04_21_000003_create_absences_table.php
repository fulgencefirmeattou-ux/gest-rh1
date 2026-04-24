<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('absences', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employe_id')->constrained('employes')->onDelete('cascade');
            $table->enum('type_absence', [
                'maladie',
                'retard',
                'permission_courte',
                'absence_non_justifiee',
                'rendez_vous',
                'autre',
            ]);
            $table->date('date_absence');
            $table->time('heure_debut')->nullable();
            $table->time('heure_fin')->nullable();
            $table->text('motif')->nullable();
            $table->string('justificatif')->nullable();
            $table->enum('statut', ['en_attente', 'validee', 'rejetee'])->default('en_attente');
            $table->text('commentaire_rh')->nullable();
            $table->unsignedBigInteger('traite_par')->nullable();
            $table->timestamp('traite_le')->nullable();
            $table->timestamps();
            $table->date('date_fin_absence')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('absences');
    }
};
