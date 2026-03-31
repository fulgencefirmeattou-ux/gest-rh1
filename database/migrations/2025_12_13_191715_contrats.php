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
        Schema::create('contrats', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employe_id')->constrained('employes')->cascadeOnDelete();
            $table->enum('type_contrat', ['CDI','CDD','Stage','Autre'])->default('CDI');
            $table->date('date_debut');
            $table->date('date_fin')->nullable();
            $table->decimal('salaire_base', 15, 2);
            $table->enum('mode_calcul', ['mensuel','horaire'])->default('mensuel');
            $table->integer('heures_par_semaine')->nullable();
            $table->enum('statut', ['actif','inactif','annule'])->default('actif');
            $table->string('pdf_path')->nullable();
            $table->timestamps();
                
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contrats');
    }
};
