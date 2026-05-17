@extends('layouts.app')

@section('content')
    <div class="max-w-6xl mx-auto px-2">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-8">
            <div>
                <h1 class="text-3xl font-black text-gray-800 tracking-tighter italic">Laporan Keluhan.</h1>
                <p class="text-gray-500 font-medium text-sm">Monitoring perbaikan dan aspirasi dari penghuni.</p>
            </div>
            <div class="inline-flex items-center space-x-2 bg-orange-50 px-5 py-3 rounded-2xl border border-orange-100">
                <span class="relative flex h-3 w-3">
                    <span
                        class="animate-ping absolute inline-flex h-full w-full rounded-full bg-orange-400 opacity-75"></span>
                    <span class="relative inline-flex rounded-full h-3 w-3 bg-orange-500"></span>
                </span>
                <span class="text-xs font-black text-orange-600 uppercase tracking-widest">
                    {{ $complaints->where('status', 'pending')->count() }} Laporan Pending
                </span>
            </div>
        </div>

        <div class="bg-white rounded-[2.5rem] shadow-sm border border-gray-100 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse min-w-[800px]">
                    <thead>
                        <tr class="bg-gray-50/50 border-b border-gray-50">
                            <th class="p-6 text-[10px] font-black text-gray-400 uppercase tracking-widest">Penghuni / Unit
                            </th>
                            <th class="p-6 text-[10px] font-black text-gray-400 uppercase tracking-widest">Detail Masalah
                            </th>
                            <th class="p-6 text-[10px] font-black text-gray-400 uppercase tracking-widest text-center">
                                Status</th>
                            <th class="p-6 text-[10px] font-black text-gray-400 uppercase tracking-widest text-right">
                                Tindakan Admin</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @foreach($complaints as $c)
                            <tr class="hover:bg-gray-50/50 transition group">
                                <td class="p-6">
                                    <div class="flex items-center space-x-4">
                                        <div
                                            class="w-10 h-10 bg-gray-100 text-gray-500 rounded-full flex items-center justify-center font-black text-sm uppercase">
                                            {{ substr($c->tenant->user->name, 0, 1) }}
                                        </div>
                                        <div>
                                            <h4 class="font-bold text-gray-800 tracking-tight">{{ $c->tenant->user->name }}</h4>
                                            <span
                                                class="bg-blue-50 text-blue-600 px-2 py-0.5 rounded text-[10px] font-black uppercase tracking-tighter italic">
                                                Kamar {{ $c->tenant->room->room_number }}
                                            </span>
                                        </div>
                                    </div>
                                </td>

                                <td class="p-6 max-w-xs">
                                    <div class="font-black text-gray-700 text-sm mb-1 tracking-tight">{{ $c->title }}</div>
                                    <p class="text-xs text-gray-500 leading-relaxed truncate group-hover:whitespace-normal">
                                        {{ $c->description }}
                                    </p>
                                    <div
                                        class="flex items-center text-[9px] text-gray-400 font-bold uppercase mt-2 tracking-widest">
                                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        {{ $c->created_at->format('d M Y, H:i') }}
                                    </div>
                                </td>

                                <td class="p-6 text-center">
                                    <span class="inline-block px-4 py-1.5 rounded-xl text-[9px] font-black uppercase tracking-widest border
                                        {{ $c->status == 'pending' ? 'bg-orange-100 text-orange-600 border-orange-200' : '' }}
                                        {{ $c->status == 'process' ? 'bg-blue-100 text-blue-600 border-blue-200' : '' }}
                                        {{ $c->status == 'resolved' ? 'bg-green-100 text-green-600 border-green-200' : '' }}">
                                        {{ $c->status }}
                                    </span>
                                </td>

                                <td class="p-6">
                                    <div class="flex justify-end items-center">
                                        <form action="{{ route('complaints.updateStatus', $c->id) }}" method="POST"
                                            class="flex gap-2">
                                            @csrf @method('PATCH')

                                            @if($c->status == 'pending')
                                                <button name="status" value="process"
                                                    class="inline-flex items-center gap-1.5 bg-blue-600 text-white px-4 py-2 rounded-xl text-[10px] font-black uppercase tracking-widest hover:bg-blue-700 shadow-lg shadow-blue-100 transition active:scale-95">
                                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                            d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                                                    </svg>
                                                    Proses
                                                </button>
                                            @endif

                                            @if($c->status == 'process' || $c->status == 'pending')
                                                <button name="status" value="resolved"
                                                    class="inline-flex items-center gap-1.5 bg-green-500 text-white px-4 py-2 rounded-xl text-[10px] font-black uppercase tracking-widest hover:bg-green-600 shadow-lg shadow-green-100 transition active:scale-95">
                                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                            d="M5 13l4 4L19 7"></path>
                                                    </svg>
                                                    Selesai
                                                </button>
                                            @endif

                                            @if($c->status == 'resolved')
                                                <span
                                                    class="inline-flex items-center gap-1.5 text-[10px] font-black text-gray-400 uppercase tracking-widest italic px-4 py-2">
                                                    <svg class="w-3.5 h-3.5 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                                                        <path
                                                            d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z">
                                                        </path>
                                                    </svg>
                                                    Sudah Ditangani
                                                </span>
                                            @endif
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            @if($complaints->isEmpty())
                <div class="py-20 text-center">
                    <div class="inline-flex items-center justify-center w-20 h-20 bg-gray-50 text-gray-300 rounded-full mb-4">
                        <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <p class="text-gray-400 font-black uppercase text-xs tracking-widest">Aman Terkendali.</p>
                    <p class="text-gray-400 text-xs mt-1">Belum ada keluhan dari para penghuni.</p>
                </div>
            @endif
        </div>
    </div>
@endsection