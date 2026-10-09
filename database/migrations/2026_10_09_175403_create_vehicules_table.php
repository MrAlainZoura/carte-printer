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
        Schema::create('vehicules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('propietaire_id')
                ->constrained()
                ->noActionOnDelete()
                ->cascadeOnUpdate();
            $table->string('marque');
            $table->string('type');
            $table->string('couleur');
            $table->string('immatriculation');
            $table->string('usage');
            $table->string('etat')->nullable();
            $table->string('an_fabrication')->nullable();
            $table->string('an_circulation')->nullable();
            $table->string('moteur')->nullable();
            $table->string('puissance')->nullable();
            $table->string('chassis')->nullable();
            $table->string('cartinspection')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vehicules');
    }
};
