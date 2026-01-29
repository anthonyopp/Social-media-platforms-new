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
        Schema::table('messages', function (Blueprint $table) {
            // === 新增字段 ===
            if (!Schema::hasColumn('messages', 'type')) {
                $table->string('type')->default('chat')->after('group_id');
            }

            if (!Schema::hasColumn('messages', 'event_id')) {
                $table->foreignId('event_id')
                    ->nullable()
                    ->after('type')
                    ->constrained('events')
                    ->onDelete('cascade');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('messages', function (Blueprint $table) {
            if (Schema::hasColumn('messages', 'event_id')) {
                $table->dropForeign(['event_id']);
                $table->dropColumn('event_id');
            }
            if (Schema::hasColumn('messages', 'type')) {
                $table->dropColumn('type');
            }
        });
    }
};
