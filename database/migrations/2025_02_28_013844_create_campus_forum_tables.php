<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up() {
        if (!Schema::hasTable('user')) {
            Schema::create('user', function (Blueprint $table) {
                $table->id('user_id');
                $table->string('name');
                $table->string('email')->unique();
                $table->string('password');
                $table->string('profile_picture')->nullable();
                $table->enum('role', ['teacher', 'student']);
                $table->boolean('is_online')->default(false);
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('posts')) {
            Schema::create('posts', function (Blueprint $table) {
                $table->id();
                $table->foreign('user_id')->references('user_id')->on('user')->onDelete('cascade');
                // $table->foreign('user_id')->references('id')->on('user')->onDelete('cascade');
                $table->string('title');
                $table->text('content');
                $table->json('tags'); // 存储标签为 JSON 格式
                $table->string('image')->nullable(); // 可选的图片字段
                $table->string('video')->nullable(); // 可选的视频字段
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('post_media')) {
            Schema::create('post_media', function (Blueprint $table) {
                $table->id();
                $table->foreignId('post_id')->constrained()->onDelete('cascade');
                $table->string('media_path');
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('likes')) {
            Schema::create('likes', function (Blueprint $table) {
                $table->id();
                $table->foreign('user_id')->references('user_id')->on('user')->onDelete('cascade');
                $table->foreignId('post_id')->constrained()->onDelete('cascade');
                $table->timestamps();
                $table->unique(['user_id', 'post_id']);
            });
        }

        if (!Schema::hasTable('comments')) {
            Schema::create('comments', function (Blueprint $table) {
                $table->id();
                $table->foreign('user_id')->references('user_id')->on('user')->onDelete('cascade');
                $table->foreignId('post_id')->constrained()->onDelete('cascade');
                $table->text('content');
                $table->unsignedInteger('likes_count')->default(0);
                $table->unsignedInteger('dislikes_count')->default(0);
                $table->foreignId('parent_id')->nullable()->constrained('comments')->onDelete('cascade');
                $table->boolean('pinned')->default(0);
                $table->json('liked_users')->nullable();    // 点赞过的用户ID集合
                $table->json('disliked_users')->nullable(); // 点踩过的用户ID集合
                $table->enum('status', ['discussion','resolved'])->nullable();
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('bookmarks')) {
            Schema::create('bookmarks', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained()->onDelete('cascade');
                $table->foreignId('post_id')->constrained()->onDelete('cascade');
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('events')) {
            Schema::create('events', function (Blueprint $table) {
                $table->id();
                $table->foreign('user_id')->references('user_id')->on('user')->onDelete('cascade');
                $table->string('title');
                $table->text('description');
                $table->dateTime('start_time');
                $table->dateTime('end_time');
                $table->string('location')->nullable();
                $table->dateTime('event_date');
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('event_registrations')) {
            Schema::create('event_registrations', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained()->onDelete('cascade');
                $table->foreignId('event_id')->constrained()->onDelete('cascade');
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('friend_requests')) {
            Schema::create('friend_requests', function (Blueprint $table) {
                $table->id();
                $table->foreignId('sender_id')->references('id')->on('user')->onDelete('cascade');
                $table->foreignId('receiver_id')->references('id')->on('user')->onDelete('cascade');
                $table->enum('status', ['pending', 'accepted', 'declined'])->default('pending');
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('friends')) {
            Schema::create('friends', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->references('id')->on('user')->onDelete('cascade');
                $table->foreignId('friend_id')->references('id')->on('user')->onDelete('cascade');
                $table->enum('status', ['pending', 'accepted', 'rejected'])->default('pending');
                $table->timestamps();
            });
        }
        if (!Schema::hasTable('messages')) {
            Schema::create('messages', function (Blueprint $table) {
                $table->id();
                $table->foreign('user_id')->references('user_id')->on('user')->onDelete('cascade');
                $table->foreign('from_user_id')->references('user_id')->on('user')->onDelete('cascade');
                $table->foreign('to_user_id')->references('user_id')->on('user')->onDelete('cascade');
                $table->text('content')->nullable();
                $table->tinyInteger('is_read')->default(0);
                $table->foreign('sender_id')->references('user_id')->on('user')->onDelete('cascade');
                $table->foreign('receiver_id')->references('user_id')->on('user')->onDelete('cascade');
                $table->foreignId('group_id')->nullable()->constrained()->onDelete('cascade');
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('groups')) {
            Schema::create('groups', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->foreign('owner_id')->references('user_id')->on('user')->onDelete('cascade');
                // $table->foreignId('owner_id')->constrained('user')->onDelete('cascade');
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('group_members')) {
            Schema::create('group_members', function (Blueprint $table) {
                $table->id();
                $table->foreignId('group_id')->constrained()->onDelete('cascade');
                $table->foreignId('user_id')->constrained()->onDelete('cascade');
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('payments')) {
            Schema::create('payments', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained()->onDelete('cascade');
                $table->string('payment_method')->nullable();
                $table->decimal('amount', 10, 2);
                $table->enum('status', ['pending', 'completed', 'failed'])->default('pending');
                $table->timestamps();
            });
        }
    }

    public function down() {
        Schema::dropIfExists('payments');
        Schema::dropIfExists('messages');
        Schema::dropIfExists('group_members');
        Schema::dropIfExists('groups');
        Schema::dropIfExists('friends');
        Schema::dropIfExists('friend_requests');
        Schema::dropIfExists('event_registrations');
        Schema::dropIfExists('events');
        Schema::dropIfExists('bookmarks');
        Schema::dropIfExists('comments');
        Schema::dropIfExists('likes');
        Schema::dropIfExists('post_media');
        Schema::dropIfExists('posts');
        Schema::dropIfExists('categories');
        Schema::dropIfExists('user');
    }
};
