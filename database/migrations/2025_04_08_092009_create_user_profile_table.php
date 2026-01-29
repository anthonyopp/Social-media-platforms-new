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
        if (!Schema::hasTable('user_profile')) {
            Schema::create('user_profile', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('user_id')->unique();
                $table->string('profile_picture')->nullable()->default('default-avatar.png');
                $table->string('background_image')->nullable()->default('default-bg.jpeg');
                $table->text('bio')->nullable();
                $table->string('signature')->nullable();
                $table->timestamps();

                $table->foreign('user_id')->references('user_id')->on('user')->onDelete('cascade');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_profile');
    }
};
