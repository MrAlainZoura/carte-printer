<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('vehicules', function (Blueprint $table) {
            $table->foreignId('plaque_id')
                ->nullable()
                ->after('propietaire_id')
                ->constrained('plaques')
                ->nullOnDelete()
                ->cascadeOnUpdate();
        });

        DB::table('plaques')
            ->whereNotNull('vehicule_id')
            ->orderBy('id')
            ->chunkById(500, function ($plaques) {
                foreach ($plaques as $plaque) {
                    DB::table('vehicules')
                        ->where('id', $plaque->vehicule_id)
                        ->whereNull('plaque_id')
                        ->update(['plaque_id' => $plaque->id]);
                }
            });
    }

    public function down(): void
    {
        Schema::table('vehicules', function (Blueprint $table) {
            $table->dropConstrainedForeignId('plaque_id');
        });
    }
};
