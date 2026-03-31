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
        Schema::create('bulletin_items', function (Blueprint $table) {

            $table->id();
            $table->foreignId('bulletin_id')->constrained('bulletins_paie')->cascadeOnDelete();
            $table->string('type'); // prime, deduction, cotisation,heures_sup
            $table->string('libelle');
            $table->decimal('montant', 15, 2);
            $table->json('meta')->nullable();
            $table->timestamps();
        });

       
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bulletin_items', function (Blueprint $table) {
            Schema::dropIfExists('bulletin_items');
        });
    }
};
