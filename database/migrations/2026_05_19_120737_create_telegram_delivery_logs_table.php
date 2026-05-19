<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('telegram_delivery_logs', function (Blueprint $table) {
            $table->id();
            $table->string('chat_id');
            $table->text('message');
            $table->integer('status_code')->nullable();
            $table->boolean('is_success');
            $table->text('response_payload')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('telegram_delivery_logs');
    }
};