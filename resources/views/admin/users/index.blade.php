@extends('layouts.app')

@section('content')
    <div class="max-w-6xl mx-auto">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-8">
            <div>
                <h1 class="text-3xl font-black text-gray-800 dark:text-gray-100 tracking-tighter italic transition-colors">
                    Manajemen Akun.
                </h1>
                <p class="text-gray-500 dark:text-gray-400 font-medium text-sm transition-colors">
                    Pantau dan kelola akses user sistem SmartKost.
                </p>
            </div>

            <div class="bg-blue-100 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 px-6 py-3 rounded-2xl text-xs font-black uppercase tracking-widest transition-colors border border-blue-200 dark:border-blue-800/50">
                Total: {{ $users->count() }} User Terdaftar
            </div>
        </div>

        <div class="bg-white dark:bg-slate-900 rounded-[2.5rem] shadow-sm border border-gray-100 dark:border-slate-800 overflow-hidden transition-colors">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-gray-50/50 dark:bg-slate-800/50 border-b border-gray-50 dark:border-slate-800">
                            <th class="p-6 text-[10px] font-black text-gray-400 dark:text-gray-500 uppercase tracking-widest">
                                Profil User
                            </th>
                            <th class="p-6 text-[10px] font-black text-gray-400 dark:text-gray-500 uppercase tracking-widest">
                                Role / Akses
                            </th>
                            <th class="p-6 text-[10px] font-black text-gray-400 dark:text-gray-500 uppercase tracking-widest text-right">
                                Manajemen
                            </th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-gray-50 dark:divide-slate-800">
                        @foreach($users as $user)
                            <tr class="hover:bg-gray-50/50 dark:hover:bg-slate-800/30 transition group">
                                <td class="p-6">
                                    <div class="flex items-center">
                                        <img src="{{ $user->avatar_url }}" 
                                             class="w-12 h-12 rounded-2xl object-cover mr-4 border-2 border-gray-50 dark:border-slate-800 shadow-sm transition-transform group-hover:scale-110" 
                                             alt="{{ $user->name }}">

                                        <div>
                                            <div class="font-bold text-gray-800 dark:text-gray-200 transition-colors tracking-tight">
                                                {{ $user->name }}
                                            </div>
                                            <div class="text-[11px] text-gray-400 dark:text-gray-500 font-medium transition-colors">
                                                {{ $user->email }}
                                            </div>
                                        </div>
                                    </div>
                                </td>

                                <td class="p-6">
                                    <span class="px-3 py-1.5 rounded-xl text-[9px] font-black uppercase tracking-widest transition-colors border
                                        {{ $user->role == 'owner'
                                            ? 'bg-purple-100 dark:bg-purple-900/30 text-purple-600 dark:text-purple-400 border-purple-200 dark:border-purple-800/50'
                                            : 'bg-blue-100 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 border-blue-200 dark:border-blue-800/50' }}">
                                        {{ $user->role }}
                                    </span>
                                    
                                    @if($user->tenant && $user->tenant->room)
                                        <span class="ml-2 text-[9px] font-black text-gray-400 uppercase italic">
                                            Kamar {{ $user->tenant->room->room_number }}
                                        </span>
                                    @endif
                                </td>

                                <td class="p-6">
                                    <div class="flex items-center justify-end gap-2">
                                        <form action="{{ route('users.reset', $user->id) }}" method="POST" onsubmit="return confirm('Reset password user ini?')">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="inline-flex items-center gap-1.5 bg-orange-50 dark:bg-orange-900/20 text-orange-600 dark:text-orange-400 hover:bg-orange-500 hover:text-white dark:hover:bg-orange-600 px-4 py-2 rounded-xl text-[10px] font-black uppercase tracking-widest transition border border-orange-100 dark:border-orange-800/30">
                                                Reset Pass
                                            </button>
                                        </form>

                                        @if($user->id != auth()->id())
                                            <form action="{{ route('users.destroy', $user->id) }}" method="POST" onsubmit="return confirm('Hapus akun ini secara permanen?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="inline-flex items-center gap-1.5 bg-red-50 dark:bg-red-900/20 text-red-600 dark:text-red-400 hover:bg-red-500 hover:text-white px-4 py-2 rounded-xl text-[10px] font-black uppercase tracking-widest transition border border-red-100 dark:border-red-800/30">
                                                    Hapus
                                                </button>
                                            </form>
                                        @else
                                            <span class="text-[10px] font-black text-gray-300 dark:text-gray-600 uppercase tracking-widest italic px-4 py-2">
                                                Akun Anda
                                            </span>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection