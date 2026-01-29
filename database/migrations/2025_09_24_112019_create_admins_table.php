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
        Schema::create('admins', function (Blueprint $table) {
            $table->id(); // 主键
            $table->string('name'); // 管理员姓名
            $table->string('email')->unique(); // 邮箱（唯一）
            $table->string('password'); // 密码
            $table->timestamps();
        });

        // 插入两个默认管理员
        DB::table('admins')->insert([
            [
                'name' => '超级管理员',
                'email' => 'admin@example.com',
                'password' => bcrypt('password123'), // 默认密码
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => '副管理员',
                'email' => 'subadmin@example.com',
                'password' => bcrypt('password456'),
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('admins');
    }
};
