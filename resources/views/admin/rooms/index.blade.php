@extends('layouts.app')

@section('content')
    <div class="max-w-6xl mx-auto px-2">
        <div class="mb-8">
            <h1
                class="text-3xl font-black text-gray-800 dark:text-gray-100 tracking-tighter italic transition-colors">
                Manajemen Kamar.
            </h1>

            <p class="text-gray-500 dark:text-gray-400 font-medium transition-colors">
                Tambah, edit, atau hapus unit kamar kost lu.
            </p>
        </div>

        <div
            class="bg-white dark:bg-slate-900 p-6 md:p-8 rounded-[2.5rem] shadow-sm border border-gray-100 dark:border-slate-800 mb-10 transition-colors">
            <h3 class="text-xs font-black text-blue-400 uppercase tracking-[0.2em] mb-6">
                Input Unit Baru
            </h3>

            <form action="{{ route('rooms.store') }}" method="POST">
                @csrf

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                    <input type="text" name="room_number" placeholder="No. Kamar (A-01)"
                        class="p-4 bg-gray-50 dark:bg-slate-800 border border-gray-100 dark:border-slate-700 rounded-2xl focus:ring-4 focus:ring-blue-100 dark:focus:ring-blue-900/30 outline-none transition font-bold text-gray-800 dark:text-gray-100 placeholder:text-gray-400 dark:placeholder:text-gray-500"
                        required>

                    <select name="type"
                        class="p-4 bg-gray-50 dark:bg-slate-800 border border-gray-100 dark:border-slate-700 rounded-2xl focus:ring-4 focus:ring-blue-100 dark:focus:ring-blue-900/30 outline-none transition font-bold text-gray-500 dark:text-gray-300"
                        required>
                        <option value="">Pilih Tipe</option>
                        <option value="Reguler">Reguler</option>
                        <option value="VIP">VIP</option>
                        <option value="Suite">Suite</option>
                    </select>

                    <div class="relative">
                        <span class="absolute left-4 top-4 font-bold text-gray-400 dark:text-gray-500">
                            Rp
                        </span>

                        <input type="number" name="price" placeholder="Harga"
                            class="w-full p-4 pl-12 bg-gray-50 dark:bg-slate-800 border border-gray-100 dark:border-slate-700 rounded-2xl focus:ring-4 focus:ring-blue-100 dark:focus:ring-blue-900/30 outline-none transition font-bold text-gray-800 dark:text-gray-100 placeholder:text-gray-400 dark:placeholder:text-gray-500"
                            required>
                    </div>

                    <button type="submit"
                        class="bg-blue-600 text-white rounded-2xl font-black text-xs uppercase tracking-widest hover:bg-blue-700 shadow-lg shadow-blue-100 dark:shadow-none transition active:scale-95">
                        Tambah Kamar
                    </button>
                </div>
            </form>
        </div>

        <div
            class="bg-white dark:bg-slate-900 rounded-[2.5rem] shadow-sm border border-gray-100 dark:border-slate-800 overflow-hidden transition-colors">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse min-w-[600px]">
                    <thead>
                        <tr
                            class="bg-gray-50/50 dark:bg-slate-800/50 border-b border-gray-50 dark:border-slate-800">
                            <th
                                class="p-6 text-[10px] font-black text-gray-400 dark:text-gray-500 uppercase tracking-widest">
                                No. Kamar
                            </th>

                            <th
                                class="p-6 text-[10px] font-black text-gray-400 dark:text-gray-500 uppercase tracking-widest">
                                Tipe
                            </th>

                            <th
                                class="p-6 text-[10px] font-black text-gray-400 dark:text-gray-500 uppercase tracking-widest">
                                Harga Sewa
                            </th>

                            <th
                                class="p-6 text-[10px] font-black text-gray-400 dark:text-gray-500 uppercase tracking-widest">
                                Status
                            </th>

                            <th
                                class="p-6 text-[10px] font-black text-gray-400 dark:text-gray-500 uppercase tracking-widest text-center">
                                Aksi
                            </th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-gray-50 dark:divide-slate-800">
                        @foreach($rooms as $room)
                            <tr class="hover:bg-gray-50/50 dark:hover:bg-slate-800/30 transition group">
                                <td class="p-6">
                                    <span
                                        class="bg-blue-50 dark:bg-blue-900/30 text-blue-700 dark:text-blue-400 px-3 py-1.5 rounded-lg font-black text-sm tracking-tight transition-colors">
                                        {{ $room->room_number }}
                                    </span>
                                </td>

                                <td class="p-6">
                                    <span class="font-bold text-gray-700 dark:text-gray-200 transition-colors">
                                        {{ $room->type }}
                                    </span>
                                </td>

                                <td class="p-6 font-black text-gray-800 dark:text-gray-100 transition-colors">
                                    Rp {{ number_format($room->price, 0, ',', '.') }}
                                </td>

                                <td class="p-6">
                                    <span
                                        class="px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-tighter transition-colors
                                        {{ $room->status == 'empty'
                                            ? 'bg-green-100 dark:bg-green-900/30 text-green-600 dark:text-green-400 border border-green-200 dark:border-green-800/40'
                                            : 'bg-red-100 dark:bg-red-900/20 text-red-600 dark:text-red-400 border border-red-200 dark:border-red-800/40' }}">
                                        {{ $room->status == 'empty' ? 'Kosong' : 'Terisi' }}
                                    </span>
                                </td>

                                <td class="p-6">
                                    <div class="flex justify-center items-center gap-2">
                                        <a href="{{ route('rooms.edit', $room->id) }}"
                                            class="inline-flex items-center gap-1.5 bg-blue-50 dark:bg-blue-900/20 text-blue-600 dark:text-blue-400 hover:bg-blue-100 dark:hover:bg-blue-900/40 px-3 py-2 rounded-xl text-[10px] font-black uppercase tracking-widest transition">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    stroke-width="2"
                                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                                </path>
                                            </svg>

                                            Edit
                                        </a>

                                        <form action="{{ route('rooms.destroy', $room->id) }}" method="POST"
                                            onsubmit="return confirm('Hapus kamar ini secara permanen?')">
                                            @csrf
                                            @method('DELETE')

                                            <button type="submit"
                                                class="inline-flex items-center gap-1.5 bg-red-50 dark:bg-red-900/20 text-red-600 dark:text-red-400 hover:bg-red-100 dark:hover:bg-red-900/40 px-3 py-2 rounded-xl text-[10px] font-black uppercase tracking-widest transition">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6M1 7h22M9 7V4a1 1 0 011-1h4a1 1 0 011 1v3">
                                                    </path>
                                                </svg>

                                                Hapus
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            @if($rooms->isEmpty())
                <div class="p-20 text-center">
                    <p class="text-gray-400 dark:text-gray-500 font-medium italic">
                        Belum ada data kamar. Silakan tambah unit di atas.
                    </p>
                </div>
            @endif
        </div>
    </div>
@endsection