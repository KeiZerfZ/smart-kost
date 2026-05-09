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
        Schema::create('rooms', function (Blueprint $table) {
            $table->id();
            $table->string('room_number'); 
            $table->string('type'); 
            
            // Pakai bigInteger biar kuat nampung milyaran
            $table->bigInteger('price'); 
            
            $table->enum('status', ['empty', 'occupied'])->default('empty');
            
            // Pastikan urutannya begini biar 1 VIP & 1 Reguler dianggap beda
            $table->unique(['room_number', 'type']);
            
            $table->timestamps();
        });
    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rooms');
    }
};
