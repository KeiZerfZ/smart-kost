@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="mb-8 flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-gray-800 dark:text-gray-100 transition-colors">
                Profil Saya
            </h1>
            <p class="text-gray-500 dark:text-gray-400 transition-colors text-sm">
                Kelola informasi akun, foto profil, dan keamanan lu.
            </p>
        </div>
        <a href="{{ route('home') }}" class="text-sm font-bold text-blue-600 dark:text-blue-400 hover:underline transition-colors">
            ← Balik ke Dashboard
        </a>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-white dark:bg-slate-900 p-6 rounded-3xl shadow-sm border border-gray-100 dark:border-slate-800 transition-colors">
            <div class="text-center mb-6">
                <div class="relative inline-block">
                    <img src="{{ auth()->user()->avatar_url }}" 
                         class="w-24 h-24 rounded-[2rem] object-cover mx-auto mb-4 border-4 border-blue-50 dark:border-slate-800 shadow-lg transition-transform hover:scale-105 duration-300"
                         alt="Avatar {{ auth()->user()->name }}">
                </div>

                <h3 class="font-bold text-xl text-gray-800 dark:text-gray-100 transition-colors">
                    {{ auth()->user()->name }}
                </h3>
                <p class="text-xs text-blue-500 dark:text-blue-400 font-black uppercase tracking-widest mt-1 transition-colors italic">
                    {{ auth()->user()->role }}
                </p>
            </div>

            <div class="space-y-4 pt-4 border-t border-gray-50 dark:border-slate-800 transition-colors">
                <div>
                    <label class="text-[10px] font-black text-gray-400 dark:text-gray-500 uppercase tracking-widest">Email Terdaftar</label>
                    <p class="text-sm font-medium text-gray-700 dark:text-gray-300 transition-colors truncate">{{ auth()->user()->email }}</p>
                </div>
                <div>
                    <label class="text-[10px] font-black text-gray-400 dark:text-gray-500 uppercase tracking-widest">Bergabung Sejak</label>
                    <p class="text-sm font-medium text-gray-700 dark:text-gray-300 transition-colors">{{ auth()->user()->created_at->format('d M Y') }}</p>
                </div>
            </div>
        </div>

        <div class="md:col-span-2 space-y-6">
            <div class="bg-white dark:bg-slate-900 p-8 rounded-3xl shadow-sm border border-gray-100 dark:border-slate-800 transition-colors">
                <h3 class="text-lg font-bold text-gray-800 dark:text-gray-100 mb-6 flex items-center transition-colors uppercase tracking-tight">
                    Update Informasi Profil
                </h3>
                
                <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data" class="space-y-5">
                    @csrf
                    @method('PATCH')

                    <div>
                        <label class="block text-xs font-black text-gray-400 uppercase tracking-widest mb-2">Foto Profil (Avatar)</label>
                        <input type="file" name="avatar" 
                               class="block w-full text-xs text-gray-500 dark:text-gray-400
                                      file:mr-4 file:py-2 file:px-4
                                      file:rounded-xl file:border-0
                                      file:text-[10px] file:font-black file:uppercase file:tracking-widest
                                      file:bg-blue-50 file:text-blue-700 dark:file:bg-slate-800 dark:file:text-blue-400
                                      hover:file:bg-blue-100 transition">
                        <p class="mt-2 text-[10px] text-gray-400 italic">Format: JPG, PNG. Maksimal 1MB.</p>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-1">Nama Lengkap</label>
                            <input type="text" name="name" value="{{ auth()->user()->name }}"
                                   class="w-full p-4 bg-gray-50 dark:bg-slate-800 border border-gray-100 dark:border-slate-700 rounded-2xl focus:ring-2 focus:ring-blue-500 outline-none transition text-gray-800 dark:text-gray-100" required>
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-1">Alamat Email</label>
                            <input type="email" name="email" value="{{ auth()->user()->email }}"
                                   class="w-full p-4 bg-gray-50 dark:bg-slate-800 border border-gray-100 dark:border-slate-700 rounded-2xl focus:ring-2 focus:ring-blue-500 outline-none transition text-gray-800 dark:text-gray-100" required>
                        </div>
                    </div>

                    <div class="pt-2">
                        <button type="submit" class="w-full md:w-auto px-10 bg-blue-600 text-white font-black text-xs uppercase tracking-widest py-4 rounded-2xl shadow-lg shadow-blue-100 dark:shadow-none hover:bg-blue-700 transition active:scale-95">
                            Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>

            <div class="bg-white dark:bg-slate-900 p-8 rounded-3xl shadow-sm border border-gray-100 dark:border-slate-800 transition-colors">
                <h3 class="text-lg font-bold text-gray-800 dark:text-gray-100 mb-6 flex items-center transition-colors uppercase tracking-tight">
                    Ganti Password
                </h3>

                <form action="{{ route('profile.password') }}" method="POST" class="space-y-5">
                    @csrf
                    @method('PUT')

                    <div>
                        <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-1">Password Saat Ini</label>
                        <input type="password" name="current_password"
                               class="w-full p-4 bg-gray-50 dark:bg-slate-800 border border-gray-100 dark:border-slate-700 rounded-2xl focus:ring-2 focus:ring-blue-500 outline-none transition text-gray-800 dark:text-gray-100" required>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-1">Password Baru</label>
                            <input type="password" name="password"
                                   class="w-full p-4 bg-gray-50 dark:bg-slate-800 border border-gray-100 dark:border-slate-700 rounded-2xl focus:ring-2 focus:ring-blue-500 outline-none transition text-gray-800 dark:text-gray-100" required>
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-1">Konfirmasi</label>
                            <input type="password" name="password_confirmation"
                                   class="w-full p-4 bg-gray-50 dark:bg-slate-800 border border-gray-100 dark:border-slate-700 rounded-2xl focus:ring-2 focus:ring-blue-500 outline-none transition text-gray-800 dark:text-gray-100" required>
                        </div>
                    </div>

                    <div class="pt-2">
                        <button type="submit" class="w-full md:w-auto px-10 bg-slate-800 dark:bg-blue-600 text-white font-black text-xs uppercase tracking-widest py-4 rounded-2xl shadow-lg transition active:scale-95">
                            Update Password
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection