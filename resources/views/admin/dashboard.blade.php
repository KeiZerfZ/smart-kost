@extends('layouts.app')

@section('content')
<div class="mb-8">
    <h1 class="text-3xl font-bold text-gray-800">Dashboard Pemilik Kost</h1>
    <p class="text-gray-600">Ringkasan operasional SmartKost hari ini.</p>
</div>

<div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
    <div class="bg-white p-6 rounded-lg shadow border-l-4 border-green-500">
        <p class="text-sm text-gray-500 uppercase font-bold">Total Pendapatan</p>
        <p class="text-2xl font-bold">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</p>
    </div>
    <div class="bg-white p-6 rounded-lg shadow border-l-4 border-blue-500">
        <p class="text-sm text-gray-500 uppercase font-bold">Kamar Kosong</p>
        <p class="text-2xl font-bold">{{ $emptyRooms }} Kamar</p>
    </div>
    <div class="bg-white p-6 rounded-lg shadow border-l-4 border-red-500">
        <p class="text-sm text-gray-500 uppercase font-bold">Tagihan Tertunggak</p>
        <p class="text-2xl font-bold">{{ $unpaidInvoices }} Orang</p>
    </div>
    <div class="bg-white p-6 rounded-lg shadow border-l-4 border-purple-500">
        <p class="text-sm text-gray-500 uppercase font-bold">Total Penghuni</p>
        <p class="text-2xl font-bold">{{ $activeTenants }} Orang</p>
    </div>
</div>

<div class="bg-white p-6 rounded-lg shadow">
    <h2 class="text-xl font-bold mb-4">Tren Pendapatan Bulanan</h2>
    <canvas id="revenueChart" height="100"></canvas>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const ctx = document.getElementById('revenueChart').getContext('2d');
    const revenueChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: {!! json_encode($monthlyRevenue->pluck('month')) !!},
            datasets: [{
                label: 'Pendapatan (Rp)',
                data: {!! json_encode($monthlyRevenue->pluck('total')) !!},
                borderColor: 'rgb(59, 130, 246)',
                backgroundColor: 'rgba(59, 130, 246, 0.1)',
                fill: true,
                tension: 0.3
            }]
        },
        options: {
            scales: {
                y: { beginAtZero: true }
            }
        }
    });
</script>
@endsection