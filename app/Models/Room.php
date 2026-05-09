<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    protected $fillable = ['room_number', 'type', 'price', 'status'];

    public function tenants()
    {
        return $this->hasMany(Tenant::class);
    }

    public function invoices()
    {
        return $this->hasManyThrough(Invoice::class, Tenant::class);
    }
}