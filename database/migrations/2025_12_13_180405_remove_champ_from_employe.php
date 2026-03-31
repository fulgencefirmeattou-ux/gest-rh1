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
        Schema::table('employes', function (Blueprint $table) {
            $table->dropColumn(['salaire','duree', 'type_contrat','date_fin']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('employes', function (Blueprint $table) {
            $table->decimal('salaire', 10, 2)->nullable();
            $table->string('type_contrat')->nullable();
            $table->integer('duree')->nullable();
            $table->date('date_fin')->nullable();
        });
    }
};
