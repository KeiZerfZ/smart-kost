<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SmartKost - Pendaftaran Berhasil</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;800&display=swap" rel="stylesheet">
    <style> body { font-family: 'Inter', sans-serif; } </style>
</head>
<body class="bg-gray-50 flex items-center justify-center min-h-screen p-4">
    <div class="bg-white p-8 md:p-10 rounded-[2.5rem] shadow-2xl shadow-blue-100/50 border border-gray-100 max-w-md w-full text-center">
        
        <div class="inline-flex items-center justify-center w-20 h-20 bg-blue-50 text-blue-600 rounded-full mb-6 animate-pulse">
            <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
        </div>

        <h1 class="text-3xl font-black text-gray-800 tracking-tighter italic">Pendaftaran Berhasil.</h1>
        <p class="text-red-500 font-black text-[10px] uppercase tracking-widest mt-1">Status Akun: Ditangguhkan (On Hold)</p>

        <div class="my-8 text-sm text-gray-500 font-medium leading-relaxed space-y-4 text-left p-5 bg-gray-50 rounded-2xl border border-gray-100">
            <p>
                Data formulir dan berkas identitas KTP Anda telah berhasil disimpan ke dalam sistem. Saat ini akun Anda sedang menanti proses peninjauan berkas oleh Administrator.
            </p>
            <p class="text-blue-600 font-bold">
                ⚠️ PERINGATAN WAJIB:
            </p>
            <p class="text-xs text-gray-600">
                Agar Anda dapat menerima pesan konfirmasi verifikasi akun serta tautan unduhan kuitansi kuitansi PDF resmi, Anda <b>wajib</b> menekan tombol aktivasi bot di bawah ini dan mengirimkan perintah <b>/start</b> sekarang.
            </p>
        </div>

        <div class="space-y-3">
            <a href="https://t.me/Manajer_SmartKost_Bot" target="_blank" 
               class="flex items-center justify-center space-x-2 bg-blue-600 text-white font-black py-4 rounded-2xl text-xs uppercase tracking-widest shadow-xl shadow-blue-200 hover:bg-blue-700 transition duration-300 active:scale-95">
                <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 24 24"><path d="M12 0C5.373 0 0 5.373 0 12s5.373 12 12 12 12-5.373 12-12S18.627 0 12 0zm5.894 8.221l-1.97 9.28c-.145.658-.537.818-1.084.508l-3-2.21-1.446 1.394c-.14 Congressional.14-.26.26-.54.26l.196-2.82 5.128-4.63c.223-.198-.048-.307-.34-.114l-6.335 3.99-2.73-.85c-.595-.185-.606-.595.124-.88l10.666-4.112c.49-.185.92.11.73.984z"/></svg>
                <span>Aktivasi Telegram Bot</span>
            </a>

            <a href="{{ route('login') }}" 
               class="flex items-center justify-center bg-gray-100 hover:bg-gray-200 text-gray-700 font-black py-4 rounded-2xl text-xs uppercase tracking-widest transition duration-300 active:scale-95">
                <span>Kembali ke Login</span>
            </a>
        </div>

        <p class="text-[9px] text-gray-400 font-bold uppercase tracking-wider mt-6">
            * Notifikasi kelulusan akun akan dikirim otomatis ke Telegram setelah Admin menyetujui berkas Anda.
        </p>
    </div>
</body>
</html>