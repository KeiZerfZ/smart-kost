@extends('layouts.app')

@section('content')
<div class="max-w-6xl mx-auto px-2">
    <div class="mb-8">
        <h1 class="text-3xl font-black text-gray-800 dark:text-gray-100 tracking-tighter italic">Permintaan Verifikasi.</h1>
        <p class="text-gray-500 dark:text-gray-400 font-medium text-sm">Daftar calon penghuni yang mengajukan pendaftaran mandiri.</p>
    </div>

    <div class="bg-white dark:bg-slate-900 rounded-[2.5rem] shadow-sm border border-gray-100 dark:border-slate-800 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse min-w-[800px]">
                <thead>
                    <tr class="bg-gray-50/50 dark:bg-slate-800/50 border-b border-gray-100 dark:border-slate-800">
                        <th class="p-6 text-[10px] font-black text-gray-400 dark:text-gray-500 uppercase tracking-widest">Calon Penghuni</th>
                        <th class="p-6 text-[10px] font-black text-gray-400 dark:text-gray-500 uppercase tracking-widest">Kamar Pilihan</th>
                        <th class="p-6 text-[10px] font-black text-gray-400 dark:text-gray-500 uppercase tracking-widest">Kontak & Telegram ID</th>
                        <th class="p-6 text-[10px] font-black text-gray-400 dark:text-gray-500 uppercase tracking-widest text-center">Identitas KTP</th>
                        <th class="p-6 text-[10px] font-black text-gray-400 dark:text-gray-500 uppercase tracking-widest text-center">Tindakan</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50 dark:divide-slate-800">
                    @foreach($pendingTenants as $pt)
                    <tr class="hover:bg-gray-50/50 dark:hover:bg-slate-800/30 transition">
                        <td class="p-6">
                            <h4 class="font-bold text-gray-800 dark:text-gray-200 text-sm">{{ $pt->user->name }}</h4>
                            <p class="text-xs text-gray-400">{{ $pt->user->email }}</p>
                        </td>
                        <td class="p-6">
                            <span class="bg-blue-600 text-white px-2.5 py-1 rounded-lg font-black text-xs italic">Kamar {{ $pt->room->room_number }}</span>
                        </td>
                        <td class="p-6 text-xs font-bold text-gray-600 dark:text-gray-300 space-y-1">
                            <p>📞 {{ $pt->phone }}</p>
                            <p class="text-blue-500">🔹 ID: {{ $pt->telegram_chat_id }}</p>
                        </td>
                        <td class="p-6 text-center">
                            @php $filename = str_replace('ktp_photos/', '', $pt->id_card_photo); @endphp
                            <a href="{{ route('tenants.ktp.show', $filename) }}" target="_blank" class="inline-flex bg-gray-100 dark:bg-slate-800 text-blue-500 px-4 py-2 rounded-xl text-[10px] font-black uppercase tracking-widest">Lihat KTP</a>
                        </td>
                        <td class="p-6 text-center">
                            <div class="flex justify-center items-center gap-2">
                                <form action="{{ route('tenants.approve', $pt->id) }}" method="POST" onsubmit="return confirm('Setujui pendaftaran penghuni ini?')">
                                    @csrf @method('PATCH')
                                    <button type="submit" class="bg-green-500 text-white px-4 py-2 rounded-xl text-[10px] font-black uppercase tracking-widest">Setujui</button>
                                </form>
                                <form action="{{ route('tenants.reject', $pt->id) }}" method="POST" onsubmit="return confirm('Tolak dan hapus pengajuan pendaftaran ini?')">
                                    @csrf @method('PATCH')
                                    <button type="submit" class="bg-red-500 text-white px-4 py-2 rounded-xl text-[10px] font-black uppercase tracking-widest">Tolak</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @if($pendingTenants->isEmpty())
            <p class="text-center text-gray-400 dark:text-gray-500 py-12 italic text-sm font-medium">Tidak ada permintaan verifikasi pendaftaran baru.</p>
        @endif
    </div>
</div>
@endsection