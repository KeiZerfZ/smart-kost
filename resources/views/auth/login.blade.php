@extends('layouts.app')

@section('content')
<div class="min-h-[85vh] flex items-center justify-center">
    <div class="bg-white p-10 rounded-[2.5rem] shadow-2xl shadow-blue-100 border border-gray-50 w-full max-w-md">
        <div class="text-center mb-10">
            <h1 class="text-5xl font-black text-blue-600 tracking-tighter italic">SmartKost.</h1>
            <p class="text-gray-400 font-medium mt-3">Sistem Manajemen Kost Profesional</p>
        </div>

        @if ($errors->any())
            <div class="mb-6 p-4 bg-red-50 border-l-4 border-red-500 rounded-2xl">
                <p class="text-sm text-red-700 font-bold">Login Gagal!</p>
                <ul class="text-[11px] text-red-600 ml-7 list-disc">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}" class="space-y-6">
            @csrf
            <div>
                <label class="block text-xs font-black text-gray-400 uppercase tracking-widest mb-2 ml-1">Email</label>
                <input type="email" name="email" class="w-full p-4 bg-gray-50 border border-gray-100 rounded-2xl focus:ring-4 focus:ring-blue-100 outline-none" required autofocus>
            </div>

            <div>
                <div class="flex justify-between mb-2 ml-1">
                    <label class="block text-xs font-black text-gray-400 uppercase tracking-widest">Password</label>
                    <a href="{{ route('password.request') }}" class="text-xs font-bold text-blue-600 hover:underline">Lupa Password?</a>
                </div>
                <input type="password" name="password" class="w-full p-4 bg-gray-50 border border-gray-100 rounded-2xl focus:ring-4 focus:ring-blue-100 outline-none" required>
            </div>

            <button type="submit" class="w-full bg-blue-600 text-white font-black py-5 rounded-3xl shadow-xl shadow-blue-200 hover:bg-blue-700 transition transform hover:-translate-y-1">
                MASUK
            </button>
        </form>

        <div class="mt-10 pt-6 border-t border-gray-50 text-center">
            <p class="text-xs text-gray-400 italic">
                Akses terbatas. Hubungi Admin jika belum punya akun.
            </p>
        </div>
    </div>
</div>
@endsection