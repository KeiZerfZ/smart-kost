@extends('layouts.app')

@section('content')
<div class="bg-white p-6 rounded-lg shadow-sm border">
    <h2 class="text-2xl font-bold mb-6">Manajemen Kamar</h2>

    @if(session('success'))
        <div class="bg-green-100 text-green-700 p-3 mb-4 rounded">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="bg-red-100 text-red-700 p-3 mb-4 rounded">{{ session('error') }}</div>
    @endif

    <form action="{{ route('rooms.store') }}" method="POST" class="mb-8 grid grid-cols-1 md:grid-cols-4 gap-4 p-4 bg-gray-50 rounded">
        @csrf
        <input type="text" name="room_number" placeholder="No. Kamar (Contoh: A-01)" class="p-2 border rounded" required>
        <select name="type" class="p-2 border rounded" required>
            <option value="">Pilih Tipe</option>
            <option value="Reguler">Reguler</option>
            <option value="VIP">VIP</option>
        </select>
        <input type="number" name="price" placeholder="Harga per Bulan" class="p-2 border rounded" required>
        <button type="submit" class="bg-blue-600 text-white rounded p-2 hover:bg-blue-700">Tambah Kamar</button>
    </form>

    <table class="w-full text-left border-collapse">
        <thead>
            <tr class="border-b bg-gray-100 text-sm font-bold">
                <th class="p-3">No. Kamar</th>
                <th class="p-3">Tipe</th>
                <th class="p-3">Harga</th>
                <th class="p-3">Status</th>
                <th class="p-3 text-center">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($rooms as $room)
            <tr class="border-b hover:bg-gray-50 transition">
                <td class="p-3 font-semibold">{{ $room->room_number }}</td>
                <td class="p-3">{{ $room->type }}</td>
                <td class="p-3">Rp {{ number_format($room->price, 0, ',', '.') }}</td>
                <td class="p-3">
                    <span class="px-2 py-1 rounded-full text-xs font-bold 
                        {{ $room->status == 'empty' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                        {{ $room->status == 'empty' ? 'KOSONG' : 'TERISI' }}
                    </span>
                </td>
                <td class="p-3 flex justify-center gap-2">
                    <a href="{{ route('rooms.edit', $room->id) }}" class="text-blue-500 hover:underline">Edit</a>
                    <form action="{{ route('rooms.destroy', $room->id) }}" method="POST" onsubmit="return confirm('Hapus kamar ini?')">
                        @csrf @method('DELETE')
                        <button type="submit" class="text-red-500 hover:underline">Hapus</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection