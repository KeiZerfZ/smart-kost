@extends('layouts.app')

@section('content')
<div class="max-w-lg mx-auto bg-white p-6 rounded-lg shadow border">
    <h2 class="text-xl font-bold mb-6">Edit Data Kamar: {{ $room->room_number }}</h2>

    <form action="{{ route('rooms.update', $room->id) }}" method="POST">
        @csrf @method('PUT')
        
        <div class="mb-4">
            <label class="block text-sm font-bold mb-1">Nomor Kamar</label>
            <input type="text" name="room_number" value="{{ $room->room_number }}" class="w-full p-2 border rounded" required>
        </div>

        <div class="mb-4">
            <label class="block text-sm font-bold mb-1">Tipe</label>
            <select name="type" class="w-full p-2 border rounded" required>
                <option value="Reguler" {{ $room->type == 'Reguler' ? 'selected' : '' }}>Reguler</option>
                <option value="VIP" {{ $room->type == 'VIP' ? 'selected' : '' }}>VIP</option>
            </select>
        </div>

        <div class="mb-4">
            <label class="block text-sm font-bold mb-1">Harga per Bulan</label>
            <input type="number" name="price" value="{{ $room->price }}" class="w-full p-2 border rounded" required>
        </div>

        <div class="mb-6">
            <label class="block text-sm font-bold mb-1">Status</label>
            <select name="status" class="w-full p-2 border rounded" required>
                <option value="empty" {{ $room->status == 'empty' ? 'selected' : '' }}>Kosong</option>
                <option value="occupied" {{ $room->status == 'occupied' ? 'selected' : '' }}>Terisi</option>
            </select>
        </div>

        <div class="flex gap-2">
            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">Update Kamar</button>
            <a href="{{ route('rooms.index') }}" class="bg-gray-500 text-white px-4 py-2 rounded">Batal</a>
        </div>
    </form>
</div>
@endsection