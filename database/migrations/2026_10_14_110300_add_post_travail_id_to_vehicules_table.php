<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * VEHICULE appartient à un POST-TRAVAIL (UML 1,n — 1,1)
     */
    public function up(): void
    {
        Schema::table('vehicules', function (Blueprint $table) {
            $table->foreignId('post_travail_id')
                ->nullable()
                ->after('propietaire_id')
                ->constrained('post_travails')
                ->nullOnDelete()
                ->cascadeOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('vehicules', function (Blueprint $table) {
            $table->dropConstrainedForeignId('post_travail_id');
        });
    }
};
