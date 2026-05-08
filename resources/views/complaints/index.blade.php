@extends('layouts.app')

@section('content')
<div class="bg-white p-6 rounded-lg shadow-md">
    <h2 class="text-2xl font-bold mb-6">Laporan & Keluhan Fasilitas</h2>

    @if(Auth::user()->role == 'tenant')
    <form action="{{ route('complaints.store') }}" method="POST" class="mb-8 p-4 bg-gray-50 rounded">
        @csrf
        <textarea name="description" class="w-full p-2 border rounded" placeholder="Deskripsikan kerusakan (misal: AC Kamar A-1 Bocor)" required></textarea>
        <button type="submit" class="mt-2 bg-red-500 text-white px-4 py-2 rounded">Kirim Laporan</button>
    </form>
    @endif

    <table class="w-full text-left">
        <thead>
            <tr class="border-b">
                <th class="p-2">Penghuni</th>
                <th class="p-2">Deskripsi Keluhan</th>
                <th class="p-2">Status</th>
                @if(Auth::user()->role == 'owner') <th class="p-2">Aksi</th> @endif
            </tr>
        </thead>
        <tbody>
            @foreach($complaints as $complaint)
            <tr class="border-b">
                <td class="p-2">{{ $complaint->tenant->user->name }}</td>
                <td class="p-2">{{ $complaint->description }}</td>
                <td class="p-2">
                    <span class="px-2 py-1 rounded text-xs font-bold 
                        {{ $complaint->status == 'pending' ? 'bg-yellow-200' : ($complaint->status == 'process' ? 'bg-blue-200' : 'bg-green-200') }}">
                        {{ strtoupper($complaint->status) }}
                    </span>
                </td>
                @if(Auth::user()->role == 'owner')
                <td class="p-2">
                    <form action="{{ route('complaints.status', $complaint->id) }}" method="POST" class="inline">
                        @csrf @method('PATCH')
                        <select name="status" onchange="this.form.submit()" class="text-sm border rounded">
                            <option value="pending" {{ $complaint->status == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="process" {{ $complaint->status == 'process' ? 'selected' : '' }}>Proses</option>
                            <option value="resolved" {{ $complaint->status == 'resolved' ? 'selected' : '' }}>Selesai</option>
                        </select>
                    </form>
                </td>
                @endif
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection