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
        Schema::create('complaints', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained(); // Relasi ke penghuni
            $table->text('description'); // Detail kerusakan
            $table->string('photo')->nullable(); // Foto bukti (opsional)
            $table->enum('status', ['pending', 'process', 'resolved'])->default('pending'); // Status tracking 
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('complaints');
    }
};
