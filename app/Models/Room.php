<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    protected $fillable = ['room_number', 'type', 'price', 'status'];

    // Relasi ke Tenant (Satu kamar punya satu penghuni aktif)
    public function tenant()
    {
        return $this->hasOne(Tenant::class);
    }
}