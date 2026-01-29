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
        Schema::table('friends', function (Blueprint $table) {
            // 修改 status 枚举
            $table->enum('status', ['pending', 'accepted', 'rejected'])
                  ->default('pending')
                  ->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('friends', function (Blueprint $table) {
            // 还原为 blocked
            $table->enum('status', ['pending', 'accepted', 'blocked'])
                  ->default('pending')
                  ->change();
        });
    }
};
