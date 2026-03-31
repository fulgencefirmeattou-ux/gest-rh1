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
        Schema::create('bulletins_paie', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employe_id')->constrained('employes')->cascadeOnDelete();
            $table->foreignId('contrat_id')->constrained('contrats')->cascadeOnDelete();
            $table->string('mois'); // format YYYY-MM
            $table->decimal('salaire_base', 15, 2);
            $table->decimal('total_primes', 15, 2)->default(0);
            $table->decimal('total_heures_sup', 15, 2)->default(0);
            $table->decimal('total_reductions', 15, 2)->default(0);
            $table->decimal('cotisations', 15, 2)->default(0);
            $table->decimal('net_a_payer', 15, 2)->default(0);
            $table->string('pdf_path')->nullable();
            $table->enum('statut', ['brouillon','payé'])->default('brouillon');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bulletins_paie', function (Blueprint $table) {
            Schema::dropIfExists('bulletins_paie');
        });
    }
};
