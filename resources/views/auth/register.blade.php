<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SmartKost - Sign Up</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;800&display=swap" rel="stylesheet">
    <style> body { font-family: 'Inter', sans-serif; } </style>
</head>
<body class="bg-gray-50 flex items-center justify-center min-h-screen p-4">
    <div class="bg-white p-8 md:p-10 rounded-[2.5rem] shadow-xl border border-gray-100 max-w-2xl w-full">
        <div class="mb-8 text-center">
            <h1 class="text-3xl font-black text-gray-800 tracking-tighter italic">Join SmartKost.</h1>
            <p class="text-gray-500 font-medium text-sm mt-1">Lengkapi data diri Anda untuk mengajukan pendaftaran hunian.</p>
        </div>

        @if ($errors->any())
            <div class="mb-6 p-4 bg-red-50 border-l-4 border-red-500 rounded-xl text-xs font-bold text-red-700 uppercase">
                @foreach ($errors->all() as $error) <p>{{ $error }}</p> @endforeach
            </div>
        @endif

        <form action="{{ route('register.store') }}" method="POST" enctype="multipart/form-data" class="space-y-5">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div class="space-y-1">
                    <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Nama Lengkap</label>
                    <input type="text" name="name" class="w-full p-4 bg-gray-50 border border-gray-200 rounded-2xl outline-none font-semibold text-sm" value="{{ old('name') }}" required>
                </div>

                <div class="space-y-1">
                    <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Email</label>
                    <input type="email" name="email" class="w-full p-4 bg-gray-50 border border-gray-200 rounded-2xl outline-none font-semibold text-sm" value="{{ old('email') }}" required>
                </div>

                <div class="space-y-1">
                    <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Nomor Kontak (WhatsApp)</label>
                    <input type="text" name="phone" class="w-full p-4 bg-gray-50 border border-gray-200 rounded-2xl outline-none font-semibold text-sm" value="{{ old('phone') }}" required>
                </div>

                <div class="space-y-1">
                    <div class="flex justify-between items-center px-1">
                        <label class="block text-[10px] font-black text-blue-500 uppercase tracking-widest">Telegram Chat ID</label>
                        <a href="https://t.me/userinfobot" target="_blank" class="text-[9px] text-blue-600 font-bold hover:underline">🔍 Cari ID Saya</a>
                    </div>
                    <input type="text" name="telegram_chat_id" placeholder="Klik 'Cari ID Saya' untuk mendapatkan ID" class="w-full p-4 bg-blue-50/30 border border-blue-200 rounded-2xl outline-none font-bold text-sm text-blue-700" value="{{ old('telegram_chat_id') }}" required>
                </div>

                <div class="space-y-1">
                    <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Kata Sandi</label>
                    <input type="password" name="password" class="w-full p-4 bg-gray-50 border border-gray-200 rounded-2xl outline-none font-semibold text-sm" required>
                </div>

                <div class="space-y-1">
                    <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Konfirmasi Kata Sandi</label>
                    <input type="password" name="password_confirmation" class="w-full p-4 bg-gray-50 border border-gray-200 rounded-2xl outline-none font-semibold text-sm" required>
                </div>

                <div class="md:col-span-2 space-y-1">
                    <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Pilih Kamar Kost</label>
                    <select name="room_id" class="w-full p-4 bg-gray-50 border border-gray-200 rounded-2xl outline-none font-bold text-sm text-blue-600 appearance-none" required>
                        <option value="" disabled selected>-- Pilih Nomor Kamar Tersedia --</option>
                        @foreach($rooms as $r)
                            <option value="{{ $r->id }}">Kamar {{ $r->room_number }} ({{ $r->type }}) - Rp {{ number_format($r->price) }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="p-5 bg-blue-50/50 border border-dashed border-blue-200 rounded-2xl text-center">
                <label class="block text-[10px] font-black text-blue-500 uppercase tracking-widest mb-2">Unggah Foto KTP Resmi</label>
                <input type="file" name="id_card_photo" class="w-full text-xs text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-xs file:font-black file:bg-blue-600 file:text-white hover:file:bg-blue-700 cursor-pointer" required>
            </div>

            <button type="submit" class="w-full bg-blue-600 text-white py-4 rounded-2xl font-black text-xs uppercase tracking-widest shadow-lg hover:bg-blue-700 transition duration-300">
                Kirim Pengajuan Pendaftaran
            </button>

            <p class="text-center text-xs text-gray-500 font-medium">
                Sudah memiliki akun? <a href="{{ route('login') }}" class="text-blue-600 font-bold hover:underline">Masuk</a>
            </p>
        </form>
    </div>
</body>
</html>