@extends('layouts.guest')

@section('content')
<div class="bg-white p-6 sm:p-10 rounded-[2.5rem] shadow-2xl shadow-blue-100 border border-gray-50 w-full max-w-md animate-shrink-in">
    <div class="text-center mb-8">
        <div class="inline-flex items-center justify-center w-16 h-16 bg-blue-50 text-blue-600 rounded-2xl mb-6">
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"></path>
            </svg>
        </div>
        <h2 class="text-3xl font-black text-gray-800 tracking-tighter italic">Lupa Sandi?</h2>
        <p class="text-gray-400 font-bold text-[10px] mt-2 uppercase tracking-widest leading-relaxed px-4">
            Jangan panik. Masukin email lu, ntar gua kirimin link buat atur ulang.
        </p>
    </div>

    @if (session('status'))
        <div class="mb-6 p-4 bg-green-50 border-l-4 border-green-500 rounded-2xl animate-pulse">
            <p class="text-[10px] text-green-700 font-black uppercase tracking-widest leading-tight">
                {{ session('status') }}
            </p>
        </div>
    @endif

    @if ($errors->any())
        <div class="mb-6 p-4 bg-red-50 border-l-4 border-red-500 rounded-2xl">
            <ul class="text-[10px] text-red-600 font-bold list-disc list-inside">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('password.email') }}" class="space-y-6">
        @csrf
        <div>
            <label class="block text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] mb-2 ml-1">Email Terdaftar</label>
            <input type="email" name="email" placeholder="nama@email.com" 
                   class="w-full p-4 bg-gray-50 border border-gray-100 rounded-2xl focus:ring-4 focus:ring-blue-100 outline-none transition font-semibold" 
                   value="{{ old('email') }}" required autofocus>
        </div>

        <button type="submit" class="w-full bg-blue-600 text-white font-black py-5 rounded-3xl shadow-xl shadow-blue-200 hover:bg-blue-700 transition active:scale-95 duration-200 uppercase text-xs tracking-[0.2em]">
            Kirim Link Reset
        </button>
    </form>

    <div class="mt-8 pt-6 border-t border-gray-50 text-center">
        <a href="{{ route('login') }}" class="text-[10px] font-black text-blue-600 hover:text-blue-800 uppercase tracking-widest transition flex items-center justify-center space-x-2">
            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M15 19l-7-7 7-7"></path></svg>
            <span>Kembali ke Login</span>
        </a>
    </div>
</div>

<style>
    @keyframes shrink-in {
        0% { opacity: 0; transform: scale(0.95); }
        100% { opacity: 1; transform: scale(1); }
    }
    .animate-shrink-in { animation: shrink-in 0.4s ease-out; }
</style>
@endsection