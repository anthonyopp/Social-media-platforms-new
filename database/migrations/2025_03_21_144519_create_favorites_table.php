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
        if (!Schema::hasTable('favorites')) {
            Schema::create('favorites', function (Blueprint $table) {
                $table->id();
                $table->foreign('user_id')->references('user_id')->on('user')->onDelete('cascade');
                // $table->foreignId('user_id')->constrained('user')->onDelete('cascade'); // 收藏的用户
                $table->foreignId('post_id')->constrained('posts')->onDelete('cascade'); // 被收藏的帖子
                $table->timestamps();
                $table->unique(['user_id', 'post_id']); // 防止重复收藏
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('favorites');
    }
};
