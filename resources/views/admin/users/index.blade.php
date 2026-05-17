@extends('layouts.app')

@section('content')
    <div class="max-w-6xl mx-auto">
        <div class="flex justify-between items-center mb-8">
            <div>
                <h1 class="text-3xl font-bold text-gray-800">Manajemen Akun</h1>
                <p class="text-gray-500 font-medium">Pantau dan kelola akses user SmartKost.</p>
            </div>
            <div class="bg-blue-100 text-blue-600 px-4 py-2 rounded-2xl text-xs font-black uppercase tracking-widest">
                Total: {{ $users->count() }} User
            </div>
        </div>

        <div class="bg-white rounded-[2.5rem] shadow-sm border border-gray-100 overflow-hidden">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50/50">
                        <th class="p-6 text-xs font-black text-gray-400 uppercase tracking-widest">Nama & Email</th>
                        <th class="p-6 text-xs font-black text-gray-400 uppercase tracking-widest">Role</th>
                        <th class="p-6 text-xs font-black text-gray-400 uppercase tracking-widest">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @foreach($users as $user)
                        <tr class="hover:bg-gray-50/50 transition">
                            <td class="p-6">
                                <div class="flex items-center">
                                    <div
                                        class="w-10 h-10 bg-gray-100 rounded-full flex items-center justify-center font-bold text-blue-600 mr-3">
                                        {{ substr($user->name, 0, 1) }}
                                    </div>
                                    <div>
                                        <div class="font-bold text-gray-800">{{ $user->name }}</div>
                                        <div class="text-xs text-gray-400 font-medium">{{ $user->email }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="p-6">
                                <span
                                    class="px-3 py-1 rounded-lg text-[10px] font-black uppercase tracking-tighter
                                        {{ $user->role == 'owner' ? 'bg-purple-100 text-purple-600' : 'bg-blue-100 text-blue-600' }}">
                                    {{ $user->role }}
                                </span>
                            </td>
                            <td class="p-6">
                                <div class="flex items-center gap-2">
                                    <form action="{{ route('users.reset', $user->id) }}" method="POST"
                                        onsubmit="return confirm('Reset password user ini?')">
                                        @csrf @method('PATCH')
                                        <button type="submit"
                                            class="inline-flex items-center gap-1.5 bg-orange-50 text-orange-600 hover:bg-orange-100 px-3 py-2 rounded-xl text-[10px] font-black uppercase tracking-widest transition">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path
                                                    d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"
                                                    stroke-width="2"></path>
                                            </svg>
                                            Reset Pass
                                        </button>
                                    </form>

                                    @if($user->id != auth()->id())
                                        <form action="{{ route('users.destroy', $user->id) }}" method="POST"
                                            onsubmit="return confirm('Hapus akun ini secara permanen?')">
                                            @csrf @method('DELETE')
                                            <button type="submit"
                                                class="inline-flex items-center gap-1.5 bg-red-50 text-red-600 hover:bg-red-100 px-3 py-2 rounded-xl text-[10px] font-black uppercase tracking-widest transition">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6M1 7h22M9 7V4a1 1 0 011-1h4a1 1 0 011 1v3">
                                                    </path>
                                                </svg>
                                                Hapus
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection