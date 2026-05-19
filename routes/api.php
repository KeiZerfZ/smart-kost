<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TelegramWebhookController;
use App\Http\Controllers\InvoiceController;

// Endpoint Webhook Telegram
Route::post('/telegram/webhook', TelegramWebhookController::class);

// Endpoint Simulasi Real Scan QRIS (Dapat di-scan via Kamera HP)
Route::get('/qris-simulate-scan/{invoice}', [InvoiceController::class, 'payQRISScan'])->name('invoices.payQRIS.scan');