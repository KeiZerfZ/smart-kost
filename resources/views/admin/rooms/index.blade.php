@extends('layouts.app')

@section('content')
<div class="grid grid-cols-1 md:grid-cols-3 gap-6">
    <div class="bg-white p-6 rounded-lg shadow-md">
        <h2 class="text-xl font-bold mb-4">Tambah Kamar Baru</h2>
        <form action="{{ route('rooms.store') }}" method="POST">
            @csrf
            <div class="mb-4">
                <label class="block text-gray-700">Nomor Kamar</label>
                <input type="text" name="room_number" class="w-full border rounded p-2" placeholder="Contoh: A-01" required>
            </div>
            <div class="mb-4">
                <label class="block text-gray-700">Harga Sewa (Rp)</label>
                <input type="number" name="price" class="w-full border rounded p-2" placeholder="1000000" required>
            </div>
            <button type="submit" class="w-full bg-blue-600 text-white py-2 rounded hover:bg-blue-700">Simpan Kamar</button>
        </form>
    </div>

    <div class="md:col-span-2 bg-white p-6 rounded-lg shadow-md">
        <h2 class="text-xl font-bold mb-4">Daftar Kamar</h2>
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="border-b bg-gray-50">
                    <th class="p-2">No. Kamar</th>
                    <th class="p-2">Harga</th>
                    <th class="p-2">Status</th>
                    <th class="p-2">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($rooms as $room)
                <tr class="border-b hover:bg-gray-50">
                    <td class="p-2">{{ $room->room_number }}</td>
                    <td class="p-2">Rp {{ number_format($room->price, 0, ',', '.') }}</td>
                    <td class="p-2">
                        <span class="px-2 py-1 rounded text-xs {{ $room->status == 'empty' ? 'bg-green-200 text-green-800' : 'bg-yellow-200 text-yellow-800' }}">
                            {{ ucfirst($room->status) }}
                        </span>
                    </td>
                    <td class="p-2">
                        <button class="text-blue-600 text-sm">Edit</button>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection