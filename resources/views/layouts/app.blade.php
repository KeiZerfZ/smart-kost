<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SmartKost - Management System</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
        [x-cloak] { display: none !important; }
    </style>
</head>
<body class="bg-gray-50 text-gray-900 flex flex-col min-h-screen">
    
    <nav x-data="{ open: false }" class="bg-blue-600 text-white shadow-xl sticky top-0 z-50">
        <div class="container mx-auto px-4">
            <div class="flex justify-between items-center h-16">
                
                <div class="flex-shrink-0">
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
                </div>

                <div class="hidden md:flex items-center space-x-2">
                    @auth
                        <a href="{{ route($dashboardRoute) }}" class="px-3 py-2 rounded-xl text-sm font-bold transition {{ request()->routeIs('*.dashboard') ? 'bg-blue-700 shadow-inner' : 'hover:bg-blue-500' }}">
                            Dashboard
                        </a>

                        @if(auth()->user()->role == 'owner')
                            <a href="{{ route('rooms.index') }}" class="px-3 py-2 rounded-xl text-sm font-bold transition {{ request()->routeIs('rooms.*') ? 'bg-blue-700 shadow-inner' : 'hover:bg-blue-500' }}">Kamar</a>
                            <a href="{{ route('tenants.index') }}" class="px-3 py-2 rounded-xl text-sm font-bold transition {{ request()->routeIs('tenants.*') ? 'bg-blue-700 shadow-inner' : 'hover:bg-blue-500' }}">Penghuni</a>
                            <a href="{{ route('invoices.index') }}" class="px-3 py-2 rounded-xl text-sm font-bold transition {{ request()->routeIs('invoices.*') ? 'bg-blue-700 shadow-inner' : 'hover:bg-blue-500' }}">Tagihan</a>
                            <a href="{{ route('complaints.index') }}" class="px-3 py-2 rounded-xl text-sm font-bold transition {{ request()->routeIs('complaints.*') ? 'bg-blue-700 shadow-inner' : 'hover:bg-blue-500' }}">Keluhan</a>
                            <a href="{{ route('users.index') }}" class="px-3 py-2 rounded-xl text-sm font-bold transition {{ request()->routeIs('users.*') ? 'bg-blue-700 shadow-inner' : 'hover:bg-blue-500' }}">Akun</a>
                        @endif

                        <div class="flex items-center space-x-2 pl-4 border-l border-blue-400 ml-2">
                            <a href="{{ route('profile.index') }}" class="bg-blue-500/50 hover:bg-white hover:text-blue-600 px-4 py-2 rounded-xl text-xs font-black uppercase tracking-wider transition duration-300 border border-blue-400/30">
                                {{ auth()->user()->name }}
                            </a>
                            <form action="{{ route('logout') }}" method="POST" class="inline">
                                @csrf
                                <button type="submit" class="bg-red-500 hover:bg-red-600 px-4 py-2 rounded-xl text-xs font-black uppercase tracking-widest transition shadow-lg shadow-red-700/20 active:scale-95">
                                    Logout
                                </button>
                            </form>
                        </div>
                    @else
                        <a href="{{ route('login') }}" class="bg-white text-blue-600 px-6 py-2 rounded-xl font-black text-sm hover:bg-blue-50 transition shadow-lg active:scale-95">
                            MASUK
                        </a>
                    @endauth
                </div>

                <div class="md:hidden flex items-center">
                    <button @click="open = !open" class="p-2 rounded-xl hover:bg-blue-500 focus:outline-none transition">
                        <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                            <path :class="{'hidden': open, 'inline-flex': !open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                            <path :class="{'hidden': !open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>

        <div x-show="open" 
             x-cloak
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 -translate-y-4"
             x-transition:enter-end="opacity-100 translate-y-0"
             class="md:hidden bg-blue-700 border-t border-blue-500 shadow-inner">
            <div class="px-4 pt-2 pb-6 space-y-2">
                @auth
                    <a href="{{ route($dashboardRoute) }}" class="block px-4 py-3 rounded-xl text-base font-bold {{ request()->routeIs('*.dashboard') ? 'bg-blue-800' : 'hover:bg-blue-500' }}">Dashboard</a>
                    
                    @if(auth()->user()->role == 'owner')
                        <a href="{{ route('rooms.index') }}" class="block px-4 py-3 rounded-xl text-base font-bold {{ request()->routeIs('rooms.*') ? 'bg-blue-800' : 'hover:bg-blue-500' }}">Data Kamar</a>
                        <a href="{{ route('tenants.index') }}" class="block px-4 py-3 rounded-xl text-base font-bold {{ request()->routeIs('tenants.*') ? 'bg-blue-800' : 'hover:bg-blue-500' }}">Data Penghuni</a>
                        <a href="{{ route('invoices.index') }}" class="block px-4 py-3 rounded-xl text-base font-bold {{ request()->routeIs('invoices.*') ? 'bg-blue-800' : 'hover:bg-blue-500' }}">Tagihan</a>
                    @endif
                    
                    <div class="pt-4 mt-4 border-t border-blue-500">
                        <a href="{{ route('profile.index') }}" class="block px-4 py-3 rounded-xl text-sm font-black uppercase tracking-widest bg-blue-500/30 mb-2 italic">
                            Profil: {{ auth()->user()->name }}
                        </a>
                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button type="submit" class="w-full text-left px-4 py-3 rounded-xl text-base font-bold bg-red-500 shadow-lg shadow-red-900/20">
                                Logout dari Aplikasi
                            </button>
                        </form>
                    </div>
                @else
                    <a href="{{ route('login') }}" class="block text-center bg-white text-blue-600 px-6 py-4 rounded-2xl font-black text-sm">MASUK</a>
                @endauth
            </div>
        </div>
    </nav>

    <main class="container mx-auto mt-6 md:mt-8 px-4 flex-grow">
        
        @if (session('error'))
            <div x-data="{ show: true }" x-show="show" x-transition.opacity
                class="max-w-4xl mx-auto mb-6 flex items-center p-4 bg-red-50 border-l-4 border-red-500 rounded-2xl shadow-lg">
                <div class="flex-shrink-0">
                    <svg class="w-5 h-5 text-red-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path></svg>
                </div>
                <div class="ml-3 text-sm font-black text-red-800 uppercase tracking-widest">
                    {{ session('error') }}
                </div>
                <button @click="show = false" class="ml-auto text-red-400 hover:text-red-600 transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>
        @endif

        @if (session('success'))
            <div x-data="{ show: true }" x-show="show" x-transition.opacity
                class="max-w-4xl mx-auto mb-6 flex items-center p-4 bg-green-50 border-l-4 border-green-500 rounded-2xl shadow-lg">
                <div class="flex-shrink-0">
                    <svg class="w-5 h-5 text-green-600" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                </div>
                <div class="ml-3 text-sm font-black text-green-800 uppercase tracking-widest">
                    {{ session('success') }}
                </div>
                <button @click="show = false" class="ml-auto text-green-400 hover:text-green-600 transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>
        @endif

        @if ($errors->any())
            <div x-data="{ show: true }" x-show="show" x-transition.opacity
                class="max-w-4xl mx-auto mb-6 flex p-4 bg-red-50 border-l-4 border-red-500 rounded-2xl shadow-xl">
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
                <button @click="show = false" class="ml-auto text-red-400 hover:text-red-600 transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>
        @endif

        <div class="pb-20">
            @yield('content')
        </div>
    </main>

    <footer class="text-center py-10 text-gray-400 text-xs font-medium uppercase tracking-[0.2em] border-t border-gray-100 bg-white">
        &copy; 2026 SmartKost. All Rights Reserved.
    </footer>
    
</body>
</html>