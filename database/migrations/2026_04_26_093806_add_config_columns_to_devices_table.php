<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('devices', function (Blueprint $table) {
            if (!Schema::hasColumn('devices', 'start_hour')) {
                $table->time('start_hour')->nullable();
            }
            if (!Schema::hasColumn('devices', 'end_hour')) {
                $table->time('end_hour')->nullable();
            }
            if (!Schema::hasColumn('devices', 'usage_time')) {
                $table->unsignedSmallInteger('usage_time')->nullable();
            }
            if (!Schema::hasColumn('devices', 'consumption')) {
                $table->unsignedSmallInteger('consumption')->nullable();
            }
            if (!Schema::hasColumn('devices', 'delete_request_number')) {
                $table->unsignedTinyInteger('delete_request_number')->default(0);
            }
            if (!Schema::hasColumn('devices', 'delete_requested_by')) {
                $table->json('delete_requested_by')->nullable();
            }
        });

        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'is_banned')) {
                $table->boolean('is_banned')->default(false)->after('is_verified');
            }
        });
    }

    public function down(): void
    {
        Schema::table('devices', function (Blueprint $table) {
            $table->dropColumn(['start_hour', 'end_hour', 'usage_time', 'consumption', 'delete_request_number', 'delete_requested_by']);
        });

        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('is_banned');
        });
    }
};
