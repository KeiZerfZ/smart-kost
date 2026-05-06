<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SmartKost - Management System</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <nav class="bg-blue-600 p-4 text-white shadow-lg">
        <div class="container mx-auto flex justify-between items-center">
            <a href="#" class="text-xl font-bold">SmartKost</a>
            <div class="space-x-4">
                <a href="{{ route('rooms.index') }}" class="hover:underline">Kamar</a>
                <a href="{{ route('tenants.index') }}" class="hover:underline">Penghuni</a>
                <a href="{{ route('invoices.index') }}" class="hover:underline">Tagihan</a>
                <form action="/logout" method="POST" class="inline">
                    @csrf
                    <button type="submit" class="bg-red-500 px-3 py-1 rounded">Logout</button>
                </form>
            </div>
        </div>
    </nav>

    <main class="container mx-auto mt-8 p-4">
        @if(session('success'))
            <div class="bg-green-200 text-green-800 p-3 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif

        @yield('content')
    </main>
</body>
</html>