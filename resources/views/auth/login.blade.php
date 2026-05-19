@extends('layouts.guest')

@section('content')
<div class="bg-white p-6 sm:p-10 rounded-[2.5rem] shadow-2xl shadow-blue-100 border border-gray-50 w-full max-w-md animate-shrink-in">
    <div class="text-center mb-10">
        <h1 class="text-4xl sm:text-5xl font-black text-blue-600 tracking-tighter italic">SmartKost.</h1>
        <p class="text-gray-400 font-bold text-xs mt-3 uppercase tracking-widest">Management System</p>
    </div>

    @if (session('registration_completed'))
        <div class="mb-6 p-5 bg-blue-50 border border-blue-200 rounded-2xl space-y-3">
            <p class="text-xs text-blue-800 font-black uppercase tracking-wider">
                📍 Langkah Penting Terakhir!
            </p>
            <p class="text-[11px] text-blue-600 font-semibold leading-relaxed">
                Pendaftaran data Anda berhasil disimpan. Agar Anda dapat menerima notifikasi verifikasi akun dan bukti kuitansi PDF, Anda <b>wajib</b> mengaktifkan bot kami sekarang.
            </p>
            <a href="https://t.me/Manajer_SmartKost_Bot" target="_blank" 
               class="flex items-center justify-center space-x-2 bg-blue-600 text-white font-black py-2.5 rounded-xl text-[10px] uppercase tracking-widest hover:bg-blue-700 transition">
                <span>Mulai Chat Bot (/start)</span>
            </a>
            <p class="text-[9px] text-gray-400 italic text-center">
                *Akun Anda tidak dapat diverifikasi jika langkah ini dilewati.
            </p>
        </div>
    @endif

    @if (session('success'))
        <div class="mb-6 p-4 bg-green-50 border-l-4 border-green-500 rounded-2xl">
            <p class="text-xs text-green-700 font-black uppercase tracking-wider mb-1">Notifikasi</p>
            <p class="text-[10px] text-green-600 font-semibold leading-relaxed">
                {{ session('success') }}
            </p>
        </div>
    @endif

    @if ($errors->any())
        <div class="mb-6 p-4 bg-red-50 border-l-4 border-red-500 rounded-2xl">
            <p class="text-xs text-red-700 font-black uppercase tracking-wider mb-1">Akses Ditolak!</p>
            <ul class="text-[10px] text-red-600 font-semibold list-disc list-inside">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('login') }}" class="space-y-5">
        @csrf
        <div>
            <label class="block text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] mb-2 ml-1">Alamat Email</label>
            <input type="email" name="email" placeholder="nama@email.com" 
                   class="w-full p-4 bg-gray-50 border border-gray-100 rounded-2xl focus:ring-4 focus:ring-blue-100 outline-none transition font-semibold" required autofocus>
        </div>

        <div>
            <div class="flex justify-between mb-2 ml-1">
                <label class="block text-[10px] font-black text-gray-400 uppercase tracking-[0.2em]">Kata Sandi</label>
                <a href="{{ route('password.request') }}" class="text-[10px] font-black text-blue-600 hover:underline uppercase tracking-widest">Lupa?</a>
            </div>
            <input type="password" name="password" placeholder="••••••••" 
                   class="w-full p-4 bg-gray-50 border border-gray-100 rounded-2xl focus:ring-4 focus:ring-blue-100 outline-none transition font-semibold" required>
        </div>

        <button type="submit" class="w-full bg-blue-600 text-white font-black py-5 rounded-3xl shadow-xl shadow-blue-200 hover:bg-blue-700 transition transform hover:-translate-y-1 active:scale-95 duration-200 uppercase text-xs tracking-[0.2em]">
            Masuk Sekarang
        </button>
    </form>

    <div class="mt-10 pt-6 border-t border-gray-100 text-center space-y-2">
        <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest">
            Belum memiliki akun penghuni?
        </p>
        <a href="{{ route('register') }}" class="inline-block text-xs font-black text-blue-600 hover:underline uppercase tracking-widest">
            Ajukan Pendaftaran Mandiri
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