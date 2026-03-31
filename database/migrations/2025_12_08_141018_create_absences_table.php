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
        Schema::create('absences', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employe_id')->constrained()->onDelete('cascade');
            $table->enum('type_absence', ['maladie', 'retard', 'absence_non_justifiee', 'rendez_vous', 'autre']);
            $table->date('date_absence');
            $table->string('motif')->nullable();
            $table->string('justificatif')->nullable(); 
            $table->enum('statut', ['en_attente', 'validee_service', 'rejetee'])->default('en_attente');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('absences');
    }
};
