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
        Schema::create('post_produits', function (Blueprint $table) {
            $table->id();
            $table->foreignId('post_travail_id')
                ->nullable()
                ->constrained('post_travails')
                ->nullOnDelete()
                ->cascadeOnUpdate();
            $table->foreignId('produit_id')
                ->nullable()
                ->constrained('produits')
                ->nullOnDelete()
                ->cascadeOnUpdate();
            $table->double('prix')->nullable();
            $table->double('quantite')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('post_produits');
    }
};
