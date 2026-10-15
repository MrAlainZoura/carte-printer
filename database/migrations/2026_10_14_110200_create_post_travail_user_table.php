<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Pivot USER ↔ POST-TRAVAIL (UML 1,n — 1,n)
     */
    public function up(): void
    {
        Schema::create('post_travail_user', function (Blueprint $table) {
            $table->id();
            $table->foreignId('post_travail_id')
                ->constrained('post_travails')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();
            $table->foreignId('user_id')
                ->constrained('users')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();
            $table->boolean('is_default')->default(false);
            $table->timestamps();

            $table->unique(['post_travail_id', 'user_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('post_travail_user');
    }
};
