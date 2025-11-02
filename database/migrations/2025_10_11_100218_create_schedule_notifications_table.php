<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
   
    public function up(): void
    {
        Schema::create('schedule_notifications', function (Blueprint $table) {
            $table->id();
            $table->json('type')->comment('email','push','whatsapp')->nullable();
            $table->string('title');
            $table->string('description')->nullable();
            $table->string('target_type')->nullable();
            $table->dateTime('schedule_time')->nullable();
            $table->enum('status',['pending','sent','failed'])->default('pending');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('schedule_notifications');
    }
};
