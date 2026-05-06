<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RoomController;
use App\Http\Controllers\TenantController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\ComplaintController;
use Illuminate\Support\Facades\Auth;

// 1. Route Home - Langsung ke Dashboard/Login
Route::get('/', function () {
    return redirect()->route('login');
});

// 2. Route Autentikasi (Bisa pakai Laravel Breeze/Fortify atau Manual)
// Jika lu pakai Laravel Starter Kit, baris di bawah biasanya sudah otomatis
Auth::routes(); 

// 3. Grup Route yang Wajib Login
Route::middleware(['auth'])->group(function () {

    // --- FITUR KHUSUS PEMILIK (OWNER) ---
    // Menggunakan middleware 'CheckRole' yang lu buat sebelumnya
    Route::middleware(['role:owner'])->group(function () {
        
        // Manajemen Kamar (CRUD) [cite: 64]
        Route::get('/rooms', [RoomController::class, 'index'])->name('rooms.index');
        Route::post('/rooms', [RoomController::class, 'store'])->name('rooms.store');
        Route::put('/rooms/{room}', [RoomController::class, 'update'])->name('rooms.update');

        // Manajemen Penghuni [cite: 64]
        Route::get('/tenants', [TenantController::class, 'index'])->name('tenants.index');
        Route::post('/tenants', [TenantController::class, 'store'])->name('tenants.store');

        // Konfirmasi Pembayaran Manual [cite: 65]
        Route::patch('/invoices/{invoice}/paid', [InvoiceController::class, 'markAsPaid'])->name('invoices.paid');
    });

    // --- FITUR UMUM (Bisa diakses Pemilik & Penghuni) ---
    
    // Daftar Tagihan (Owner liat semua, Tenant liat miliknya sendiri) [cite: 65, 66]
    Route::get('/invoices', [InvoiceController::class, 'index'])->name('invoices.index');

    // (Opsional) Modul Keluhan/Laporan Kerusakan [cite: 65]
    // Route::resource('complaints', ComplaintController::class);
});
Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

// Contoh memproteksi manajemen kamar yang hanya boleh diakses Pemilik Kost (owner)
Route::get('/rooms', [RoomController::class, 'index'])
    ->middleware('role:owner')
    ->name('rooms.index');

// Contoh memproteksi halaman keluhan yang bisa diakses oleh Penghuni (tenant)
Route::get('/my-complaints', [ComplaintController::class, 'index'])
    ->middleware('role:tenant')
    ->name('complaints.index');