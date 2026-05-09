@extends('layouts.app')

@section('content')
<div class="max-w-xl mx-auto bg-white p-6 rounded-lg shadow border">
    <h2 class="text-xl font-bold mb-6">Pendaftaran Penghuni Baru</h2>

    <form action="{{ route('tenants.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="mb-4">
            <label class="block mb-1 font-bold">Nama Lengkap</label>
            <input type="text" name="name" class="w-full p-2 border rounded" required>
        </div>
        <div class="mb-4">
            <label class="block mb-1 font-bold">Email (untuk login)</label>
            <input type="email" name="email" class="w-full p-2 border rounded" required>
        </div>
        <div class="mb-4">
            <label class="block mb-1 font-bold">Nomor HP</label>
            <input type="text" name="phone" class="w-full p-2 border rounded" required>
        </div>
        <div class="mb-4">
            <label class="block mb-1 font-bold">Pilih Kamar (Tersedia)</label>
            <select name="room_id" class="w-full p-2 border rounded" required>
                @foreach($rooms as $r)
                    <option value="{{ $r->id }}">{{ $r->room_number }} - Rp {{ number_format($r->price) }}</option>
                @endforeach
            </select>
        </div>
        <div class="mb-6">
            <label class="block mb-1 font-bold">Foto KTP</label>
            <input type="file" name="id_card_photo" class="w-full" required>
        </div>
        <button type="submit" class="w-full bg-blue-600 text-white py-2 rounded font-bold">Daftarkan Sekarang</button>
    </form>
</div>
@endsection