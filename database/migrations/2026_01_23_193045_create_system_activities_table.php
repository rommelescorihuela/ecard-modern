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
        Schema::create('system_activities', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();

            // Context
            $table->unsignedInteger('vcard_id')->nullable(); // Tenant Context
            $table->unsignedBigInteger('user_id')->nullable();
            $table->string('user_type')->nullable(); // Admin, User, etc.

            // Action
            $table->string('action'); // create, update, delete, login, etc.
            $table->string('subject_type')->nullable(); // App\Models\Vcard
            $table->unsignedBigInteger('subject_id')->nullable();
            $table->text('description')->nullable();

            // Request Metadata
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->string('method')->nullable();
            $table->text('url')->nullable();
            $table->text('referrer')->nullable();

            // Device Info (Optional but good to have)
            $table->string('device')->nullable();
            $table->string('platform')->nullable();
            $table->string('browser')->nullable();

            // Payloads
            $table->json('payload')->nullable(); // Request data
            $table->json('old_values')->nullable();
            $table->json('new_values')->nullable();

            $table->timestamps();

            // Indexes for Dashboard performance
            $table->index('vcard_id');
            $table->index('user_id');
            $table->index(['subject_type', 'subject_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('system_activities');
    }
};
