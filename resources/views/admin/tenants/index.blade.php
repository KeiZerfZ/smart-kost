@extends('layouts.app')

@section('content')
<div class="max-w-6xl mx-auto px-2">
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-8">
        <div>
            <h1 class="text-3xl font-black text-gray-800 dark:text-gray-100 tracking-tighter italic transition-colors">
                Data Penghuni.
            </h1>
            <p class="text-gray-500 dark:text-gray-400 font-medium text-sm transition-colors">
                Daftar penghuni resmi SmartKost dengan integrasi notifikasi Telegram Bot.
            </p>
        </div>

        <a href="{{ route('tenants.create') }}"
            class="bg-blue-600 text-white px-6 py-3 rounded-2xl font-black text-xs uppercase tracking-widest shadow-xl shadow-blue-100 dark:shadow-none hover:bg-blue-700 transition active:scale-95 text-center">
            + Tambah Penghuni
        </a>
    </div>

    <div class="bg-white dark:bg-slate-900 rounded-[2.5rem] shadow-sm border border-gray-100 dark:border-slate-800 overflow-hidden transition-colors">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse min-w-[700px]">
                <thead>
                    <tr class="bg-gray-50/50 dark:bg-slate-800/50 border-b border-gray-50 dark:border-slate-800">
                        <th class="p-6 text-[10px] font-black text-gray-400 dark:text-gray-500 uppercase tracking-widest">Penghuni</th>
                        <th class="p-6 text-[10px] font-black text-gray-400 dark:text-gray-500 uppercase tracking-widest">Kamar</th>
                        <th class="p-6 text-[10px] font-black text-gray-400 dark:text-gray-500 uppercase tracking-widest">Kontak & Telegram Chat ID</th>
                        <th class="p-6 text-[10px] font-black text-gray-400 dark:text-gray-500 uppercase tracking-widest text-center">Identitas</th>
                        <th class="p-6 text-[10px] font-black text-gray-400 dark:text-gray-500 uppercase tracking-widest text-center">Manajemen</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-gray-50 dark:divide-slate-800">
                    @foreach($tenants as $t)
                    <tr class="hover:bg-gray-50/50 dark:hover:bg-slate-800/30 transition group">
                        <td class="p-6">
                            <div class="flex items-center space-x-4">
                                <img src="{{ $t->user->avatar_url ?? 'https://ui-avatars.com/api/?name='.urlencode($t->user->name) }}" 
                                     class="w-12 h-12 rounded-2xl object-cover border-2 border-gray-50 dark:border-slate-800 shadow-sm transition-transform group-hover:scale-110" 
                                     alt="{{ $t->user->name }}">
                                <div>
                                    <h4 class="font-bold text-gray-800 dark:text-gray-200 tracking-tight">{{ $t->user->name }}</h4>
                                    <div class="flex items-center space-x-2">
                                        <span class="w-2 h-2 bg-green-500 rounded-full animate-pulse"></span>
                                        <p class="text-[9px] text-green-600 dark:text-green-400 font-black uppercase tracking-widest">Aktif</p>
                                    </div>
                                </div>
                            </div>
                        </td>

                        <td class="p-6">
                            <div class="inline-flex flex-col">
                                <span class="bg-blue-600 text-white px-3 py-1 rounded-lg font-black text-xs tracking-tighter italic">
                                    Kamar {{ $t->room->room_number }}
                                </span>
                                <span class="text-[9px] text-gray-400 dark:text-gray-500 font-black uppercase mt-1 ml-1 tracking-widest">{{ $t->room->type }}</span>
                            </div>
                        </td>

                        <td class="p-6">
                            <div class="flex flex-col space-y-2">
                                <span class="text-xs font-bold text-gray-600 dark:text-gray-300 flex items-center">
                                    <svg class="w-3 h-3 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg>
                                    {{ $t->phone }}
                                </span>
                                <span class="text-xs font-black {{ $t->telegram_chat_id ? 'text-blue-500' : 'text-red-400 italic' }} flex items-center">
                                    <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 24 24"><path d="M12 0C5.373 0 0 5.373 0 12s5.373 12 12 12 12-5.373 12-12S18.627 0 12 0zm5.894 8.221l-1.97 9.28c-.145.658-.537.818-1.084.508l-3-2.21-1.446 1.394c-.14.14-.26.26-.54.26l.196-2.82 5.128-4.63c.223-.198-.048-.307-.34-.114l-6.335 3.99-2.73-.85c-.595-.185-.606-.595.124-.88l10.666-4.112c.49-.185.92.11.73.984z"/></svg>
                                    {{ $t->telegram_chat_id ?? 'Belum Terhubung' }}
                                </span>
                            </div>
                        </td>

                        <td class="p-6 text-center">
                            @php $ktpFilename = str_replace('ktp_photos/', '', $t->id_card_photo); @endphp
                            <a href="{{ route('tenants.ktp.show', $ktpFilename) }}" target="_blank"
                                class="inline-flex items-center space-x-2 bg-gray-50 dark:bg-slate-800 hover:bg-blue-50 dark:hover:bg-blue-900/20 text-blue-400 dark:text-blue-300 px-4 py-2 rounded-xl text-[10px] font-black uppercase tracking-widest transition border border-transparent hover:border-blue-100">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                <span>Lihat KTP</span>
                            </a>
                        </td>

                        <td class="p-6 text-center">
                            <form action="{{ route('tenants.destroy', $t->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin memproses check-out untuk penghuni ini?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="bg-red-50 dark:bg-red-900/20 text-red-500 dark:text-red-400 hover:bg-red-500 hover:text-white px-4 py-2 rounded-xl text-[10px] font-black uppercase tracking-widest transition duration-300 shadow-sm border border-red-100 dark:border-red-800/40">
                                    Check-Out
                                </button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection