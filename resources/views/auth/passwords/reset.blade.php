@extends('layouts.app')

@section('content')
<div class="min-h-[80vh] flex items-center justify-center">
    <div class="bg-white p-10 rounded-[2.5rem] shadow-2xl shadow-blue-100 border border-gray-50 w-full max-w-md text-center">
        <h2 class="text-3xl font-black text-gray-800 mb-6 tracking-tighter">Password Baru</h2>

        <form method="POST" action="{{ route('password.update') }}" class="space-y-5 text-left">
            @csrf
            <input type="hidden" name="token" value="{{ $token }}">

            <div>
                <label class="block text-xs font-black text-gray-400 uppercase tracking-widest mb-1 ml-1">Email</label>
                <input type="email" name="email" value="{{ $email ?? old('email') }}" class="w-full p-4 bg-gray-100 border border-transparent rounded-2xl outline-none" readonly>
            </div>

            <div>
                <label class="block text-xs font-black text-gray-400 uppercase tracking-widest mb-1 ml-1">Password Baru</label>
                <input type="password" name="password" class="w-full p-4 bg-gray-50 border border-gray-100 rounded-2xl focus:ring-4 focus:ring-blue-100 outline-none" required autofocus>
            </div>

            <div>
                <label class="block text-xs font-black text-gray-400 uppercase tracking-widest mb-1 ml-1">Konfirmasi Password</label>
                <input type="password" name="password_confirmation" class="w-full p-4 bg-gray-50 border border-gray-100 rounded-2xl focus:ring-4 focus:ring-blue-100 outline-none" required>
            </div>

            <button type="submit" class="w-full bg-blue-600 text-white font-black py-5 rounded-3xl shadow-xl shadow-blue-200 hover:bg-blue-700 transition">
                UPDATE PASSWORD
            </button>
        </form>
    </div>
</div>
@endsection