@extends('layouts.guest')

@section('content')
<div class="bg-white p-6 sm:p-10 rounded-[2.5rem] shadow-2xl shadow-blue-100 border border-gray-50 w-full max-w-md animate-shrink-in">
    <div class="text-center mb-8">
        <h2 class="text-3xl font-black text-gray-800 tracking-tighter italic">Sandi Baru.</h2>
        <p class="text-gray-400 font-bold text-[10px] mt-2 uppercase tracking-widest encoding-relaxed px-4">
            Silakan konfigurasi kata sandi baru akun Anda di bawah ini.
        </p>
    </div>

    @if ($errors->any())
        <div class="mb-6 p-4 bg-red-50 border-l-4 border-red-500 rounded-2xl">
            <p class="text-xs text-red-700 font-black uppercase tracking-wider mb-1">Pembaruan Gagal!</p>
            <ul class="text-[10px] text-red-600 font-semibold list-disc list-inside">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('password.update') }}" class="space-y-5">
        @csrf
        <input type="hidden" name="token" value="{{ $token }}">

        <div>
            <label class="block text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] mb-2 ml-1">Alamat Email</label>
            <input type="email" name="email" value="{{ $email ?? old('email') }}" 
                   class="w-full p-4 bg-gray-50 border border-gray-100 rounded-2xl outline-none font-semibold text-gray-400 cursor-not-allowed" readonly>
        </div>

        <div>
            <label class="block text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] mb-2 ml-1">Kata Sandi Baru</label>
            <input type="password" name="password" placeholder="••••••••" 
                   class="w-full p-4 bg-gray-50 border border-gray-100 rounded-2xl focus:ring-4 focus:ring-blue-100 outline-none transition font-semibold" required autofocus>
        </div>

        <div>
            <label class="block text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] mb-2 ml-1">Konfirmasi Kata Sandi</label>
            <input type="password" name="password_confirmation" placeholder="••••••••" 
                   class="w-full p-4 bg-gray-50 border border-gray-100 rounded-2xl focus:ring-4 focus:ring-blue-100 outline-none transition font-semibold" required>
        </div>

        <button type="submit" class="w-full bg-blue-600 text-white font-black py-5 rounded-3xl shadow-xl shadow-blue-200 hover:bg-blue-700 transition transform hover:-translate-y-1 active:scale-95 duration-200 uppercase text-xs tracking-[0.2em]">
            Perbarui Kata Sandi
        </button>
    </form>
</div>

<style>
    @keyframes shrink-in {
        0% { opacity: 0; transform: scale(0.95); }
        100% { opacity: 1; transform: scale(1); }
    }
    .animate-shrink-in { animation: shrink-in 0.4s ease-out; }
</style>
@endsection