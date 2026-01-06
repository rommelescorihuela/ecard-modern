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
        Schema::create('templates', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Ejemplo: "Elegante Oscuro"
            $table->string('identifier')->unique(); // Este debe coincidir con el nombre en Astro
            $table->string('preview_image')->nullable(); // URL de la miniatura
            $table->boolean('is_premium')->default(false); // ¿Solo para planes Pro?
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('templates');
    }
};
