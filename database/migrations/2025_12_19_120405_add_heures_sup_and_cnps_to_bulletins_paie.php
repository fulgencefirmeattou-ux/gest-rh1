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
        Schema::table('bulletins_paie', function (Blueprint $table) {
            $table->decimal('montant_heures_sup', 12, 2)->default(0);
            $table->decimal('montant_cnps', 12, 2)->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bulletins_paie', function (Blueprint $table) {
            //
        });
    }
};
