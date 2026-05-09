@extends('layouts.app')

@section('content')
<div class="bg-white p-6 rounded-lg shadow-sm border">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold">Manajemen User</h2>
    </div>

    <form action="{{ route('users.store') }}" method="POST" class="mb-8 grid grid-cols-1 md:grid-cols-5 gap-4 p-4 bg-gray-50 rounded">
        @csrf
        <input type="text" name="name" placeholder="Nama" class="p-2 border rounded" required>
        <input type="email" name="email" placeholder="Email" class="p-2 border rounded" required>
        <input type="password" name="password" placeholder="Password" class="p-2 border rounded" required>
        <select name="role" class="p-2 border rounded">
            <option value="tenant">Penghuni (Tenant)</option>
            <option value="owner">Pemilik (Owner)</option>
        </select>
        <button type="submit" class="bg-blue-600 text-white rounded p-2">Tambah User</button>
    </form>

    <table class="w-full text-left border-collapse">
        <thead>
            <tr class="border-b bg-gray-100">
                <th class="p-3">Nama</th>
                <th class="p-3">Email</th>
                <th class="p-3">Role</th>
                <th class="p-3">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($users as $u)
            <tr class="border-b hover:bg-gray-50">
                <td class="p-3">{{ $u->name }}</td>
                <td class="p-3">{{ $u->email }}</td>
                <td class="p-3">
                    <span class="px-2 py-1 rounded text-xs {{ $u->role == 'owner' ? 'bg-purple-100 text-purple-700' : 'bg-green-100 text-green-700' }}">
                        {{ strtoupper($u->role) }}
                    </span>
                </td>
                <td class="p-3">
                    <form action="{{ route('users.destroy', $u->id) }}" method="POST" onsubmit="return confirm('Yakin hapus user ini?')">
                        @csrf @method('DELETE')
                        <button type="submit" class="text-red-500 hover:underline">Hapus</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection