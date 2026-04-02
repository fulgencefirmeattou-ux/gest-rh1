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
        Schema::table('departements', function (Blueprint $table) {
            if (Schema::hasColumn('departements', 'responsable_id')) {
                $table->dropForeign(['responsable_id']);
                $table->foreign('responsable_id')->references('id')->on('users')->nullOnDelete();
            }
        });
    }

    public function down(): void
    {
        Schema::table('departements', function (Blueprint $table) {
            $table->dropForeign(['responsable_id']);
            $table->foreign('responsable_id')->references('id')->on('employes')->nullOnDelete();
        });
    }
};
