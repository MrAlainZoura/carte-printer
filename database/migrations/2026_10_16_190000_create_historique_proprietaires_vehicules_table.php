<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('historique_proprietaires_vehicules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vehicule_id')->constrained('vehicules')->cascadeOnDelete();
            $table->foreignId('ancien_proprietaire_id')->nullable();
            $table->foreign('ancien_proprietaire_id', 'hist_prop_veh_ancien_fk')
                ->references('id')
                ->on('propietaires')
                ->nullOnDelete();
            $table->foreignId('nouveau_proprietaire_id')->nullable();
            $table->foreign('nouveau_proprietaire_id', 'hist_prop_veh_nouveau_fk')
                ->references('id')
                ->on('propietaires')
                ->nullOnDelete();
            $table->string('ancien_proprietaire');
            $table->string('nouveau_proprietaire');
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('transfere_le');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('historique_proprietaires_vehicules');
    }
};
