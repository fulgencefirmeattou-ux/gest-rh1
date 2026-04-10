<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('contrats', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employe_id')->constrained('employes')->cascadeOnDelete();
            $table->string('type_contrat', 50);
            $table->date('date_debut');
            $table->date('date_fin')->nullable();
            $table->integer('duree')->nullable();
            $table->decimal('salaire_base', 12, 2);
            $table->string('mode_calcul', 20)->default('mensuel');
            $table->decimal('heures_par_semaine', 5, 2)->nullable()->default(40);
            $table->string('statut', 20)->default('actif');
            $table->string('pdf_path')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('contrats');
    }
};
