<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Tenant extends Model
{
    protected $fillable = [
        'user_id', 
        'room_id', 
        'phone',
        'telegram_chat_id', 
        'id_card_photo', 
        'entry_date', 
        'status'
    ];

    protected $casts = [
        'entry_date' => 'date',
    ];

    protected function ktpUrl(): Attribute
    {
        return Attribute::make(
            get: function () {
                if (!$this->id_card_photo) {
                    return asset('images/default-ktp.png');
                }
                $filename = str_replace('ktp_photos/', '', $this->id_card_photo);
                return route('tenants.ktp.show', $filename);
            }
        );
    }

    public function user(): BelongsTo { return $this->belongsTo(User::class); }
    public function room(): BelongsTo { return $this->belongsTo(Room::class); }
    public function invoices(): HasMany { return $this->hasMany(Invoice::class); }
}