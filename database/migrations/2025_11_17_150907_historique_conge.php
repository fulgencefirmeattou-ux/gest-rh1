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
        Schema::create('historique_conges', function (Blueprint $table) {
        $table->id();

        $table->foreignId('demande_conge_id')->constrained('demande_conges')->onDelete('cascade');
        $table->foreignId('responsable_id')->constrained('employes');

        $table->enum('etape', ['service', 'departement', 'dg']);
        $table->enum('decision', ['approuvee', 'rejetee', 'modification_demandee']);
        $table->text('commentaire')->nullable();

        $table->timestamp('approved_at')->nullable();
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
