<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Tenant extends Model
{
    protected $fillable = ['user_id', 'room_id', 'id_card_photo', 'entry_date'];

    protected $casts = ['entry_date' => 'date'];

    public function user(): BelongsTo { return $this->belongsTo(User::class); }
    public function room(): BelongsTo { return $this->belongsTo(Room::class); }
    
    // Relasi ke semua tagihan yang dimiliki penghuni [cite: 65]
    public function invoices(): HasMany
    {
        return $this->hasMany(Invoice::class);
    }
}