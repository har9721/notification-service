<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('schedule_notification_users', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('notification_id');
            $table->foreign('notification_id')->references('id')->on('schedule_notifications')->onDelete('cascade');
            $table->unsignedBigInteger('user_ID')->nullable();
            $table->foreign('user_ID')->references('id')->on('users')->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('schedule_notification_users');
    }
};
