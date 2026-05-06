<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Room extends Model
{
    protected $fillable = ['room_number', 'price', 'status'];

    // Relasi ke penghuni yang menempati kamar 
    public function tenant(): HasOne
    {
        return $this->hasOne(Tenant::class);
    }
}