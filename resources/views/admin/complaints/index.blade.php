@extends('layouts.app')

@section('content')
<div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold text-gray-800">Daftar Keluhan Penghuni</h2>
        <span class="bg-orange-100 text-orange-600 px-4 py-1 rounded-full text-sm font-bold">
            {{ $complaints->where('status', 'pending')->count() }} Perlu Respon
        </span>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-left">
            <thead>
                <tr class="text-gray-400 text-sm border-b">
                    <th class="pb-4">Penghuni / Kamar</th>
                    <th class="pb-4">Keluhan</th>
                    <th class="pb-4">Status</th>
                    <th class="pb-4 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($complaints as $c)
                <tr class="border-b last:border-0 hover:bg-gray-50 transition">
                    <td class="py-4">
                        <div class="font-bold text-gray-800">{{ $c->tenant->user->name }}</div>
                        <div class="text-xs text-blue-600">Kamar {{ $c->tenant->room->room_number }}</div>
                    </td>
                    <td class="py-4">
                        <div class="font-semibold text-gray-700">{{ $c->title }}</div>
                        <div class="text-sm text-gray-500">{{ $c->description }}</div>
                        <div class="text-[10px] text-gray-400 mt-1 italic">{{ $c->created_at->format('d M Y, H:i') }}</div>
                    </td>
                    <td class="py-4">
                        <span class="px-3 py-1 rounded-full text-xs font-bold uppercase
                            {{ $c->status == 'pending' ? 'bg-orange-100 text-orange-600' : '' }}
                            {{ $c->status == 'process' ? 'bg-blue-100 text-blue-600' : '' }}
                            {{ $c->status == 'resolved' ? 'bg-green-100 text-green-600' : '' }}">
                            {{ $c->status }}
                        </span>
                    </td>
                    <td class="py-4 text-right">
                        <form action="{{ route('complaints.updateStatus', $c->id) }}" method="POST" class="inline-flex space-x-2">
                            @csrf @method('PATCH')
                            
                            @if($c->status == 'pending')
                                <button name="status" value="process" class="bg-blue-500 text-white px-3 py-1 rounded-lg text-xs font-bold hover:bg-blue-600 transition">
                                    Proses
                                </button>
                            @endif

                            @if($c->status == 'process' || $c->status == 'pending')
                                <button name="status" value="resolved" class="bg-green-500 text-white px-3 py-1 rounded-lg text-xs font-bold hover:bg-green-700 transition">
                                    Selesai
                                </button>
                            @endif
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection