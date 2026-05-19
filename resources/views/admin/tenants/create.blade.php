@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto px-2">
    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="text-3xl font-black text-gray-800 dark:text-gray-100 tracking-tighter italic transition-colors">
                Pendaftaran Manual.
            </h1>
            <p class="text-gray-500 dark:text-gray-400 font-medium text-sm transition-colors">
                Mendaftarkan penghuni baru secara langsung dengan integrasi Telegram Bot.
            </p>
        </div>

        <a href="{{ route('tenants.index') }}"
            class="text-gray-400 dark:text-gray-500 hover:text-gray-600 dark:hover:text-gray-300 font-bold text-xs uppercase tracking-widest transition">
            ← Kembali
        </a>
    </div>

    <div class="bg-white dark:bg-slate-900 p-6 md:p-10 rounded-[2.5rem] shadow-xl shadow-blue-100/50 dark:shadow-none border border-gray-100 dark:border-slate-800 transition-colors">
        <form action="{{ route('tenants.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">

                <div class="space-y-2">
                    <label class="block text-[10px] font-black text-gray-400 dark:text-gray-500 uppercase tracking-[0.2em] ml-1">
                        Nama Lengkap
                    </label>
                    <input type="text" name="name" placeholder="Nama lengkap sesuai KTP"
                        class="w-full p-4 bg-gray-50 dark:bg-slate-800 border border-gray-100 dark:border-slate-700 rounded-2xl focus:ring-4 focus:ring-blue-100 dark:focus:ring-blue-900/30 outline-none transition font-semibold text-gray-800 dark:text-gray-100 placeholder:text-gray-400 dark:placeholder:text-gray-500"
                        value="{{ old('name') }}" required>
                </div>

                <div class="space-y-2">
                    <label class="block text-[10px] font-black text-gray-400 dark:text-gray-500 uppercase tracking-[0.2em] ml-1">
                        Alamat Email
                    </label>
                    <input type="email" name="email" placeholder="nama@email.com"
                        class="w-full p-4 bg-gray-50 dark:bg-slate-800 border border-gray-100 dark:border-slate-700 rounded-2xl focus:ring-4 focus:ring-blue-100 dark:focus:ring-blue-900/30 outline-none transition font-semibold text-gray-800 dark:text-gray-100 placeholder:text-gray-400 dark:placeholder:text-gray-500"
                        value="{{ old('email') }}" required>
                </div>

                <div class="space-y-2">
                    <label class="block text-[10px] font-black text-gray-400 dark:text-gray-500 uppercase tracking-[0.2em] ml-1">
                        Nomor Kontak (WhatsApp)
                    </label>
                    <input type="text" name="phone" placeholder="0812XXXXXXXX"
                        class="w-full p-4 bg-gray-50 dark:bg-slate-800 border border-gray-100 dark:border-slate-700 rounded-2xl focus:ring-4 focus:ring-blue-100 dark:focus:ring-blue-900/30 outline-none transition font-semibold text-gray-800 dark:text-gray-100 placeholder:text-gray-400 dark:placeholder:text-gray-500"
                        value="{{ old('phone') }}" required>
                </div>

                <div class="space-y-2">
                    <label class="block text-[10px] font-black text-blue-500 dark:text-blue-400 uppercase tracking-[0.2em] ml-1">
                        Telegram Chat ID
                    </label>
                    <input type="text" name="telegram_chat_id" placeholder="Contoh: 891227965"
                        class="w-full p-4 bg-blue-50/50 dark:bg-blue-900/10 border border-blue-100 dark:border-blue-800/40 rounded-2xl focus:ring-4 focus:ring-blue-100 dark:focus:ring-blue-900/30 outline-none transition font-bold text-blue-600 dark:text-blue-300 placeholder:text-blue-300 dark:placeholder:text-blue-700"
                        value="{{ old('telegram_chat_id') }}">
                    <p class="text-[9px] text-gray-400 dark:text-gray-500 italic ml-1 tracking-tight">
                        * Dapatkan nomor ID unik dengan mengirimkan pesan ke <b>@userinfobot</b> di aplikasi Telegram.
                    </p>
                </div>

                <div class="md:col-span-2 space-y-2">
                    <label class="block text-[10px] font-black text-gray-400 dark:text-gray-500 uppercase tracking-[0.2em] ml-1">
                        Pilih Kamar Kos Kosong
                    </label>
                    <select name="room_id"
                        class="w-full p-4 bg-gray-50 dark:bg-slate-800 border border-gray-100 dark:border-slate-700 rounded-2xl focus:ring-4 focus:ring-blue-100 dark:focus:ring-blue-900/30 outline-none transition font-bold text-blue-600 dark:text-blue-400 appearance-none"
                        required>
                        @if($rooms->isEmpty())
                            <option value="" disabled selected>⚠️ Tidak ada kamar dengan status kosong saat ini.</option>
                        @else
                            <option value="" disabled selected>-- Pilih Nomor Kamar --</option>
                            @foreach($rooms as $r)
                                <option value="{{ $r->id }}">
                                    Kamar {{ $r->room_number }} ({{ $r->type }}) - Rp {{ number_format($r->price, 0, ',', '.') }}
                                </option>
                            @endforeach
                        @endif
                    </select>
                </div>
            </div>

            <div class="mb-10 p-6 bg-blue-50/50 dark:bg-blue-900/10 border-2 border-dashed border-blue-100 dark:border-blue-800/40 rounded-[2rem] transition-colors">
                <label class="block text-[10px] font-black text-blue-400 dark:text-blue-300 uppercase tracking-[0.2em] mb-4 text-center">
                    Dokumen Identitas Resmi (Foto KTP)
                </label>
                <input type="file" name="id_card_photo"
                    class="w-full text-sm text-gray-500 dark:text-gray-400 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-xs file:font-black file:bg-blue-600 file:text-white hover:file:bg-blue-700 cursor-pointer"
                    required>
            </div>

            <button type="submit"
                class="w-full bg-blue-600 text-white py-5 rounded-3xl font-black text-xs uppercase tracking-[0.2em] shadow-xl shadow-blue-200 dark:shadow-none hover:bg-blue-700 hover:-translate-y-1 transition duration-300 active:scale-95">
                Proses Pendaftaran Penghuni
            </button>
        </form>
    </div>
</div>
@endsection