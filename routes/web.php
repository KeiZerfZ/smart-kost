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
| Web Routes - SmartKost Management System (Build 2026.05.18)
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return redirect()->route('login');
});

Auth::routes(['register' => false]);

Route::middleware('auth')->group(function () {

    Route::get('/home', function () {
        return auth()->user()->role == 'owner' 
            ? redirect()->route('admin.dashboard') 
            : redirect()->route('tenant.dashboard');
    })->name('home');

    /**
     * PENGATURAN PROFIL
     */
    Route::controller(ProfileController::class)->group(function () {
        Route::get('/profile', 'index')->name('profile.index');
        Route::patch('/profile', 'update')->name('profile.update'); // Update data & avatar
        Route::put('/profile/password', 'updatePassword')->name('profile.password');
    });

    Route::get('/invoices/{invoice}/download', [InvoiceController::class, 'download'])->name('invoices.download');

    // --- GRUP OWNER ---
    Route::middleware('role:owner')->prefix('admin')->group(function () {
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('admin.dashboard');

        Route::controller(TenantController::class)->group(function () {
            Route::get('/tenants/ktp/{filename}', 'showKtp')->name('tenants.ktp.show')->where('filename', '.*');
        });
        
        Route::resource('rooms', RoomController::class);
        Route::resource('tenants', TenantController::class);

        Route::controller(UserController::class)->group(function () {
            Route::get('/users', 'index')->name('users.index');
            Route::delete('/users/{user}', 'destroy')->name('users.destroy');
            Route::patch('/users/{user}/reset', 'resetPassword')->name('users.reset');
        });

        Route::controller(InvoiceController::class)->group(function () {
            Route::get('/invoices', 'index')->name('invoices.index');
            Route::post('/invoices/manual', 'store')->name('invoices.store');
            Route::patch('/invoices/{invoice}/pay', 'pay')->name('invoices.pay'); 
        });

        Route::controller(ComplaintController::class)->group(function () {
            Route::get('/complaints', 'index')->name('complaints.index');
            Route::patch('/complaints/{complaint}/status', 'updateStatus')->name('complaints.updateStatus');
        });
    });

    // --- GRUP TENANT ---
    Route::middleware('role:tenant')->prefix('tenant')->group(function () {
        Route::get('/dashboard', [TenantDashboardController::class, 'index'])->name('tenant.dashboard');
        Route::patch('/invoices/{invoice}/qris', [InvoiceController::class, 'payQRIS'])->name('invoices.payQRIS');
        Route::post('/complaints', [ComplaintController::class, 'store'])->name('complaints.store');
    });

    // --- TESTING WA (Hapus jika sudah stabil) ---
    Route::get('/test-wa', function() {
        $target = '08xxxxxxxxxx'; 
        $message = "Halo Brok! Ini tes notifikasi dari sistem *SmartKost.*";
        return response()->json(\App\Services\FonnteService::send($target, $message));
    });
});