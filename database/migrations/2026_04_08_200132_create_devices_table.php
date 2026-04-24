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
        Schema::create('devices', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('type')->nullable();
            $table->string('brand')->nullable();
            $table->enum('status', ['actif', 'inactif', 'maintenance'])->default("actif");
            $table->text('description')->nullable();
            $table->foreignId('room_id')->nullable()->constrained('rooms')->nullOnDelete();
            $table->foreignId('added_by')->nullable()->constrained('users')->nullOnDelete();

            // Configuration d'utilisation
            $table->time('start_hour')->nullable();
            $table->time('end_hour')->nullable();
            $table->unsignedSmallInteger('usage_time')->nullable();    // en minutes
            $table->unsignedSmallInteger('consumption')->nullable();   // en watts

            // Demandes de suppression
            $table->unsignedTinyInteger('delete_request_number')->default(0);
            $table->json('delete_requested_by')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('devices');
    }
};
