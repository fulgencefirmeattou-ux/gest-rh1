<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('employes', function (Blueprint $table) {
            $table->id();
            $table->string('matricule')->unique();
            $table->string('nom');
            $table->string('prenom');
            $table->string('civilite', 10)->nullable();
            $table->string('nationalite', 100)->nullable();
            $table->string('situation_matrimoniale', 50)->nullable();
            $table->unsignedInteger('nombre_enfants')->default(0)->nullable();
            $table->date('date_naissance')->nullable();
            $table->string('lieu_naissance', 255)->nullable();
            $table->string('telephone', 20);
            $table->string('email')->unique();
            $table->string('adresse', 500)->nullable();
            $table->string('photo_profil')->nullable();
            $table->string('curriculum_vitae')->nullable();
            $table->string('lettre_motivation')->nullable();
            $table->unsignedInteger('salaire');
            $table->foreignId('departement_id')
                  ->constrained('departements')
                  ->cascadeOnDelete();
            $table->foreignId('poste_id')
                  ->constrained('postes')
                  ->cascadeOnDelete();
            $table->foreignId('type_contrat_id')
                  ->constrained('type_contrats')
                  ->cascadeOnDelete();
            $table->foreignId('user_id')
                  ->constrained('users');
            $table->date('date_embauche');
            $table->softDeletes();
            $table->timestamps();

            //--Services liés à la paie (ex: CNPS, CMU)────────────────────────────────────────
            $table->unsignedBigInteger('service_id')->nullable();
            $table->unsignedSmallInteger('solde_conges')->default(30);
            $table->unsignedSmallInteger('solde_permissions')->default(10);
            $table->unsignedSmallInteger('conges_pris')->default(0);
            $table->unsignedSmallInteger('permissions_prises')->default(0);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('employes');
    }
};
