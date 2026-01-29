<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
       // 1. 扩展枚举，先临时允许 draft + pending 共存
    DB::statement("ALTER TABLE `events` MODIFY COLUMN `status` ENUM('draft','published','closed','canceled','pending') NOT NULL DEFAULT 'pending'");

    // 2. 更新旧数据
    DB::statement("UPDATE `events` SET `status` = 'pending' WHERE `status` = 'draft'");

    // 3. 移除 draft，保留新的状态集合
    DB::statement("ALTER TABLE `events` MODIFY COLUMN `status` ENUM('pending','published','closed','canceled') NOT NULL DEFAULT 'pending'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
       // 回滚时，把 pending 改回 draft
    DB::statement("UPDATE `events` SET `status` = 'draft' WHERE `status` = 'pending'");

    // 改回旧的枚举
    Schema::table('events', function (Blueprint $table) {
        DB::statement("ALTER TABLE `events` MODIFY COLUMN `status` ENUM('draft','published','closed') NOT NULL DEFAULT 'draft'");
    });
    }
};
