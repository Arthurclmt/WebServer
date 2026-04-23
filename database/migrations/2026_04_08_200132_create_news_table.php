<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(){
        Schema::create('news', function (Blueprint $table) {
        $table->id();
        $table->string('title');
        $table->text('content');
        $table->string('image')->nullable();
        $table->enum('type', ['news', 'event'])->default('news');
        $table->unsignedBigInteger('event_id')->nullable(); // lien vers event si type=event
        $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('news');
    }
};