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
        Schema::table('events', function (Blueprint $table) {
            // ⚠️ 注意：你原来的表定义里缺少 user_id 字段，需要先补充
            if (!Schema::hasColumn('events', 'user_id')) {
                $table->unsignedBigInteger('user_id')->after('id');
                $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            }

            // 活动类型（例如：讲座、比赛、聚会、志愿活动等）
            $table->string('type')->nullable()->after('title');

            // 活动名额限制
            $table->integer('capacity')->nullable()->after('location');

            // 报名截止时间
            $table->dateTime('registration_deadline')->nullable()->after('capacity');

            // 是否需要报名（true/false）
            $table->boolean('requires_registration')->default(true)->after('registration_deadline');

            // 活动状态（draft 草稿 / published 已发布 / closed 已结束）
            $table->enum('status', ['draft', 'published', 'closed'])->default('draft')->after('requires_registration');

            // 活动海报/封面图
            $table->string('cover_image')->nullable()->after('status');

            // 活动附件（例如活动手册 PDF）
            $table->string('attachment')->nullable()->after('cover_image');

            // 已报名人数（方便快速统计）
            $table->integer('registered_count')->default(0)->after('attachment');

            // 是否开启评论区
            $table->boolean('enable_comments')->default(true)->after('registered_count');

            // 是否开启投票/问卷
            $table->boolean('enable_poll')->default(false)->after('enable_comments');

            // 活动回顾/总结内容（活动结束后可以写总结）
            $table->longText('summary')->nullable()->after('enable_poll');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('events', function (Blueprint $table) {
            // 回滚时删除新增的列
            $table->dropForeign(['user_id']);
            $table->dropColumn([
                'user_id',
                'type',
                'capacity',
                'registration_deadline',
                'requires_registration',
                'status',
                'cover_image',
                'attachment',
                'registered_count',
                'enable_comments',
                'enable_poll',
                'summary',
            ]);
        });
    }
};
