<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('utilisateurs', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->enum('role', ['employe', 'super-admin', 'admin', 'rh', 'responsable_service'])->default('employe');
            $table->string('login')->nullable();
            $table->unsignedBigInteger('employe_id')->nullable();
            $table->timestamp('date_creation')->nullable()->useCurrent();
            $table->timestamp('date_connexion')->nullable();
            $table->rememberToken();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('utilisateurs');
    }
};
