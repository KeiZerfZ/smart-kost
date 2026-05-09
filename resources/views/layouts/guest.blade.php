<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SmartKost - Autentikasi</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="bg-gray-50 text-gray-900 flex flex-col min-h-screen">
    <main class="flex-grow flex items-center justify-center p-4">
        @yield('content')
    </main>
    <footer class="text-center py-6 text-gray-400 text-[10px] font-black uppercase tracking-widest">
        &copy; 2026 SmartKost. All Rights Reserved.
    </footer>
</body>
</html>