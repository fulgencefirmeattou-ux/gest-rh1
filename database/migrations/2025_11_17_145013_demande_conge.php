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
        Schema::create('demande_conges', function (Blueprint $table) {
        $table->id();
        $table->foreignId('employe_id')->constrained('employes')->onDelete('cascade');
        $table->enum('type_conge', ['annuel', 'special', 'exceptionnel']);
        $table->date('date_debut_conge');
        $table->date('date_fin_conge');
        $table->text('raison')->nullable();
        $table->string('justification_absence')->nullable();

        $table->enum('statut', [
            'attente_service',
            'modification_demandee',
            'attente_departement',
            'attente_dg',
            'approuvee',
            'rejetee'
        ])->default('attente_service');

        $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
