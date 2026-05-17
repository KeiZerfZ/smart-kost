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

/*
|--------------------------------------------------------------------------
| Web Routes - SmartKost Management System (Final Build 2026.05.17)
|--------------------------------------------------------------------------
*/

// --- 1. LANDING & AUTH ---
Route::get('/', function () {
    return redirect()->route('login');
});

// Fitur Register dimatikan: Pendaftaran hanya via Admin
Auth::routes(['register' => false]);

// --- 2. AUTHENTICATED AREA (Wajib Login) ---
Route::middleware('auth')->group(function () {

    /**
     * LOGIKA REDIRECT HOME
     */
    Route::get('/home', function () {
        if (auth()->user()->role == 'owner') {
            return redirect()->route('admin.dashboard');
        }
        return redirect()->route('tenant.dashboard');
    })->name('home');

    /**
     * PENGATURAN PROFIL (Owner & Tenant)
     */
    Route::controller(ProfileController::class)->group(function () {
        Route::get('/profile', 'index')->name('profile.index');
        Route::patch('/profile', 'update')->name('profile.update');
        Route::put('/profile/password', 'updatePassword')->name('profile.password');
    });

    /**
     * FITUR DOWNLOAD PDF (Akses Umum Terautentikasi)
     */
    Route::get('/invoices/{invoice}/download', [InvoiceController::class, 'download'])->name('invoices.download');

    // ==========================================
    // --- GRUP KHUSUS OWNER (PEMILIK/ADMIN) ---
    // ==========================================
    Route::middleware('role:owner')->prefix('admin')->group(function () {
        
        // Dashboard & Statistik
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('admin.dashboard');

        // CRUD MASTER DATA & SECURITY
        Route::controller(TenantController::class)->group(function () {
            /**
             * URL HARDENING: Pintu khusus akses berkas KTP.
             * Mengambil file dari private storage (disk: local).
             */
            Route::get('/tenants/ktp/{filename}', 'showKtp')
                ->name('tenants.ktp.show')
                ->where('filename', '.*');
        });
        
        Route::resource('rooms', RoomController::class);
        Route::resource('tenants', TenantController::class);

        // MANAJEMEN AKUN USER
        Route::controller(UserController::class)->group(function () {
            Route::get('/users', 'index')->name('users.index');
            Route::delete('/users/{user}', 'destroy')->name('users.destroy');
            Route::patch('/users/{user}/reset', 'resetPassword')->name('users.reset');
        });

        // MANAJEMEN KEUANGAN & TAGIHAN
        Route::controller(InvoiceController::class)->group(function () {
            Route::get('/invoices', 'index')->name('invoices.index');
            Route::post('/invoices/manual', 'store')->name('invoices.store');
            Route::patch('/invoices/{invoice}/pay', 'pay')->name('invoices.pay'); 
        });

        // MANAJEMEN KELUHAN
        Route::controller(ComplaintController::class)->group(function () {
            Route::get('/complaints', 'index')->name('complaints.index');
            Route::patch('/complaints/{complaint}/status', 'updateStatus')->name('complaints.updateStatus');
        });
    });

    // ==========================================
    // --- GRUP KHUSUS TENANT (PENGHUNI) ---
    // ==========================================
    Route::middleware('role:tenant')->prefix('tenant')->group(function () {
        
        Route::get('/dashboard', [TenantDashboardController::class, 'index'])->name('tenant.dashboard');
        
        // Pembayaran Mandiri
        Route::patch('/invoices/{invoice}/qris', [InvoiceController::class, 'payQRIS'])->name('invoices.payQRIS');

        // Lapor Keluhan
        Route::post('/complaints', [ComplaintController::class, 'store'])->name('complaints.store');
    });

});