<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->unsignedInteger('vcard_id')->nullable()->after('id');
            // Assuming vcard_id references a vcard. Using integer for now as Vcard uses int id.
            // If Vcard used UUID, we would use uuid('vcard_id').
            // Adding nullable because Central Admins have null.
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('vcard_id');
        });
    }
};
