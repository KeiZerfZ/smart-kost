<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WaLog extends Model
{
    protected $fillable = ['invoice_id', 'recipient_number', 'message', 'status'];

    public function invoice()
    {
        return $this->belongsTo(Invoice::class);
    }
}