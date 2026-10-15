<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('approvisionnements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('posttravail_id')->constrained('post_travails')->cascadeOnDelete();
            $table->foreignId('produit_id')->constrained('produits')->cascadeOnDelete();
            $table->decimal('quantite', 12, 2);
            $table->boolean('confirm')->default(false);
            $table->foreignId('receptionUser')->nullable()->constrained('users')->nullOnDelete();
            $table->string('origine')->default('central');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('approvisionnements');
    }
};
