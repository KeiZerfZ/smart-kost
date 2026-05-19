@extends('layouts.app')

@section('content')
<div class="mb-8">
    <h1 class="text-3xl font-black text-gray-800 dark:text-gray-100 tracking-tighter transition-colors">
        Ringkasan Instrumen.
    </h1>
    <p class="text-gray-500 dark:text-gray-400 font-medium transition-colors">
        Pantau performa bisnis, statistik fungsional, dan arus kas secara berkala.
    </p>
</div>

<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-5 gap-4 md:gap-6 mb-8">

    <div class="bg-white dark:bg-slate-900 p-6 rounded-[2rem] shadow-sm border border-gray-100 dark:border-slate-800 flex flex-col justify-between hover:shadow-md dark:hover:bg-slate-800/40 transition duration-300">
        <div class="flex justify-between items-start mb-4">
            <div class="p-3 bg-blue-50 dark:bg-blue-900/30 rounded-2xl text-blue-600 dark:text-blue-400 transition-colors">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                </svg>
            </div>
            <span class="text-[10px] font-black text-gray-400 dark:text-gray-500 uppercase tracking-widest italic">Rooms</span>
        </div>
        <div>
            <p class="text-xs font-bold text-gray-400 dark:text-gray-500 uppercase tracking-widest mb-1">Total Kamar</p>
            <h3 class="text-3xl font-black text-blue-600 dark:text-blue-400">{{ $totalRooms }}</h3>
            <p class="text-[11px] text-green-600 dark:text-green-400 font-bold mt-2 flex items-center">
                <span class="w-2 h-2 bg-green-500 rounded-full mr-2 animate-pulse"></span>
                {{ $emptyRooms }} Tersedia
            </p>
        </div>
    </div>

    <div class="bg-white dark:bg-slate-900 p-6 rounded-[2rem] shadow-sm border border-gray-100 dark:border-slate-800 flex flex-col justify-between hover:shadow-md dark:hover:bg-slate-800/40 transition duration-300">
        <div class="flex justify-between items-start mb-4">
            <div class="p-3 bg-purple-50 dark:bg-purple-900/30 rounded-2xl text-purple-600 dark:text-purple-400 transition-colors">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                </svg>
            </div>
            <span class="text-[10px] font-black text-gray-400 dark:text-gray-500 uppercase tracking-widest italic">Tenants</span>
        </div>
        <div>
            <p class="text-xs font-bold text-gray-400 dark:text-gray-500 uppercase tracking-widest mb-1">Penghuni Aktif</p>
            <h3 class="text-3xl font-black text-gray-800 dark:text-gray-100">{{ $totalTenants }}</h3>
            <p class="text-[11px] text-gray-400 dark:text-gray-500 font-bold mt-2">Terverifikasi di sistem</p>
        </div>
    </div>

    <div class="bg-white dark:bg-slate-900 p-6 rounded-[2rem] shadow-sm border border-gray-100 dark:border-slate-800 flex flex-col justify-between hover:shadow-md dark:hover:bg-slate-800/40 transition duration-300">
        @php $pendingCount = \App\Models\Tenant::where('status', 'pending')->count(); @endphp
        <div class="flex justify-between items-start mb-4">
            <div class="p-3 bg-red-50 dark:bg-red-900/20 text-red-500 dark:text-red-400 transition-colors">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                </svg>
            </div>
            <span class="text-[10px] font-black text-gray-400 dark:text-gray-500 uppercase tracking-widest italic">Approval</span>
        </div>
        <div>
            <p class="text-xs font-bold text-gray-400 dark:text-gray-500 uppercase tracking-widest mb-1">Registrasi Pending</p>
            <h3 class="text-3xl font-black {{ $pendingCount > 0 ? 'text-red-500 animate-pulse' : 'text-gray-800 dark:text-gray-100' }}">{{ $pendingCount }}</h3>
            <p class="text-[11px] font-bold mt-2 uppercase tracking-tight {{ $pendingCount > 0 ? 'text-red-400' : 'text-gray-400' }}">
                {{ $pendingCount > 0 ? '⚠️ Butuh Tindakan' : 'Evaluasi Bersih' }}
            </p>
        </div>
    </div>

    <div class="bg-white dark:bg-slate-900 p-6 rounded-[2rem] shadow-sm border border-gray-100 dark:border-slate-800 flex flex-col justify-between hover:shadow-md dark:hover:bg-slate-800/40 transition duration-300">
        <div class="flex justify-between items-start mb-4">
            <div class="p-3 bg-orange-50 dark:bg-orange-900/30 rounded-2xl text-orange-500 dark:text-orange-400 transition-colors">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                </svg>
            </div>
            <span class="text-[10px] font-black text-gray-400 dark:text-gray-500 uppercase tracking-widest italic">Complaints</span>
        </div>
        <div>
            <p class="text-xs font-bold text-gray-400 dark:text-gray-500 uppercase tracking-widest mb-1">Keluhan Pending</p>
            <h3 class="text-3xl font-black text-orange-500 dark:text-orange-400">{{ $pendingComplaints }}</h3>
            <p class="text-[11px] text-orange-400 dark:text-orange-300 font-bold mt-2 uppercase tracking-tighter">Laporan Kerusakan</p>
        </div>
    </div>

    <div class="bg-white dark:bg-slate-900 p-6 rounded-[2rem] shadow-sm border border-gray-100 dark:border-slate-800 flex flex-col justify-between hover:shadow-md dark:hover:bg-slate-800/40 transition duration-300">
        <div class="flex justify-between items-start mb-4">
            <div class="p-3 bg-green-50 dark:bg-green-900/30 rounded-2xl text-green-600 dark:text-green-400 transition-colors">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
            <span class="text-[10px] font-black text-gray-400 dark:text-gray-500 uppercase tracking-widest italic">Revenue</span>
        </div>
        <div>
            <p class="text-xs font-bold text-gray-400 dark:text-gray-500 uppercase tracking-widest mb-1">Kas Bulan Ini</p>
            <h3 class="text-xl md:text-2xl font-black text-green-600 dark:text-green-400 truncate">Rp {{ number_format($monthlyIncome, 0, ',', '.') }}</h3>
            <p class="text-[11px] text-gray-400 dark:text-gray-500 font-bold mt-2 italic">Akumulasi lunas</p>
        </div>
    </div>
</div>

<div class="bg-blue-600 dark:bg-blue-700 p-8 md:p-12 rounded-[2.5rem] text-center shadow-xl shadow-blue-100 dark:shadow-none relative overflow-hidden transition-colors">
    <div class="absolute -top-10 -right-10 w-40 h-40 bg-blue-500 dark:bg-blue-600 rounded-full opacity-50"></div>
    <div class="absolute -bottom-10 -left-10 w-32 h-32 bg-blue-700 dark:bg-blue-800 rounded-full opacity-50"></div>

    <div class="relative z-10">
        <h2 class="text-2xl md:text-3xl font-black text-white mb-2 tracking-tight">
            Selamat Datang di Panel Utama Administrator
        </h2>
        <p class="text-blue-100 dark:text-blue-200 font-medium max-w-xl mx-auto leading-relaxed">
            Seluruh komponen arsitektur sistem berjalan dengan normal. Gunakan menu navigasi eksternal atau tombol jalan pintas di bawah ini untuk mengelola operasional hunian.
        </p>

        <div class="mt-8 flex flex-wrap justify-center gap-4">
            <a href="{{ route('tenants.verification.index') }}"
                class="bg-white text-blue-600 dark:bg-slate-900 dark:text-blue-400 px-6 py-3 rounded-2xl font-black text-xs uppercase tracking-widest hover:bg-blue-50 dark:hover:bg-slate-800 transition shadow-lg">
                Periksa Verifikasi Akun
            </a>
            <a href="{{ route('rooms.index') }}"
                class="bg-blue-700 text-white dark:bg-blue-800 px-6 py-3 rounded-2xl font-black text-xs uppercase tracking-widest hover:bg-blue-800 dark:hover:bg-blue-900 transition shadow-lg">
                Manajemen Kamar
            </a>
            <a href="{{ route('tenants.index') }}"
                class="bg-blue-700 text-white dark:bg-blue-800 px-6 py-3 rounded-2xl font-black text-xs uppercase tracking-widest hover:bg-blue-800 dark:hover:bg-blue-900 transition shadow-lg">
                Manajemen Penghuni
            </a>
        </div>
    </div>
</div>
@endsection