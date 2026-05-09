@extends('layouts.app')

@section('content')
<div class="max-w-2xl mx-auto px-2">
    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="text-3xl font-black text-gray-800 tracking-tighter italic">Edit Unit.</h1>
            <p class="text-gray-500 font-medium text-sm">Update detail untuk Kamar {{ $room->room_number }}</p>
        </div>
        <a href="{{ route('rooms.index') }}" class="text-gray-400 hover:text-gray-600 font-bold text-xs uppercase tracking-widest transition">
            ← Kembali
        </a>
    </div>

    <div class="bg-white p-6 md:p-10 rounded-[2.5rem] shadow-xl shadow-blue-100/50 border border-gray-100">
        <form action="{{ route('rooms.update', $room->id) }}" method="POST">
            @csrf 
            @method('PUT')
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                <div class="space-y-2">
                    <label class="block text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] ml-1">Nomor Kamar</label>
                    <input type="text" name="room_number" 
                           class="w-full p-4 bg-gray-50 border rounded-2xl outline-none transition font-bold
                           {{ $errors->has('room_number') ? 'border-red-500 ring-4 ring-red-100' : 'border-gray-100 focus:ring-4 focus:ring-blue-100' }}" 
                           value="{{ old('room_number', $room->room_number) }}" required>
                    @error('room_number')
                        <p class="text-[10px] text-red-500 font-bold mt-1 uppercase italic ml-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="space-y-2">
                    <label class="block text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] ml-1">Tipe Unit</label>
                    <select name="type" class="w-full p-4 bg-gray-50 border border-gray-100 rounded-2xl focus:ring-4 focus:ring-blue-100 outline-none transition font-bold text-gray-600 appearance-none" required>
                        <option value="Reguler" {{ old('type', $room->type) == 'Reguler' ? 'selected' : '' }}>Reguler</option>
                        <option value="VIP" {{ old('type', $room->type) == 'VIP' ? 'selected' : '' }}>VIP Class</option>
                        <option value="Suite" {{ old('type', $room->type) == 'Suite' ? 'selected' : '' }}>Suite Class</option>
                    </select>
                </div>

                <div class="space-y-2">
                    <label class="block text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] ml-1">Harga Sewa (Rp)</label>
                    <div class="relative">
                        <span class="absolute left-4 top-4 font-bold text-gray-300">Rp</span>
                        <input type="number" name="price" 
                               class="w-full p-4 pl-12 bg-gray-50 border border-gray-100 rounded-2xl focus:ring-4 focus:ring-blue-100 outline-none transition font-bold text-green-600" 
                               value="{{ old('price', $room->price) }}" required>
                    </div>
                </div>

                <div class="space-y-2">
                    <label class="block text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] ml-1">Status Ketersediaan</label>
                    <select name="status" class="w-full p-4 bg-gray-50 border border-gray-100 rounded-2xl focus:ring-4 focus:ring-blue-100 outline-none transition font-black uppercase text-xs tracking-widest
                        {{ $room->status == 'empty' ? 'text-green-600' : 'text-red-600' }}" required>
                        <option value="empty" {{ old('status', $room->status) == 'empty' ? 'selected' : '' }}>🟢 Kosong</option>
                        <option value="occupied" {{ old('status', $room->status) == 'occupied' ? 'selected' : '' }}>🔴 Terisi</option>
                    </select>
                </div>
            </div>

            <div class="flex flex-col md:flex-row gap-4">
                <button type="submit" class="flex-1 bg-blue-600 text-white py-5 rounded-3xl font-black text-xs uppercase tracking-[0.2em] shadow-xl shadow-blue-200 hover:bg-blue-700 transition duration-300 active:scale-95">
                    Simpan Perubahan
                </button>
                <a href="{{ route('rooms.index') }}" class="flex-1 bg-gray-100 text-gray-500 py-5 rounded-3xl font-black text-xs uppercase tracking-[0.2em] text-center hover:bg-gray-200 transition duration-300">
                    Batalkan
                </a>
            </div>
        </form>
    </div>

    <div class="mt-8 p-6 bg-orange-50 rounded-3xl border border-orange-100">
        <div class="flex items-start">
            <svg class="w-5 h-5 text-orange-400 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
            </svg>
            <div class="ml-3">
                <p class="text-[11px] font-bold text-orange-800 uppercase tracking-widest mb-1">Perhatian Admin</p>
                <p class="text-xs text-orange-700 leading-relaxed font-medium">
                    Mengubah status kamar secara manual menjadi **Kosong** tidak akan menghapus data penghuni yang sebelumnya terdaftar. Pastikan data sudah sinkron sebelum menyimpan.
                </p>
            </div>
        </div>
    </div>
</div>
@endsection