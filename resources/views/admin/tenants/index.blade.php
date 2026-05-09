@extends('layouts.app')

@section('content')
<div class="bg-white p-6 rounded-lg shadow-sm border">
    <div class="flex justify-between mb-6">
        <h2 class="text-2xl font-bold">Daftar Penghuni</h2>
        <a href="{{ route('tenants.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded">Tambah Penghuni</a>
    </div>

    <table class="w-full text-left border-collapse">
        <thead>
            <tr class="bg-gray-100">
                <th class="p-3">Nama</th>
                <th class="p-3">Kamar</th>
                <th class="p-3">HP</th>
                <th class="p-3">KTP</th>
                <th class="p-3">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($tenants as $t)
            <tr class="border-b">
                <td class="p-3">{{ $t->user->name }}</td>
                <td class="p-3 font-bold text-blue-600">{{ $t->room->room_number }}</td>
                <td class="p-3">{{ $t->phone }}</td>
                <td class="p-3">
                    <a href="{{ asset('storage/'.$t->id_card_photo) }}" target="_blank" class="text-xs text-blue-500 underline">Lihat KTP</a>
                </td>
                <td class="p-3">
                    <form action="{{ route('tenants.destroy', $t->id) }}" method="POST" onsubmit="return confirm('Proses checkout penghuni?')">
                        @csrf @method('DELETE')
                        <button type="submit" class="text-red-500">Checkout</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection