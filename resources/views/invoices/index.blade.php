@extends('layouts.app')

@section('content')
<div class="bg-white p-6 rounded-lg shadow-md">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold">Manajemen Tagihan</h2>
        <div class="bg-blue-100 p-3 rounded text-blue-800">
            Total Pendapatan Bulan Ini: <span class="font-bold">Rp {{ number_format($invoices->where('status', 'paid')->sum('amount'), 0, ',', '.') }}</span>
        </div>
    </div>

    <table class="w-full text-left border-collapse">
        <thead>
            <tr class="border-b bg-gray-50">
                <th class="p-3">Penghuni</th>
                <th class="p-3">Kamar</th>
                <th class="p-3">Jumlah Tagihan</th>
                <th class="p-3">Jatuh Tempo</th>
                <th class="p-3">Status</th>
                <th class="p-3">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($invoices as $invoice)
            <tr class="border-b hover:bg-gray-50">
                <td class="p-3">{{ $invoice->tenant->user->name }}</td>
                <td class="p-3">No. {{ $invoice->tenant->room->room_number }}</td>
                <td class="p-3 font-semibold">Rp {{ number_format($invoice->amount, 0, ',', '.') }}</td>
                <td class="p-3">{{ $invoice->bill_date->format('d M Y') }}</td>
                <td class="p-3">
                    <span class="px-2 py-1 rounded text-xs font-bold 
                        {{ $invoice->status == 'unpaid' ? 'bg-red-200 text-red-800' : 'bg-green-200 text-green-800' }}">
                        {{ strtoupper($invoice->status) }}
                    </span>
                </td>
                <td class="p-3">
                    @if(Auth::user()->role == 'owner' && $invoice->status == 'unpaid')
                        <form action="{{ route('invoices.paid', $invoice->id) }}" method="POST">
                            @csrf @method('PATCH')
                            <button type="submit" class="bg-blue-500 text-white px-3 py-1 rounded text-sm hover:bg-blue-600">
                                Konfirmasi Bayar
                            </button>
                        </form>
                    @else
                        <span class="text-gray-400 text-sm">No Action</span>
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection