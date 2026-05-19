<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\TenantDashboardController;
use App\Http\Controllers\RoomController;
use App\Http\Controllers\TenantController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\ComplaintController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Auth\RegisterController;

/*
|--------------------------------------------------------------------------
| Web Routes - SmartKost Management System
|--------------------------------------------------------------------------
*/

// Pengalihan halaman utama ke halaman login
Route::get('/', function () {
    return redirect()->route('login');
});

// Menonaktifkan fitur registrasi bawaan Laravel Auth Scaffolding
Auth::routes(['register' => false]);

// --- REGISTRASI MANDIRI CALON TENANT (PUBLIC GUEST) ---
// Batasan dilonggarkan menjadi maksimal 10 kali percobaan per 1 menit
Route::middleware(['guest', 'throttle:10,1'])->group(function () {
    Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [RegisterController::class, 'register'])->name('register.store');
    
    // Rute Baru untuk Halaman Sukses Registrasi Mandiri
    Route::get('/register/success', [RegisterController::class, 'success'])->name('register.success');
});

// --- PROTEKSI OTENTIKASI (AUTHENTICATED USERS) ---
Route::middleware('auth')->group(function () {

    // Rute Fallback Home untuk penanganan internal Laravel Auth redirect
    Route::get('/home', function () {
        return auth()->user()->role == 'owner' 
            ? redirect()->route('admin.dashboard') 
            : redirect()->route('tenant.dashboard');
    })->name('home');

    /**
     * PENGATURAN PROFIL UNIVERSAL
     */
    Route::controller(ProfileController::class)->group(function () {
        Route::get('/profile', 'index')->name('profile.index');
        Route::patch('/profile', 'update')->name('profile.update'); 
        Route::put('/profile/password', 'updatePassword')->name('profile.password');
    });

    /**
     * UNDUH KUITANSI DIGITAL (PDF)
     */
    Route::get('/invoices/{invoice}/download', [InvoiceController::class, 'download'])->name('invoices.download');

    // --- GRUP HAK AKSES: OWNER (ADMIN) ---
    Route::middleware('role:owner')->prefix('admin')->group(function () {
        
        // Dashboard Utama Admin
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('admin.dashboard');

        // Manajemen Verifikasi Pendaftaran Tenant Mandiri & Gatekeeper Dokumen KTP
        Route::controller(TenantController::class)->group(function () {
            Route::get('/tenants/verification', 'verificationIndex')->name('tenants.verification.index');
            Route::patch('/tenants/{tenant}/approve', 'approve')->name('tenants.approve');
            Route::patch('/tenants/{tenant}/reject', 'reject')->name('tenants.reject');
            Route::get('/tenants/ktp/{filename}', 'showKtp')->name('tenants.ktp.show')->where('filename', '.*');
        });
        
        // Sumber Daya Data Utama (Resource Routes)
        Route::resource('rooms', RoomController::class);
        Route::resource('tenants', TenantController::class);

        // Manajemen Akun Pengguna Keamanan Sistem
        Route::controller(UserController::class)->group(function () {
            Route::get('/users', 'index')->name('users.index');
            Route::delete('/users/{user}', 'destroy')->name('users.destroy');
            Route::patch('/users/{user}/reset', 'resetPassword')->name('users.reset');
        });

        // Manajemen Keuangan Sisi Admin
        Route::controller(InvoiceController::class)->group(function () {
            Route::get('/invoices', 'index')->name('invoices.index');
            Route::post('/invoices/manual', 'store')->name('invoices.store');
            Route::patch('/invoices/{invoice}/pay', 'pay')->name('invoices.pay'); 
        });

        // Pelaporan Keluhan Sisi Admin
        Route::controller(ComplaintController::class)->group(function () {
            Route::get('/complaints', 'index')->name('complaints.index');
            Route::patch('/complaints/{complaint}/status', 'updateStatus')->name('complaints.updateStatus');
        });
    });

    // --- GRUP HAK AKSES: TENANT (PENGHUNI) ---
    Route::middleware('role:tenant')->prefix('tenant')->group(function () {
        
        // Dashboard Utama Tenant
        Route::get('/dashboard', [TenantDashboardController::class, 'index'])->name('tenant.dashboard');
        
        // Konfirmasi Pembayaran QRIS Mandiri via Web
        Route::patch('/invoices/{invoice}/qris', [InvoiceController::class, 'payQRIS'])->name('invoices.payQRIS');
        
        // Pengajuan Keluhan Fasilitas Baru
        Route::post('/complaints', [ComplaintController::class, 'store'])->name('complaints.store');
    });
});