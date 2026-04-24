<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('historique_conges', function (Blueprint $table) {
            $table->id();
            $table->foreignId('demande_conge_id')->constrained('demande_conges')->onDelete('cascade');
            $table->foreignId('approver_id')->constrained('users')->onDelete('cascade');
            $table->enum('etape', ['service', 'departement', 'dg_rh']);
            $table->enum('decision', ['approuve', 'rejete', 'modification_demandee']);
            $table->text('commentaire')->nullable();
            $table->timestamp('approved_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('historique_conges');
    }
};
