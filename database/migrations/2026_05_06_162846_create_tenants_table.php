<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tenants', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); 
            $table->foreignId('room_id')->constrained(); 
            $table->string('phone');
            $table->string('telegram_chat_id')->nullable();
            $table->string('id_card_photo')->nullable(); 
            $table->date('entry_date')->nullable(); // nullable karena baru diisi saat disetujui
            $table->enum('status', ['pending', 'active', 'inactive'])->default('pending'); // Menggantikan is_active
            $table->timestamps();
        });        
    }

    public function down(): void
    {
        Schema::dropIfExists('tenants');
    }
};