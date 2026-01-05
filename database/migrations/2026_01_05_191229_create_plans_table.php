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
        Schema::create('plans', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Básico, Pro, Premium
            $table->decimal('price', 8, 2);
            $table->integer('limit_vcards')->default(1); // Límite de tarjetas por usuario
            $table->json('features')->nullable(); // Para guardar permisos extra (ej: SEO, sin anuncios)
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('plans');
    }
};
