@extends('layouts.app')

@section('content')
<div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
    <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
        <p class="text-sm text-gray-500 font-bold uppercase">Total Kamar</p>
        <h3 class="text-3xl font-black text-blue-600">{{ $totalRooms }}</h3>
        <p class="text-xs text-green-600 mt-2">● {{ $emptyRooms }} Tersedia</p>
    </div>

    <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
        <p class="text-sm text-gray-500 font-bold uppercase">Penghuni Aktif</p>
        <h3 class="text-3xl font-black text-gray-800">{{ $totalTenants }}</h3>
        <p class="text-xs text-gray-400 mt-2 italic text-sm">Orang</p>
    </div>

    <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
        <p class="text-sm text-gray-500 font-bold uppercase">Keluhan Pending</p>
        <h3 class="text-3xl font-black text-orange-500">{{ $pendingComplaints }}</h3>
        <p class="text-xs text-gray-400 mt-2">Butuh respon segera</p>
    </div>

    <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
        <p class="text-sm text-gray-500 font-bold uppercase">Pemasukan Bulan Ini</p>
        <h3 class="text-2xl font-black text-green-600">Rp {{ number_format($monthlyIncome) }}</h3>
        <p class="text-xs text-gray-400 mt-2">Dari tagihan lunas</p>
    </div>
</div>

<div class="bg-blue-50 p-8 rounded-2xl border-2 border-dashed border-blue-200 text-center">
    <h2 class="text-xl font-bold text-blue-800">Selamat Bekerja, Admin!</h2>
    <p class="text-blue-600">Gunakan menu di atas untuk mengelola SmartKost.</p>
</div>
@endsection