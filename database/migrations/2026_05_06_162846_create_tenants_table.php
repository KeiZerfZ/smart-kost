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
        Schema::create('tenants', function (Blueprint $table) {
            $table->id();
            // onDelete('cascade') biar kalau User dihapus, data Tenant ini ikut hilang
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); 
            $table->foreignId('room_id')->constrained(); 
            $table->string('phone'); // <--- Tambahkan baris sakti ini
            $table->string('id_card_photo')->nullable(); 
            $table->date('entry_date'); 
            $table->timestamps();
        });        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tenants');
    }
};