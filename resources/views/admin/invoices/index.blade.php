@extends('layouts.app')

@section('content')
    <div class="max-w-6xl mx-auto px-2">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-8">
            <div>
                <h1 class="text-3xl font-black text-gray-800 dark:text-gray-100 tracking-tighter italic transition-colors">
                    Laporan Keuangan.
                </h1>
                <p class="text-gray-500 dark:text-gray-400 font-medium text-sm">
                    Monitor seluruh tagihan dan arus kas SmartKost.
                </p>
            </div>

            <div class="bg-green-50 dark:bg-green-900/20 px-4 py-2 rounded-2xl border border-green-100 dark:border-green-800/30 text-center transition-colors">
                <p class="text-[9px] font-black text-green-600 dark:text-green-400 uppercase tracking-widest">
                    Total Lunas
                </p>
                <p class="font-black text-green-700 dark:text-green-300">
                    Rp {{ number_format($invoices->where('status', 'paid')->sum('amount'), 0, ',', '.') }}
                </p>
            </div>
        </div>

        <div class="bg-white dark:bg-slate-900 rounded-[2.5rem] shadow-sm border border-gray-100 dark:border-slate-800 overflow-hidden transition-colors">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse min-w-[900px]">
                    <thead>
                        <tr class="bg-gray-50/50 dark:bg-slate-800/50 border-b border-gray-50 dark:border-slate-800">
                            <th class="p-6 text-[10px] font-black text-gray-400 dark:text-gray-500 uppercase tracking-widest">
                                Penghuni & Status
                            </th>
                            <th class="p-6 text-[10px] font-black text-gray-400 dark:text-gray-500 uppercase tracking-widest">
                                Kamar
                            </th>
                            <th class="p-6 text-[10px] font-black text-gray-400 dark:text-gray-500 uppercase tracking-widest">
                                Periode Tagihan
                            </th>
                            <th class="p-6 text-[10px] font-black text-gray-400 dark:text-gray-500 uppercase tracking-widest">
                                Nominal
                            </th>
                            <th class="p-6 text-[10px] font-black text-gray-400 dark:text-gray-500 uppercase tracking-widest">
                                Metode / Status
                            </th>
                            <th class="p-6 text-[10px] font-black text-gray-400 dark:text-gray-500 uppercase tracking-widest text-center">
                                Aksi
                            </th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-gray-50 dark:divide-slate-800">
                        @foreach($invoices as $inv)
                            <tr class="hover:bg-gray-50/50 dark:hover:bg-slate-800/30 transition group">
                                <td class="p-6">
                                    <div class="flex items-center space-x-3">
                                        <img src="{{ $inv->tenant->user->avatar_url ?? 'https://ui-avatars.com/api/?name=User&color=7F9CF5&background=EBF4FF' }}" 
                                             class="w-10 h-10 rounded-xl object-cover border-2 border-gray-50 dark:border-slate-800 shadow-sm transition-transform group-hover:scale-110" 
                                             alt="Avatar">

                                        <div>
                                            <h4 class="font-bold text-gray-800 dark:text-gray-200 text-sm tracking-tight transition-colors">
                                                {{ $inv->tenant->user->name ?? 'Mantan Penghuni' }}
                                            </h4>

                                            <span class="text-[9px] font-black uppercase tracking-tighter {{ ($inv->tenant->room->status ?? '') == 'occupied' ? 'text-blue-500 dark:text-blue-400' : 'text-gray-400 dark:text-gray-500' }}">
                                                ● {{ ($inv->tenant->room->status ?? '') == 'occupied' ? 'Aktif' : 'Sudah Checkout' }}
                                            </span>
                                        </div>
                                    </div>
                                </td>

                                <td class="p-6">
                                    <span class="bg-blue-50 dark:bg-blue-900/30 text-blue-700 dark:text-blue-400 px-3 py-1 rounded-lg font-black text-xs italic transition-colors">
                                        {{ $inv->tenant->room->room_number ?? 'N/A' }}
                                    </span>
                                </td>

                                <td class="p-6">
                                    <div class="font-bold text-gray-700 dark:text-gray-200 text-sm italic transition-colors">
                                        {{ $inv->bill_date->format('F Y') }}
                                    </div>
                                    <p class="text-[9px] text-gray-400 dark:text-gray-500 font-bold uppercase tracking-widest">
                                        Jatuh Tempo: {{ $inv->due_date->format('d M Y') }}
                                    </p>
                                </td>

                                <td class="p-6">
                                    <div class="font-black text-gray-800 dark:text-gray-100 italic transition-colors">
                                        Rp {{ number_format($inv->amount, 0, ',', '.') }}
                                    </div>
                                </td>

                                <td class="p-6">
                                    @if($inv->status == 'paid')
                                        <div class="flex flex-col space-y-1">
                                            <span class="inline-flex items-center px-3 py-1 bg-green-100 dark:bg-green-900/30 text-green-600 dark:text-green-400 rounded-xl text-[9px] font-black uppercase tracking-widest w-fit border border-green-200 dark:border-green-800/40 transition-colors">
                                                Lunas
                                            </span>
                                            <span class="text-[9px] font-black text-gray-400 dark:text-gray-500 uppercase italic ml-1">
                                                via {{ $inv->payment_method ?? 'Cash' }}
                                            </span>
                                        </div>
                                    @else
                                        <span class="inline-flex items-center px-3 py-1 bg-red-50 dark:bg-red-900/20 text-red-500 dark:text-red-400 rounded-xl text-[9px] font-black uppercase tracking-widest w-fit border border-red-200 dark:border-red-800/40 animate-pulse transition-colors">
                                            Belum Bayar
                                        </span>
                                    @endif
                                </td>

                                <td class="p-6">
                                    <div class="flex justify-center items-center gap-2">
                                        @if($inv->status == 'unpaid')
                                            <form action="{{ route('invoices.pay', $inv->id) }}" method="POST">
                                                @csrf @method('PATCH')
                                                <button type="submit" class="inline-flex items-center gap-1.5 bg-blue-600 text-white px-4 py-2 rounded-xl text-[10px] font-black uppercase tracking-widest shadow-lg shadow-blue-100 dark:shadow-none hover:bg-blue-700 transition active:scale-95">
                                                    Konfirmasi Cash
                                                </button>
                                            </form>
                                        @else
                                            <a href="{{ route('invoices.download', $inv->id) }}" class="inline-flex items-center gap-1.5 bg-gray-800 dark:bg-slate-700 text-white px-4 py-2 rounded-xl text-[10px] font-black uppercase tracking-widest hover:bg-black dark:hover:bg-slate-600 transition shadow-lg active:scale-95">
                                                PDF
                                            </a>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            @if($invoices->isEmpty())
                <div class="py-20 text-center">
                    <p class="text-gray-400 dark:text-gray-500 font-black uppercase text-xs tracking-widest">
                        Belum Ada Transaksi.
                    </p>
                </div>
            @endif
        </div>
    </div>
@endsection