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
        Schema::create('propietaires', function (Blueprint $table) {
            $table->id();
            $table->string('type')->default('personne physique');
            $table->string('nom_complet')->nullable();
            $table->string('raison_sociale')->nullable();
            $table->string('adresse_physique')->nullable();
            $table->string('telephone')->nullable();
            $table->string('impot')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('propietaires');
    }
};
