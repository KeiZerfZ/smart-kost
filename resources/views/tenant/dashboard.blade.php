@extends('layouts.app')

@section('content')
@php
    // Logika Tambahan untuk Tenggat & Estimasi
    $earliestUnpaid = $unpaidInvoices->sortBy('bill_date')->first();
    $lastInvoice = \App\Models\Invoice::where('tenant_id', $tenant->id)->latest('bill_date')->first();
    
    // Estimasi adalah 1 bulan setelah tagihan terakhir, atau bulan ini jika belum ada
    $nextEstimation = $lastInvoice ? $lastInvoice->bill_date->addMonth() : now();
    
    // Asumsi tenggat adalah tanggal 10 setiap bulannya (bisa lu sesuaikan)
    $deadlineDate = $earliestUnpaid ? $earliestUnpaid->bill_date->day(10) : null;
@endphp

<div class="max-w-6xl mx-auto px-2">
    <div class="mb-8 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h1 class="text-3xl md:text-4xl font-black text-gray-800 dark:text-gray-100 tracking-tighter transition-colors">Halo, {{ $tenant->user->name }}! 👋</h1>
            <p class="text-gray-500 dark:text-gray-400 font-medium transition-colors">Cek info kamar dan tagihan lu di sini.</p>
        </div>
        <div class="flex items-center space-x-2">
             <div class="px-4 py-2 bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 rounded-2xl text-xs font-black uppercase tracking-widest border border-blue-100 dark:border-blue-800/40 transition-colors">
                {{ $tenant->room->room_number }} - {{ $tenant->room->type }}
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 md:gap-8">
        
        <div class="space-y-6">
            <div class="bg-gradient-to-br from-blue-600 to-blue-800 text-white p-8 rounded-[2.5rem] shadow-xl shadow-blue-100 dark:shadow-none relative overflow-hidden">
                <div class="absolute -top-6 -right-6 w-24 h-24 bg-white/10 rounded-full"></div>
                <h3 class="text-sm font-black opacity-70 mb-6 uppercase tracking-[0.2em]">Kamar Lu</h3>
                <div class="text-center py-2">
                    <span class="text-6xl font-black block mb-2 tracking-tighter">{{ $tenant->room->room_number }}</span>
                    <span class="bg-white/20 backdrop-blur-md px-4 py-1 rounded-full text-[10px] font-black uppercase tracking-widest">
                        {{ $tenant->room->type }} CLASS
                    </span>
                </div>
                <div class="mt-8 border-t border-white/20 pt-6 flex justify-between items-center text-sm">
                    <span class="opacity-70 font-medium">Sewa/Bulan:</span>
                    <span class="font-black text-lg">Rp {{ number_format($tenant->room->price, 0, ',', '.') }}</span>
                </div>
            </div>

            <div class="bg-white dark:bg-slate-900 p-6 rounded-[2.5rem] border border-gray-100 dark:border-slate-800 shadow-sm overflow-hidden relative transition-colors">
                <div class="flex items-center space-x-3 mb-6">
                    <div class="p-2 bg-orange-50 dark:bg-orange-900/20 text-orange-500 dark:text-orange-400 rounded-xl transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                    <h4 class="font-black text-gray-800 dark:text-gray-100 uppercase text-xs tracking-widest transition-colors">Jadwal Bayar</h4>
                </div>

                <div class="space-y-4">
                    <div class="p-4 rounded-2xl transition-colors {{ $earliestUnpaid ? 'bg-red-50 dark:bg-red-900/20 border border-red-100 dark:border-red-800/40' : 'bg-gray-50 dark:bg-slate-800 border border-gray-100 dark:border-slate-700' }}">
                        <p class="text-[10px] font-black text-gray-400 dark:text-gray-500 uppercase mb-1">Tenggat Tagihan Ini</p>
                        @if($earliestUnpaid)
                            <p class="text-sm font-black text-red-600 dark:text-red-400 italic">{{ $deadlineDate->format('d M Y') }}</p>
                            <p class="text-[9px] text-red-400 dark:text-red-300 font-bold mt-1 uppercase leading-tight">Segera lunasi sebelum akses layanan terbatas!</p>
                        @else
                            <p class="text-sm font-black text-gray-400 dark:text-gray-500 italic">Tidak ada tagihan aktif</p>
                        @endif
                    </div>

                    <div class="p-4 bg-blue-50 dark:bg-blue-900/20 rounded-2xl border border-blue-100 dark:border-blue-800/40 transition-colors">
                        <p class="text-[10px] font-black text-blue-400 dark:text-blue-300 uppercase mb-1">Estimasi Tagihan Berikutnya</p>
                        <p class="text-sm font-black text-blue-600 dark:text-blue-400 italic">{{ $nextEstimation->format('F Y') }}</p>
                        <p class="text-[9px] text-blue-400 dark:text-blue-300 font-bold mt-1 uppercase leading-tight">Tagihan akan otomatis muncul pada tanggal 1.</p>
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-slate-900 p-6 rounded-[2rem] border border-gray-100 dark:border-slate-800 shadow-sm group transition-colors">
                <h4 class="font-black text-gray-800 dark:text-gray-100 mb-2 uppercase text-xs tracking-widest transition-colors">Bantuan?</h4>
                <p class="text-sm text-gray-500 dark:text-gray-400 mb-6 leading-relaxed transition-colors">Ada kendala pembayaran? Hubungi admin via WhatsApp.</p>
                <a href="https://wa.me/628123456789" target="_blank" class="flex items-center justify-center space-x-2 bg-green-500 text-white font-black py-4 rounded-2xl hover:bg-green-600 transition shadow-lg shadow-green-100 dark:shadow-none active:scale-95">
                    <span class="text-sm uppercase tracking-widest">WhatsApp Admin</span>
                </a>
            </div>
        </div>

        <div class="lg:col-span-2 space-y-6 md:space-y-8">
            
            <div class="bg-white dark:bg-slate-900 p-6 md:p-8 rounded-[2.5rem] shadow-sm border border-gray-100 dark:border-slate-800 transition-colors">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-xl font-black text-gray-800 dark:text-gray-100 tracking-tight italic transition-colors">Tagihan Aktif.</h3>
                </div>

                @if($unpaidInvoices->isEmpty())
                    <div class="flex flex-col items-center justify-center py-12 bg-gray-50 dark:bg-slate-800 rounded-3xl border border-dashed border-gray-200 dark:border-slate-700 transition-colors">
                        <div class="w-16 h-16 bg-green-100 dark:bg-green-900/30 text-green-600 dark:text-green-400 rounded-full flex items-center justify-center mb-4 text-3xl animate-bounce">✓</div>
                        <p class="text-gray-800 dark:text-gray-100 font-black transition-colors">Semua tagihan lunas!</p>
                    </div>
                @else
                    <div class="overflow-x-auto">
                        <table class="w-full text-left min-w-[500px]">
                            <thead>
                                <tr class="text-gray-400 dark:text-gray-500 text-[10px] font-black uppercase tracking-widest border-b border-gray-50 dark:border-slate-800 transition-colors">
                                    <th class="pb-4">Periode</th>
                                    <th class="pb-4 text-right">Nominal</th>
                                    <th class="pb-4 text-right">Tindakan</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($unpaidInvoices as $inv)
                                <tr class="border-b border-gray-50 dark:border-slate-800 last:border-0 hover:bg-gray-50/50 dark:hover:bg-slate-800/30 transition">
                                    <td class="py-5 font-bold text-gray-700 dark:text-gray-200 transition-colors">{{ $inv->bill_date->format('F Y') }}</td>
                                    <td class="py-5 text-right font-black text-red-600 dark:text-red-400 italic text-lg transition-colors">Rp {{ number_format($inv->amount, 0, ',', '.') }}</td>
                                    <td class="py-5 text-right">
                                        <button onclick="openQRIS('{{ route('invoices.payQRIS', $inv->id) }}')" class="bg-blue-600 text-white px-5 py-2.5 rounded-xl text-xs font-black uppercase tracking-widest transition shadow-lg shadow-blue-100 dark:shadow-none hover:bg-blue-700 active:scale-95">
                                            Bayar QRIS
                                        </button>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>

            <div class="bg-white dark:bg-slate-900 p-6 md:p-8 rounded-[2.5rem] shadow-sm border border-gray-100 dark:border-slate-800 transition-colors">
                <div class="flex flex-col sm:flex-row justify-between sm:items-center gap-4 mb-8">
                    <h3 class="text-xl font-black text-gray-800 dark:text-gray-100 tracking-tight italic transition-colors">Laporan Fasilitas.</h3>
                    <button onclick="document.getElementById('modal-complaint').classList.remove('hidden')" class="bg-gray-800 dark:bg-slate-700 text-white px-6 py-3 rounded-2xl text-xs font-black uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-slate-600 transition active:scale-95">
                        + Buat Laporan
                    </button>
                </div>
                
                @if($recentComplaints->isEmpty())
                    <p class="text-center text-gray-400 dark:text-gray-500 py-6 italic text-sm border-2 border-dashed border-gray-100 dark:border-slate-700 rounded-3xl transition-colors">
                        Belum ada laporan.
                    </p>
                @else
                    <div class="space-y-3">
                        @foreach($recentComplaints as $c)
                        <div class="flex items-center justify-between p-5 bg-gray-50/50 dark:bg-slate-800/50 rounded-2xl border border-gray-100 dark:border-slate-700 transition-colors">
                            <div>
                                <h4 class="font-black text-gray-800 dark:text-gray-100 text-sm tracking-tight transition-colors">{{ $c->title }}</h4>
                                <p class="text-[10px] text-gray-400 dark:text-gray-500 font-bold uppercase italic transition-colors">{{ $c->created_at->diffForHumans() }}</p>
                            </div>
                            <span class="px-4 py-1.5 rounded-xl text-[9px] font-black uppercase tracking-widest
                                {{ $c->status == 'pending' ? 'bg-orange-100 dark:bg-orange-900/30 text-orange-600 dark:text-orange-400' : '' }}
                                {{ $c->status == 'process' ? 'bg-blue-100 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400' : '' }}
                                {{ $c->status == 'resolved' ? 'bg-green-100 dark:bg-green-900/30 text-green-600 dark:text-green-400' : '' }}">
                                {{ $c->status }}
                            </span>
                        </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<div id="modal-complaint" class="fixed inset-0 bg-black/60 backdrop-blur-sm hidden flex items-center justify-center z-[100] p-4">
    <div class="bg-white dark:bg-slate-900 rounded-[2.5rem] shadow-2xl max-w-lg w-full p-8 border border-gray-100 dark:border-slate-800 transition-colors">
        <h3 class="text-2xl font-black text-gray-800 dark:text-gray-100 mb-8 italic transition-colors">Kirim Keluhan.</h3>

        <form action="{{ route('complaints.store') }}" method="POST">
            @csrf

            <div class="mb-5">
                <label class="block text-[10px] font-black text-gray-400 dark:text-gray-500 uppercase tracking-widest mb-2 ml-1 transition-colors">
                    Subjek
                </label>

                <input type="text"
                       name="title"
                       class="w-full p-4 bg-gray-50 dark:bg-slate-800 border border-gray-100 dark:border-slate-700 rounded-2xl outline-none text-gray-800 dark:text-gray-100 transition-colors"
                       required>
            </div>

            <div class="mb-8">
                <label class="block text-[10px] font-black text-gray-400 dark:text-gray-500 uppercase tracking-widest mb-2 ml-1 transition-colors">
                    Detail
                </label>

                <textarea name="description"
                          rows="4"
                          class="w-full p-4 bg-gray-50 dark:bg-slate-800 border border-gray-100 dark:border-slate-700 rounded-2xl outline-none text-gray-800 dark:text-gray-100 transition-colors"
                          required></textarea>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <button type="button"
                        onclick="document.getElementById('modal-complaint').classList.add('hidden')"
                        class="bg-gray-100 dark:bg-slate-800 py-4 rounded-2xl font-black text-gray-500 dark:text-gray-300 text-xs uppercase tracking-widest transition-colors">
                    Batal
                </button>

                <button type="submit"
                        class="bg-blue-600 text-white font-black py-4 rounded-2xl shadow-xl shadow-blue-100 dark:shadow-none text-xs uppercase tracking-widest">
                    Kirim
                </button>
            </div>
        </form>
    </div>
</div>

<div id="modal-qris" class="fixed inset-0 bg-black/60 backdrop-blur-sm hidden flex items-center justify-center z-[100] p-4">
    <div class="bg-white dark:bg-slate-900 rounded-[3rem] p-10 max-w-sm w-full text-center shadow-2xl border border-gray-100 dark:border-slate-800 transition-colors">
        <h3 class="text-3xl font-black text-gray-800 dark:text-gray-100 mb-2 tracking-tighter italic transition-colors">Scan Pay.</h3>

        <p class="text-[10px] text-gray-400 dark:text-gray-500 mb-8 font-black uppercase tracking-widest transition-colors">
            SmartKost Payments
        </p>

        <div class="bg-gray-100 dark:bg-slate-800 p-6 rounded-3xl mb-8 relative transition-colors">
            <img src="https://api.qrserver.com/v1/create-qr-code/?size=200x200&data=SMARTKOST-PAY"
                 alt="QRIS"
                 class="mx-auto rounded-xl shadow-md border-4 border-white dark:border-slate-700 relative z-10">
        </div>

        <form id="form-qris" action="" method="POST" class="space-y-4">
            @csrf @method('PATCH')

            <button type="submit"
                    class="w-full bg-blue-600 text-white py-5 rounded-[2rem] font-black text-xs uppercase tracking-widest shadow-xl shadow-blue-200 dark:shadow-none">
                SAYA SUDAH BAYAR
            </button>
        </form>

        <button onclick="document.getElementById('modal-qris').classList.add('hidden')"
                class="mt-6 text-gray-400 dark:text-gray-500 text-[10px] font-black uppercase tracking-widest transition-colors">
            Batalkan
        </button>
    </div>
</div>

<script>
    function openQRIS(url) {
        document.getElementById('form-qris').action = url;
        document.getElementById('modal-qris').classList.remove('hidden');
    }
</script>
@endsection