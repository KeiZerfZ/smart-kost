@extends('layouts.app')

@section('content')
<div class="min-h-[70vh] flex items-center justify-center">
    <div class="max-w-md w-full bg-white p-10 rounded-[2.5rem] shadow-xl shadow-blue-50 border border-gray-100 text-center">
        <div class="inline-flex items-center justify-center w-20 h-20 bg-orange-50 rounded-full mb-8">
            <svg class="w-10 h-10 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
            </svg>
        </div>

        <h2 class="text-2xl font-black text-gray-800 mb-3 tracking-tight">Satu Langkah Lagi!</h2>
        <p class="text-gray-500 text-sm leading-relaxed mb-8">
            Halo **{{ auth()->user()->name }}**, akun lu sudah aktif di sistem **SmartKost**. Tapi, admin belum menautkan data kamar lu ke profil ini.
        </p>

        <div class="space-y-4">
            <a href="https://wa.me/628123456789" target="_blank" class="block w-full bg-blue-600 text-white font-bold py-4 rounded-2xl shadow-lg shadow-blue-100 hover:bg-blue-700 transition">
                Hubungi Admin Kost
            </a>

            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="w-full text-gray-400 text-sm font-bold hover:text-red-500 transition">
                    Keluar Sekarang
                </button>
            </form>
        </div>

        <div class="mt-10 pt-6 border-t border-gray-50">
            <p class="text-[10px] text-gray-300 uppercase font-black tracking-widest">SmartKost Management System</p>
        </div>
    </div>
</div>
@endsection