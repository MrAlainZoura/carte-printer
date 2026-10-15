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
        Schema::create('post_travails', function (Blueprint $table) {
            $table->id();
            $table->string('libele');
            $table->string('email')->nullable();
            $table->string('contact1')->nullable();
            $table->string('contact')->nullable();
            $table->string('cpostal')->nullable();
            $table->string('pays')->nullable();
            $table->string('province')->nullable();
            $table->string('ville')->nullable();
            $table->string('avenue')->nullable();
            $table->text('autres')->nullable();
            $table->decimal('lon', 10, 7)->nullable();
            $table->decimal('lat', 10, 7)->nullable();
            //$table->string('type')->nullable(); // bureau, guichet, etc.
            $table->boolean('use_cdf')->default(true);
            //$table->decimal('tva_rate', 5, 2)->default(0);
            //$table->boolean('show_tva')->default(false);
            $table->boolean('actif')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('post_travails');
    }
};
