<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TelegramDeliveryLog extends Model
{
    protected $fillable = [
        'chat_id',
        'message',
        'status_code',
        'is_success',
        'response_payload'
    ];
}