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
        if (!Schema::hasTable('event_comments')) {
    Schema::create('event_comments', function (Blueprint $table) {
        $table->id();

        // 评论人
        $table->foreignId('user_id')->constrained()->onDelete('cascade');

        // 关联的活动
        $table->foreignId('event_id')->constrained()->onDelete('cascade');

        // 评论内容
        $table->text('content');

        // 评论类型：question（提问）、feedback（一般反馈）、review（活动后评价）
        $table->enum('type', ['question', 'feedback', 'review'])->default('feedback');

        // 活动评分（仅当 type=review 时有意义）
        $table->unsignedTinyInteger('rating')->nullable();

        // 是否公开（有的活动评论仅活动成员可见）
        $table->boolean('is_public')->default(true);

        // 父评论（支持回复链）
        $table->foreignId('parent_id')->nullable()->constrained('event_comments')->onDelete('cascade');

        $table->timestamps();
    });
}

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
