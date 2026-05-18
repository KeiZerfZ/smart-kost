@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="mb-8 flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-gray-800 dark:text-gray-100 transition-colors">
                Profil Saya
            </h1>

            <p class="text-gray-500 dark:text-gray-400 transition-colors">
                Kelola informasi akun dan keamanan lu.
            </p>
        </div>

        <a href="{{ route('home') }}"
            class="text-sm font-bold text-blue-600 dark:text-blue-400 hover:underline transition-colors">
            ← Balik ke Dashboard
        </a>
    </div>

    @if(session('success'))
        <div
            class="mb-6 p-4 bg-green-100 dark:bg-green-900/30 border-l-4 border-green-500 dark:border-green-700 text-green-700 dark:text-green-300 rounded-xl shadow-sm transition-colors">
            {{ session('success') }}
        </div>
    @endif

    @if ($errors->any())
        <div
            class="mb-6 p-4 bg-red-50 dark:bg-red-900/20 border-l-4 border-red-500 dark:border-red-700 rounded-xl transition-colors">
            <p class="text-sm text-red-700 dark:text-red-300 font-bold mb-1">
                Waduh, ada masalah:
            </p>

            <ul class="text-xs text-red-600 dark:text-red-400 list-disc list-inside">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

        <div
            class="bg-white dark:bg-slate-900 p-6 rounded-3xl shadow-sm border border-gray-100 dark:border-slate-800 transition-colors">
            <div class="text-center mb-6">
                <div
                    class="w-20 h-20 bg-blue-100 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 rounded-full flex items-center justify-center mx-auto mb-4 text-3xl font-bold transition-colors">
                    {{ substr(auth()->user()->name, 0, 1) }}
                </div>

                <h3 class="font-bold text-xl text-gray-800 dark:text-gray-100 transition-colors">
                    {{ auth()->user()->name }}
                </h3>

                <p
                    class="text-xs text-blue-500 dark:text-blue-400 font-bold uppercase tracking-widest mt-1 transition-colors">
                    {{ auth()->user()->role }}
                </p>
            </div>

            <div
                class="space-y-4 pt-4 border-t border-gray-50 dark:border-slate-800 transition-colors">
                <div>
                    <label
                        class="text-[10px] font-black text-gray-400 dark:text-gray-500 uppercase">
                        Email Terdaftar
                    </label>

                    <p class="text-sm font-medium text-gray-700 dark:text-gray-300 transition-colors">
                        {{ auth()->user()->email }}
                    </p>
                </div>

                <div>
                    <label
                        class="text-[10px] font-black text-gray-400 dark:text-gray-500 uppercase">
                        Bergabung Sejak
                    </label>

                    <p class="text-sm font-medium text-gray-700 dark:text-gray-300 transition-colors">
                        {{ auth()->user()->created_at->format('d M Y') }}
                    </p>
                </div>
            </div>
        </div>

        <div
            class="md:col-span-2 bg-white dark:bg-slate-900 p-8 rounded-3xl shadow-sm border border-gray-100 dark:border-slate-800 transition-colors">
            <h3
                class="text-lg font-bold text-gray-800 dark:text-gray-100 mb-6 flex items-center transition-colors">
                <svg class="w-5 h-5 mr-2 text-blue-600 dark:text-blue-400" fill="none"
                    stroke="currentColor" viewBox="0 0 24 24">
                    <path
                        d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"
                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    </path>
                </svg>

                Ganti Password
            </h3>

            <form action="{{ route('profile.password') }}" method="POST" class="space-y-5">
                @csrf
                @method('PUT')

                <div>
                    <label
                        class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-1 transition-colors">
                        Password Saat Ini
                    </label>

                    <input type="password" name="current_password"
                        class="w-full p-4 bg-gray-50 dark:bg-slate-800 border border-gray-100 dark:border-slate-700 rounded-2xl focus:ring-2 focus:ring-blue-500 outline-none transition text-gray-800 dark:text-gray-100"
                        placeholder="Masukkan password sekarang" required>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                    <div>
                        <label
                            class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-1 transition-colors">
                            Password Baru
                        </label>

                        <input type="password" name="password"
                            class="w-full p-4 bg-gray-50 dark:bg-slate-800 border border-gray-100 dark:border-slate-700 rounded-2xl focus:ring-2 focus:ring-blue-500 outline-none transition text-gray-800 dark:text-gray-100"
                            placeholder="Minimal 8 karakter" required>
                    </div>

                    <div>
                        <label
                            class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-1 transition-colors">
                            Konfirmasi
                        </label>

                        <input type="password" name="password_confirmation"
                            class="w-full p-4 bg-gray-50 dark:bg-slate-800 border border-gray-100 dark:border-slate-700 rounded-2xl focus:ring-2 focus:ring-blue-500 outline-none transition text-gray-800 dark:text-gray-100"
                            placeholder="Ulangi password baru" required>
                    </div>
                </div>

                <div class="pt-4">
                    <button type="submit"
                        class="w-full md:w-auto px-10 bg-blue-600 text-white font-bold py-4 rounded-2xl shadow-lg shadow-blue-100 dark:shadow-none hover:bg-blue-700 transition">
                        Simpan Password Baru
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection