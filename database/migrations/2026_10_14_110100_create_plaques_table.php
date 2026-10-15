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
        Schema::create('plaques', function (Blueprint $table) {
            $table->id();
            $table->foreignId('post_travail_id')
                ->constrained('post_travails')
                ->cascadeOnUpdate()
                ->restrictOnDelete();
            $table->string('code')->unique();
            $table->string('ville')->nullable();
            $table->string('serie')->nullable();
            $table->string('statut')->default('disponible'); // disponible, attribuee, annulee
            $table->foreignId('vehicule_id')
                ->nullable()
                ->constrained('vehicules')
                ->nullOnDelete()
                ->cascadeOnUpdate();
            $table->date('date_attribution')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('plaques');
    }
};
