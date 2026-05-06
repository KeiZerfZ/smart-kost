@extends('layouts.app')

@section('content')
<div class="bg-white p-6 rounded-lg shadow-md max-w-2xl mx-auto">
    <h2 class="text-xl font-bold mb-6">Pendaftaran Penghuni Baru</h2>
    <form action="{{ route('tenants.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div class="mb-4">
                <label class="block text-gray-700">Nama Lengkap</label>
                <input type="text" name="name" class="w-full border rounded p-2" required>
            </div>
            <div class="mb-4">
                <label class="block text-gray-700">Email</label>
                <input type="email" name="email" class="w-full border rounded p-2" required>
            </div>
            <div class="mb-4">
                <label class="block text-gray-700">Pilih Kamar</label>
                <select name="room_id" class="w-full border rounded p-2" required>
                    @foreach($availableRooms as $room)
                        <option value="{{ $room->id }}">{{ $room->room_number }} - Rp {{ number_format($room->price) }}</option>
                    @endforeach
                </select>
            </div>
            <div class="mb-4">
                <label class="block text-gray-700">Tanggal Masuk</label>
                <input type="date" name="entry_date" class="w-full border rounded p-2" required>
            </div>
        </div>
        <div class="mb-4">
            <label class="block text-gray-700">Foto KTP</label>
            <input type="file" name="id_card_photo" class="w-full border p-1" accept="image/*">
        </div>
        <button type="submit" class="w-full bg-green-600 text-white py-2 rounded font-bold hover:bg-green-700">Daftarkan Penghuni</button>
    </form>
</div>
@endsection