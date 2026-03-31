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
            $table->decimal('autres_retenues', 10, 2)->nullable()->after('total_reductions');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bulletins_paie', function (Blueprint $table) {
            $table->dropColumn('autres_retenues');
        });
    }
};
