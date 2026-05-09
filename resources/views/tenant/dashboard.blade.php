@extends('layouts.app')

@section('content')
<div class="max-w-6xl mx-auto">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-800">Halo, {{ $tenant->user->name }}! 👋</h1>
        <p class="text-gray-600 font-medium">Selamat datang di dashboard SmartKost.</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        <div class="space-y-6">
            <div class="bg-gradient-to-br from-blue-600 to-blue-800 text-white p-6 rounded-2xl shadow-lg border-none">
                <h3 class="text-lg font-semibold opacity-80 mb-4 uppercase tracking-wider">Info Kamar Lu</h3>
                <div class="text-center py-4">
                    <span class="text-5xl font-extrabold block mb-2">{{ $tenant->room->room_number }}</span>
                    <span class="bg-blue-500 px-3 py-1 rounded-full text-sm font-bold uppercase">Tipe: {{ $tenant->room->type }}</span>
                </div>
                <div class="mt-6 border-t border-blue-400 pt-4 flex justify-between">
                    <span>Harga Sewa:</span>
                    <span class="font-bold">Rp {{ number_format($tenant->room->price) }}</span>
                </div>
            </div>

            <div class="bg-gray-100 p-6 rounded-2xl border border-dashed border-gray-300">
                <h4 class="font-bold mb-2">Bantuan?</h4>
                <p class="text-sm text-gray-600 mb-4">Ada kendala pembayaran atau butuh akses kunci duplikat? Hubungi pengurus via WhatsApp.</p>
                <a href="https://wa.me/628123456789" target="_blank" class="block text-center bg-green-500 text-white font-bold py-2 rounded-lg hover:bg-green-600 transition shadow-md shadow-green-100">
                    WhatsApp Admin
                </a>
            </div>
        </div>

        <div class="lg:col-span-2 space-y-6">
            
            <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
                <h3 class="text-lg font-bold text-gray-800 mb-4">Tagihan Menunggu Bayar</h3>
                @if($unpaidInvoices->isEmpty())
                    <div class="flex flex-col items-center justify-center py-10">
                        <span class="text-4xl mb-2">🎉</span>
                        <p class="text-green-600 font-bold">Mantap! Semua tagihan sudah lunas.</p>
                    </div>
                @else
                    <div class="overflow-x-auto">
                        <table class="w-full text-left">
                            <thead>
                                <tr class="text-gray-400 text-sm border-b">
                                    <th class="pb-3">Bulan Tagihan</th>
                                    <th class="pb-3 text-right">Jumlah</th>
                                    <th class="pb-3 text-right">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($unpaidInvoices as $inv)
                                <tr class="border-b last:border-0">
                                    <td class="py-4 font-medium">{{ $inv->bill_date->format('F Y') }}</td>
                                    <td class="py-4 text-right font-bold text-red-600">Rp {{ number_format($inv->amount) }}</td>
                                    <td class="py-4 text-right">
                                        <button onclick="openQRIS('{{ route('invoices.payQRIS', $inv->id) }}')" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg text-xs font-bold transition shadow-md shadow-indigo-100">
                                            Bayar QRIS
                                        </button>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif

                @php
                    $paidInvoices = \App\Models\Invoice::where('tenant_id', $tenant->id)->where('status', 'paid')->latest()->get();
                @endphp
                @if($paidInvoices->isNotEmpty())
                <div class="mt-8 pt-6 border-t border-gray-100">
                    <h4 class="text-sm font-bold text-gray-400 uppercase mb-4 tracking-widest">Riwayat Lunas (Download PDF)</h4>
                    <div class="space-y-2">
                        @foreach($paidInvoices as $paid)
                        <div class="flex justify-between items-center bg-gray-50 p-3 rounded-xl border border-gray-100">
                            <div>
                                <span class="font-bold text-gray-700 text-sm">{{ $paid->bill_date->format('F Y') }}</span>
                                <span class="text-[10px] bg-green-100 text-green-600 px-2 py-0.5 rounded ml-2 font-bold uppercase">{{ $paid->payment_method }}</span>
                            </div>
                            <a href="{{ route('invoices.download', $paid->id) }}" class="text-blue-600 text-xs font-bold hover:underline flex items-center">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                                Bukti PDF
                            </a>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif
            </div>

            <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-lg font-bold text-gray-800">Keluhan Terakhir</h3>
                    <button onclick="document.getElementById('modal-complaint').classList.remove('hidden')" class="bg-gray-800 text-white px-4 py-2 rounded-lg text-sm font-bold hover:bg-gray-700 transition">
                        + Kirim Keluhan Baru
                    </button>
                </div>
                
                @if($recentComplaints->isEmpty())
                    <p class="text-center text-gray-400 py-4 italic text-sm">Belum ada laporan keluhan.</p>
                @else
                    @foreach($recentComplaints as $c)
                    <div class="flex items-center justify-between p-4 mb-3 bg-gray-50 rounded-xl border border-gray-100">
                        <div>
                            <h4 class="font-bold text-gray-700">{{ $c->title }}</h4>
                            <p class="text-xs text-gray-500">{{ $c->created_at->diffForHumans() }}</p>
                        </div>
                        <div>
                            <span class="px-3 py-1 rounded-full text-[10px] font-bold uppercase
                                {{ $c->status == 'pending' ? 'bg-orange-100 text-orange-600' : '' }}
                                {{ $c->status == 'process' ? 'bg-blue-100 text-blue-600' : '' }}
                                {{ $c->status == 'resolved' ? 'bg-green-100 text-green-600' : '' }}">
                                {{ $c->status }}
                            </span>
                        </div>
                    </div>
                    @endforeach
                @endif
            </div>

        </div>
    </div>
</div>

<div id="modal-complaint" class="fixed inset-0 bg-black bg-opacity-50 hidden flex items-center justify-center z-50 p-4">
    <div class="bg-white rounded-2xl shadow-xl max-w-lg w-full p-6 animate-shrink-in">
        <div class="flex justify-between items-center mb-6">
            <h3 class="text-xl font-bold text-gray-800">Lapor Keluhan Baru</h3>
            <button onclick="document.getElementById('modal-complaint').classList.add('hidden')" class="text-gray-400 hover:text-gray-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
        </div>
        <form action="{{ route('complaints.store') }}" method="POST">
            @csrf
            <div class="mb-4">
                <label class="block text-sm font-bold text-gray-700 mb-1">Judul Keluhan</label>
                <input type="text" name="title" placeholder="Misal: AC Bocor" class="w-full p-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 outline-none" required>
            </div>
            <div class="mb-6">
                <label class="block text-sm font-bold text-gray-700 mb-1">Deskripsi Detail</label>
                <textarea name="description" rows="4" placeholder="Jelaskan detail kerusakannya..." class="w-full p-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 outline-none" required></textarea>
            </div>
            <div class="flex space-x-3">
                <button type="button" onclick="document.getElementById('modal-complaint').classList.add('hidden')" class="flex-1 bg-gray-100 py-3 rounded-xl font-bold text-gray-600">Batal</button>
                <button type="submit" class="flex-1 bg-blue-600 text-white font-bold py-3 rounded-xl shadow-lg shadow-blue-100">Kirim Laporan</button>
            </div>
        </form>
    </div>
</div>

<div id="modal-qris" class="fixed inset-0 bg-black bg-opacity-50 hidden flex items-center justify-center z-50 p-4">
    <div class="bg-white rounded-3xl p-8 max-w-sm w-full text-center shadow-2xl">
        <h3 class="text-xl font-bold text-gray-800 mb-2">Pembayaran QRIS</h3>
        <p class="text-xs text-gray-400 mb-6 font-medium uppercase tracking-widest">Scan & Bayar Sekarang</p>
        
        <div class="bg-gray-50 p-4 rounded-2xl mb-6">
            <img src="https://api.qrserver.com/v1/create-qr-code/?size=200x200&data=SMARTKOST-PAY" alt="QRIS" class="mx-auto rounded-lg shadow-sm border-4 border-white">
        </div>
        
        <p class="text-sm text-gray-500 mb-8 leading-relaxed">Silakan bayar sesuai nominal tagihan lu. Status akan otomatis lunas setelah lu menekan tombol di bawah.</p>
        
        <form id="form-qris" action="" method="POST">
            @csrf @method('PATCH')
            <button type="submit" class="w-full bg-indigo-600 text-white py-4 rounded-2xl font-bold shadow-lg shadow-indigo-100 hover:bg-indigo-700 transition">
                Saya Sudah Bayar
            </button>
        </form>
        <button onclick="document.getElementById('modal-qris').classList.add('hidden')" class="mt-4 text-gray-400 text-sm hover:underline">Nanti Saja</button>
    </div>
</div>

<script>
    function openQRIS(url) {
        document.getElementById('form-qris').action = url;
        document.getElementById('modal-qris').classList.remove('hidden');
    }
</script>
@endsection