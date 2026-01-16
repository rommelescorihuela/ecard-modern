<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Tabla pivote: Define qué Planes tienen acceso a qué Plantillas
        Schema::create('plan_template', function (Blueprint $table) {
            $table->id();
            $table->foreignId('plan_id')->constrained()->onDelete('cascade');
            $table->foreignId('template_id')->constrained()->onDelete('cascade');
            $table->timestamps();
        });

        // 2. Ajustar VCards: Cambiar el identificador de texto por una relación formal
        Schema::table('vcards', function (Blueprint $table) {
            if (!Schema::hasColumn('vcards', 'template_id')) {
                $table->foreignId('template_id')
                      ->nullable()
                      ->after('template_identifier')
                      ->constrained('templates')
                      ->onDelete('set null');
            }
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('plan_template');
        Schema::table('vcards', function (Blueprint $table) {
            $table->dropForeign(['template_id']);
            $table->dropColumn('template_id');
        });
    }
};