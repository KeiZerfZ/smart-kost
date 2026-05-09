<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SmartKost - Management System</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="bg-gray-50 text-gray-900">

    <nav class="bg-blue-600 p-4 text-white shadow-xl sticky top-0 z-50">
        <div class="container mx-auto flex justify-between items-center">
            
            @auth
                @php
                    $dashboardRoute = auth()->user()->role == 'owner' ? 'admin.dashboard' : 'tenant.dashboard';
                @endphp
                <a href="{{ route($dashboardRoute) }}" class="text-2xl font-black tracking-tighter italic hover:text-blue-200 transition duration-300">
                    SmartKost.
                </a>
            @else
                <a href="/" class="text-2xl font-black tracking-tighter italic">SmartKost.</a>
            @endauth

            <div class="flex items-center space-x-2 lg:space-x-4">
                @auth
                    <a href="{{ route($dashboardRoute) }}" class="px-3 py-2 rounded-xl text-sm font-bold transition duration-200 {{ request()->routeIs('*.dashboard') ? 'bg-blue-700 shadow-inner' : 'hover:bg-blue-500' }}">
                        Dashboard
                    </a>

                    @if(auth()->user()->role == 'owner')
                        <a href="{{ route('rooms.index') }}" class="px-3 py-2 rounded-xl text-sm font-bold transition duration-200 {{ request()->routeIs('rooms.*') ? 'bg-blue-700 shadow-inner' : 'hover:bg-blue-500' }}">Kamar</a>
                        <a href="{{ route('tenants.index') }}" class="px-3 py-2 rounded-xl text-sm font-bold transition duration-200 {{ request()->routeIs('tenants.*') ? 'bg-blue-700 shadow-inner' : 'hover:bg-blue-500' }}">Penghuni</a>
                        <a href="{{ route('invoices.index') }}" class="px-3 py-2 rounded-xl text-sm font-bold transition duration-200 {{ request()->routeIs('invoices.*') ? 'bg-blue-700 shadow-inner' : 'hover:bg-blue-500' }}">Tagihan</a>
                        <a href="{{ route('complaints.index') }}" class="px-3 py-2 rounded-xl text-sm font-bold transition duration-200 {{ request()->routeIs('complaints.*') ? 'bg-blue-700 shadow-inner' : 'hover:bg-blue-500' }}">Keluhan</a>
                        <a href="{{ route('users.index') }}" class="px-3 py-2 rounded-xl text-sm font-bold transition duration-200 {{ request()->routeIs('users.*') ? 'bg-blue-700 shadow-inner' : 'hover:bg-blue-500' }}">Akun User</a>
                    @endif

                    <div class="flex items-center space-x-2 pl-4 border-l border-blue-400 ml-2">
                        <a href="{{ route('profile.index') }}" class="bg-blue-500/50 hover:bg-white hover:text-blue-600 px-4 py-2 rounded-xl text-xs font-black uppercase tracking-wider transition duration-300 shadow-sm border border-blue-400/30">
                            {{ auth()->user()->name }}
                        </a>

                        <form action="{{ route('logout') }}" method="POST" class="inline">
                            @csrf
                            <button type="submit" class="bg-red-500 hover:bg-red-600 px-4 py-2 rounded-xl text-xs font-black uppercase tracking-widest transition duration-300 shadow-lg shadow-red-700/20 active:scale-95">
                                Logout
                            </button>
                        </form>
                    </div>
                @else
                    <a href="{{ route('login') }}" class="bg-white text-blue-600 px-6 py-2 rounded-xl font-black text-sm hover:bg-blue-50 transition shadow-lg shadow-blue-700/20 active:scale-95">
                        MASUK
                    </a>
                @endauth
            </div>
        </div>
    </nav>

    <main class="container mx-auto mt-8 p-4">
        
        @if ($errors->any())
            <div id="alert-validation" class="max-w-4xl mx-auto mb-6 flex p-4 bg-red-50 border-l-4 border-red-500 rounded-2xl shadow-xl shadow-red-100 animate-bounce">
                <div class="flex-shrink-0 mt-0.5">
                    <svg class="w-5 h-5 text-red-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path></svg>
                </div>
                <div class="ml-3">
                    <h3 class="text-sm font-black text-red-800 uppercase tracking-widest">Input Bermasalah!</h3>
                    <ul class="mt-1 text-xs text-red-700 list-disc list-inside font-semibold leading-relaxed">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                <button onclick="document.getElementById('alert-validation').remove()" class="ml-auto text-red-400 hover:text-red-600 transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>
        @endif

        @if (session('success'))
            <div id="alert-success" class="max-w-4xl mx-auto mb-6 flex items-center p-4 bg-green-50 border-l-4 border-green-500 rounded-2xl shadow-lg shadow-green-100">
                <div class="flex-shrink-0">
                    <svg class="w-5 h-5 text-green-600" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                </div>
                <div class="ml-3 text-sm font-black text-green-800 uppercase tracking-widest">
                    {{ session('success') }}
                </div>
                <button onclick="document.getElementById('alert-success').remove()" class="ml-auto text-green-400 hover:text-green-600 transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>
        @endif

        @if (session('error'))
            <div id="alert-error" class="max-w-4xl mx-auto mb-6 flex items-center p-4 bg-orange-50 border-l-4 border-orange-500 rounded-2xl shadow-lg shadow-orange-100">
                <div class="flex-shrink-0">
                    <svg class="w-5 h-5 text-orange-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path></svg>
                </div>
                <div class="ml-3 text-sm font-black text-orange-800 uppercase tracking-widest">
                    {{ session('error') }}
                </div>
                <button onclick="document.getElementById('alert-error').remove()" class="ml-auto text-orange-400 hover:text-orange-600 transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>
        @endif

        <div class="pb-20">
            @yield('content')
        </div>
    </main>

    <footer class="text-center py-10 text-gray-400 text-xs font-medium uppercase tracking-[0.2em]">
        &copy; 2026 SmartKost. All Rights Reserved.
    </footer>

</body>
</html>