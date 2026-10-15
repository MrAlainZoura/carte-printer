<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Table: carte_roses (entité UML CARTE → CarteRose)
     */
    public function up(): void
    {
        Schema::create('carte_roses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('post_travail_id')
                ->constrained('post_travails')
                ->cascadeOnUpdate()
                ->restrictOnDelete();
            $table->foreignId('vehicule_id')
                ->constrained('vehicules')
                ->cascadeOnUpdate()
                ->restrictOnDelete();
            $table->foreignId('user_id')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete()
                ->cascadeOnUpdate();
            $table->string('libele'); // ex: Carte Rose / duplicata
            $table->string('numero')->unique(); // référence DGRK/...
            $table->date('date_octroi')->nullable();
            $table->date('date_expiration')->nullable();
            $table->string('statut')->default('active'); // active, expiree, annulee, perdue
            $table->string('qr_token')->nullable()->unique();
            $table->text('observations')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('carte_roses');
    }
};
