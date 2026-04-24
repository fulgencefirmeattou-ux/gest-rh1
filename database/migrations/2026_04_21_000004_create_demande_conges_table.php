<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('demande_conges', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employe_id')->constrained('employes')->onDelete('cascade');
            $table->enum('type_conge', [
                'conge_paye',
                'maladie',
                'permission_courte',
                'exceptionnel',
                'special',
                'autre',
            ]);
            $table->date('date_debut_conge');
            $table->date('date_fin_conge');
            $table->date('date_retour')->nullable();
            $table->text('raison')->nullable();
            $table->string('justification_absence')->nullable();
            $table->enum('statut', [
                'attente_service',
                'attente_departement',
                'attente_dg',
                'approuvee',
                'rejetee',
                'modification_demandee',
            ])->default('attente_service');
            $table->text('commentaire')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('demande_conges');
    }
};
