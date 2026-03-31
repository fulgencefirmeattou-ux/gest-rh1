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
        Schema::create('total_conges', function (Blueprint $table) {
        $table->id();
        $table->foreignId('employe_id')->constrained('employes');
        $table->integer('conge_annuel')->default(30);
        $table->integer('conge_exceptionnel')->default(0);  
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
