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
        Schema::create('info_entreprise', function (Blueprint $table) {
            $table->id();
            $table->string('nom')->default('');
            $table->string('adresse')->nullable();
            $table->string('siege_social')->nullable();
            $table->string('num_cnps')->nullable();
            $table->string('num_contribuable')->nullable();
            $table->string('email')->nullable();
            $table->string('telephone')->nullable();
            $table->string('logo')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('info_entreprise');
    }
};
