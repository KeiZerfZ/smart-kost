<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Support\Facades\Storage;

class Tenant extends Model
{
    protected $fillable = ['user_id', 'room_id', 'phone', 'id_card_photo', 'entry_date'];

    protected $casts = [
        'entry_date' => 'date'
    ];

    /**
     * Accessor untuk mendapatkan URL full foto KTP.
     * Panggil di Blade dengan: {{ $tenant->id_card_photo_url }}
     */
    protected function idCardPhotoUrl(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->id_card_photo 
                ? asset('storage/' . $this->id_card_photo) 
                : asset('images/default-ktp.png'), // Opsional: default image
        );
    }

    public function user(): BelongsTo 
    { 
        return $this->belongsTo(User::class); 
    }

    public function room(): BelongsTo 
    { 
        return $this->belongsTo(Room::class); 
    }
    
    public function invoices(): HasMany
    {
        return $this->hasMany(Invoice::class);
    }
}