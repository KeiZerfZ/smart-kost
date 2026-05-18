<!DOCTYPE html>
<html lang="id" x-data="{ darkMode: localStorage.getItem('dark') === 'true' }" :class="{ 'dark': darkMode }">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SmartKost - Management System</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    <script>
        tailwind.config = {
            darkMode: 'class',
        }
    </script>

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; transition: background-color 0.3s ease, color 0.3s ease; }
        [x-cloak] { display: none !important; }
    </style>
</head>
<body class="bg-gray-50 dark:bg-gray-950 text-gray-900 dark:text-gray-100 flex flex-col min-h-screen">
    
    <nav x-data="{ open: false }" class="bg-blue-600 dark:bg-slate-900 text-white shadow-xl sticky top-0 z-50 transition-colors duration-300">
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
                        <a href="{{ route($dashboardRoute) }}" class="px-3 py-2 rounded-xl text-sm font-bold transition {{ request()->routeIs('*.dashboard') ? 'bg-blue-700 dark:bg-blue-600 shadow-inner' : 'hover:bg-blue-500' }}">
                            Dashboard
                        </a>

                        @if(auth()->user()->role == 'owner')
                            <a href="{{ route('rooms.index') }}" class="px-3 py-2 rounded-xl text-sm font-bold transition {{ request()->routeIs('rooms.*') ? 'bg-blue-700 dark:bg-blue-600 shadow-inner' : 'hover:bg-blue-500' }}">Kamar</a>
                            <a href="{{ route('tenants.index') }}" class="px-3 py-2 rounded-xl text-sm font-bold transition {{ request()->routeIs('tenants.*') ? 'bg-blue-700 dark:bg-blue-600 shadow-inner' : 'hover:bg-blue-500' }}">Penghuni</a>
                            <a href="{{ route('invoices.index') }}" class="px-3 py-2 rounded-xl text-sm font-bold transition {{ request()->routeIs('invoices.*') ? 'bg-blue-700 dark:bg-blue-600 shadow-inner' : 'hover:bg-blue-500' }}">Tagihan</a>
                            <a href="{{ route('complaints.index') }}" class="px-3 py-2 rounded-xl text-sm font-bold transition {{ request()->routeIs('complaints.*') ? 'bg-blue-700 dark:bg-blue-600 shadow-inner' : 'hover:bg-blue-500' }}">Keluhan</a>
                            <a href="{{ route('users.index') }}" class="px-3 py-2 rounded-xl text-sm font-bold transition {{ request()->routeIs('users.*') ? 'bg-blue-700 dark:bg-blue-600 shadow-inner' : 'hover:bg-blue-500' }}">Akun</a>
                        @endif

                        <div class="flex items-center space-x-2 pl-4 border-l border-blue-400 dark:border-slate-700 ml-2">
                            <button @click="darkMode = !darkMode; localStorage.setItem('dark', darkMode)" 
                                    class="p-2 rounded-xl bg-blue-500/50 dark:bg-slate-800 hover:bg-white dark:hover:bg-yellow-400 hover:text-blue-600 dark:hover:text-slate-900 transition duration-300 border border-blue-400/30 dark:border-slate-700">
                                <template x-if="!darkMode">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"></path></svg>
                                </template>
                                <template x-if="darkMode">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364-6.364l-.707.707M6.343 17.657l-.707.707M16.243 17.657l.707.707M6.343 6.343l.707-.707M14.5 12a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path></svg>
                                </template>
                            </button>

                            <a href="{{ route('profile.index') }}" class="bg-blue-500/50 dark:bg-slate-800 hover:bg-white dark:hover:bg-blue-400 hover:text-blue-600 dark:hover:text-slate-900 px-4 py-2 rounded-xl text-xs font-black uppercase tracking-wider transition duration-300 border border-blue-400/30 dark:border-slate-700">
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

                <div class="md:hidden flex items-center space-x-2">
                    <button @click="darkMode = !darkMode; localStorage.setItem('dark', darkMode)" 
                            class="p-2 rounded-xl bg-blue-500/50 dark:bg-slate-800 focus:outline-none transition">
                        <template x-if="!darkMode">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"></path></svg>
                        </template>
                        <template x-if="darkMode">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364-6.364l-.707.707M6.343 17.657l-.707.707M16.243 17.657l.707.707M6.343 6.343l.707-.707M14.5 12a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path></svg>
                        </template>
                    </button>
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
             class="md:hidden bg-blue-700 dark:bg-slate-800 border-t border-blue-500 dark:border-slate-700 shadow-inner">
            <div class="px-4 pt-2 pb-6 space-y-1">
                @auth
                    <a href="{{ route($dashboardRoute) }}" class="block px-4 py-3 rounded-xl text-base font-bold {{ request()->routeIs('*.dashboard') ? 'bg-blue-800' : 'hover:bg-blue-500' }}">Dashboard</a>
                    
                    @if(auth()->user()->role == 'owner')
                        <a href="{{ route('rooms.index') }}" class="block px-4 py-3 rounded-xl text-base font-bold {{ request()->routeIs('rooms.*') ? 'bg-blue-800' : 'hover:bg-blue-500' }}">Kamar</a>
                        <a href="{{ route('tenants.index') }}" class="block px-4 py-3 rounded-xl text-base font-bold {{ request()->routeIs('tenants.*') ? 'bg-blue-800' : 'hover:bg-blue-500' }}">Penghuni</a>
                        <a href="{{ route('invoices.index') }}" class="block px-4 py-3 rounded-xl text-base font-bold {{ request()->routeIs('invoices.*') ? 'bg-blue-800' : 'hover:bg-blue-500' }}">Tagihan</a>
                        <a href="{{ route('complaints.index') }}" class="block px-4 py-3 rounded-xl text-base font-bold {{ request()->routeIs('complaints.*') ? 'bg-blue-800' : 'hover:bg-blue-500' }}">Keluhan</a>
                        <a href="{{ route('users.index') }}" class="block px-4 py-3 rounded-xl text-base font-bold {{ request()->routeIs('users.*') ? 'bg-blue-800' : 'hover:bg-blue-500' }}">Manajemen Akun</a>
                    @endif

                    <div class="mt-4 pt-4 border-t border-blue-500/50 dark:border-slate-700/50">
                        <div class="px-4 py-2 mb-2">
                            <p class="text-xs font-black uppercase tracking-widest text-blue-300 dark:text-slate-400">Akun Saya</p>
                        </div>
                        <a href="{{ route('profile.index') }}" class="block px-4 py-3 rounded-xl text-base font-bold {{ request()->routeIs('profile.*') ? 'bg-blue-800' : 'hover:bg-blue-500' }}">
                            Profil ({{ auth()->user()->name }})
                        </a>
                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button type="submit" class="w-full text-left px-4 py-3 rounded-xl text-base font-black bg-red-500 mt-2 shadow-lg active:scale-95 transition">
                                LOGOUT
                            </button>
                        </form>
                    </div>
                @else
                    <a href="{{ route('login') }}" class="block px-4 py-4 rounded-xl text-center text-base font-black bg-white text-blue-600 shadow-lg">MASUK</a>
                @endauth
            </div>
        </div>
    </nav>

    <main class="container mx-auto mt-6 md:mt-8 px-4 flex-grow">
        
        @foreach (['error' => 'red', 'success' => 'green'] as $msg => $color)
            @if (session($msg))
                <div x-data="{ show: true }" x-show="show" x-transition.opacity
                    class="max-w-4xl mx-auto mb-6 flex items-center p-4 bg-{{$color}}-50 dark:bg-{{$color}}-900/20 border-l-4 border-{{$color}}-500 rounded-2xl shadow-lg border-y border-{{$color}}-200/50">
                    <div class="ml-3 text-sm font-black text-{{$color}}-800 dark:text-{{$color}}-200 uppercase tracking-widest">
                        {{ session($msg) }}
                    </div>
                    <button @click="show = false" class="ml-auto text-{{$color}}-400 transition">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>
            @endif
        @endforeach

        <div class="pb-20">
            @yield('content')
        </div>
    </main>

    <footer class="text-center py-10 text-gray-400 dark:text-gray-500 text-xs font-medium uppercase tracking-[0.2em] border-t border-gray-100 dark:border-slate-800 bg-white dark:bg-slate-900 transition-colors duration-300">
        &copy; 2026 SmartKost. All Rights Reserved.
    </footer>
    
</body>
</html>