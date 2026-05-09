@extends('layouts.app')

@section('content')
<div class="max-w-6xl mx-auto px-2">
    <div class="mb-8">
        <h1 class="text-3xl font-black text-gray-800 tracking-tighter italic">Manajemen Kamar.</h1>
        <p class="text-gray-500 font-medium">Tambah, edit, atau hapus unit kamar kost lu.</p>
    </div>

    <div class="bg-white p-6 md:p-8 rounded-[2.5rem] shadow-sm border border-gray-100 mb-10">
        <h3 class="text-xs font-black text-blue-400 uppercase tracking-[0.2em] mb-6">Input Unit Baru</h3>
        <form action="{{ route('rooms.store') }}" method="POST">
            @csrf
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                <input type="text" name="room_number" placeholder="No. Kamar (A-01)" 
                       class="p-4 bg-gray-50 border border-gray-100 rounded-2xl focus:ring-4 focus:ring-blue-100 outline-none transition font-bold" required>
                
                <select name="type" class="p-4 bg-gray-50 border border-gray-100 rounded-2xl focus:ring-4 focus:ring-blue-100 outline-none transition font-bold text-gray-500" required>
                    <option value="">Pilih Tipe</option>
                    <option value="Reguler">Reguler</option>
                    <option value="VIP">VIP</option>
                    <option value="Suite">Suite</option>
                </select>

                <div class="relative">
                    <span class="absolute left-4 top-4 font-bold text-gray-400">Rp</span>
                    <input type="number" name="price" placeholder="Harga" 
                           class="w-full p-4 pl-12 bg-gray-50 border border-gray-100 rounded-2xl focus:ring-4 focus:ring-blue-100 outline-none transition font-bold" required>
                </div>

                <button type="submit" class="bg-blue-600 text-white rounded-2xl font-black text-xs uppercase tracking-widest hover:bg-blue-700 shadow-lg shadow-blue-100 transition active:scale-95">
                    Tambah Kamar
                </button>
            </div>
        </form>
    </div>

    <div class="bg-white rounded-[2.5rem] shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse min-w-[600px]">
                <thead>
                    <tr class="bg-gray-50/50 border-b border-gray-50">
                        <th class="p-6 text-[10px] font-black text-gray-400 uppercase tracking-widest">No. Kamar</th>
                        <th class="p-6 text-[10px] font-black text-gray-400 uppercase tracking-widest">Tipe</th>
                        <th class="p-6 text-[10px] font-black text-gray-400 uppercase tracking-widest">Harga Sewa</th>
                        <th class="p-6 text-[10px] font-black text-gray-400 uppercase tracking-widest">Status</th>
                        <th class="p-6 text-[10px] font-black text-gray-400 uppercase tracking-widest text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @foreach($rooms as $room)
                    <tr class="hover:bg-gray-50/50 transition group">
                        <td class="p-6">
                            <span class="bg-blue-50 text-blue-700 px-3 py-1.5 rounded-lg font-black text-sm tracking-tight">
                                {{ $room->room_number }}
                            </span>
                        </td>
                        <td class="p-6">
                            <span class="font-bold text-gray-700">{{ $room->type }}</span>
                        </td>
                        <td class="p-6 font-black text-gray-800">
                            Rp {{ number_format($room->price, 0, ',', '.') }}
                        </td>
                        <td class="p-6">
                            <span class="px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-tighter
                                {{ $room->status == 'empty' ? 'bg-green-100 text-green-600 border border-green-200' : 'bg-red-100 text-red-600 border border-red-200' }}">
                                {{ $room->status == 'empty' ? 'Kosong' : 'Terisi' }}
                            </span>
                        </td>
                        <td class="p-6">
                            <div class="flex justify-center items-center space-x-4">
                                <a href="{{ route('rooms.edit', $room->id) }}" class="text-blue-500 hover:text-blue-700 font-black text-[10px] uppercase tracking-widest">
                                    Edit
                                </a>
                                
                                <form action="{{ route('rooms.destroy', $room->id) }}" method="POST" onsubmit="return confirm('Hapus kamar ini secara permanen?')">
                                    @csrf 
                                    @method('DELETE')
                                    <button type="submit" class="text-red-400 hover:text-red-600 font-black text-[10px] uppercase tracking-widest transition">
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
                <p class="text-gray-400 font-medium italic">Belum ada data kamar. Silakan tambah unit di atas.</p>
            </div>
        @endif
    </div>
</div>
@endsection