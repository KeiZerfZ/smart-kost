# 🏠 SmartKost - Management System (Build 2026.05.20)

**SmartKost** adalah platform manajemen operasional rumah kost digital berbasis web yang mengintegrasikan sistem kendali internal pemilik kost (*Owner/Admin*) dan penghuni (*Tenant*). Sistem ini berfokus pada otomatisasi penerbitan tagihan bulanan, pengerasan keamanan data, dan mekanisme notifikasi *real-time* menggunakan **Telegram Bot API** sebagai pengganti infrastruktur email/WhatsApp konvensional guna mengoptimalkan biaya operasional dan efisiensi pengiriman data.

---

## 🛠️ Tech Stack & Spesifikasi Sistem

* **Backend Framework:** Laravel 13.x
* **Runtime Environment:** PHP ^8.3 (Konfigurasi Produksi: PHP 8.3.30)
* **Database Engine:** MySQL / MariaDB / SQLite
* **Frontend Stack:** Tailwind CSS & Alpine.js (Mendukung fungsionalitas *Global Dark Mode*)
* **Library Eksternal:** `barryvdh/laravel-dompdf` (Penerbitan kuitansi resmi format PDF)
* **Integrasi Pihak Ketiga:** Telegram Bot API (Webhook eksternal & *Task Scheduling*)

---

## 🚀 Peta Fungsionalitas Sistem

### 🛠️ Modul Owner (Admin Dashboard)

* **Dasbor Statistik & Arus Kas:** Visualisasi grafik pendapatan bulanan (*chart revenue* via Chart.js), total kamar, penghuni aktif, laporan kerusakan fasilitas, dan lencana notifikasi (*badge counter*) permintaan akun baru.
* **Manajemen Kamar (CRUD):** Pengelolaan unit kamar, nomor unit, klasifikasi tipe (Reguler/VIP/Suite), penetapan harga sewa, dan otomatisasi pembaruan status ketersediaan.
* **Gerbang Persetujuan (Approval Gate):** Panel peninjauan berkas foto KTP milik calon penghuni yang mendaftar mandiri untuk disetujui (*Approve*) atau ditolak (*Reject*).
* **Kontrol Keuangan Manual:** Fasilitas penerbitan tagihan manual dan konfirmasi pelunasan tunai (*cash*).
* **Complaint Tracking System:** Penanganan laporan kendala fasilitas dari tenant disertai pengubahan status kerja (*Pending*, *Process*, *Resolved*).
* **Security & Account Management:** Pengelolaan akun pengguna, isolasi hak akses (*middleware role authorization*), dan fitur reset kata sandi darurat.

### 👤 Modul Tenant (Penghuni Dashboard)

* **Registrasi Mandiri (Self Sign-Up):** Formulir pendaftaran publik luar untuk memesan kamar kosong dengan kewajiban unggah dokumen identitas (KTP) dan pengisian Telegram Chat ID.
* **Aktivasi Sesi Akun (On-Hold State):** Halaman penangguhan akun pasca-registrasi yang memandu pengguna untuk mengaktifkan Telegram Bot sebelum masuk ke sistem.
* **Personal Dashboard:** Informasi detail unit sewa, visualisasi status keuangan kamar, dan transparansi estimasi tanggal penerbitan tagihan berikutnya secara presisi.
* **Simulasi Pembayaran QRIS Otomatis:** Sistem pembayaran nontunai menggunakan kode QR dinamis yang terhubung ke kamera pemindai. Status tagihan berubah menjadi lunas secara *real-time* tanpa intervensi tombol konfirmasi manual.
* **Sistem Lupa Kata Sandi Terintegrasi:** Fitur pengaturan ulang kata sandi melalui *Password Broker* Laravel yang mengirimkan tautan enkripsi unik langsung ke akun Telegram personal pengguna.
* **Unduh Kuitansi Digital:** Fitur ekspor bukti pembayaran transaksi lunas ke format PDF resmi.

---

## 🔒 Otomatisasi & Pengerasan Keamanan (Security Hardening)

1. **Telegram Task Scheduler:** Eksekusi perintah otomatis (*Cron Job*) untuk pembuatan invoice bulanan secara periodik berdasarkan tanggal masuk tenant, sekaligus memicu pengiriman pesan pengingat jatuh tempo secara berkala pada kurun waktu H-3, H-1, dan Hari-H.
2. **Rate Limiting (Throttle Middleware):** Pembatasan laju permintaan akses pada rute publik registrasi mandiri (`throttle:10,1`) guna meminimalkan risiko serangan *Brute Force* dan penyalahgunaan pengiriman data berkas ke server.
3. **URL Hardening & File Protection:** Isolasi direktori penyimpanan berkas foto KTP tenant menggunakan proteksi enkripsi *endpoint* rute untuk mencegah kebocoran data dari akses ilegal pengguna luar.
4. **Fault Tolerance Log:** Pencatatan otomatis setiap kegagalan pengiriman data API Telegram ke dalam tabel log audit (`telegram_delivery_logs`) untuk menjaga stabilitas sirkulasi backend aplikasi.

---

## 📦 Panduan Instalasi Lokal

Ikuti instruksi di bawah ini untuk mengonfigurasi proyek SmartKost di lingkungan lokal Anda:

1. **Kloning Repositori**
```bash
git clone https://github.com/username/smart-kost.git
cd smart-kost

```


2. **Instalasi Dependensi Dependen**
```bash
composer install
npm install && npm run build

```


3. **Konfigurasi Berkas Lingkungan (.env)**
Salin berkas template lingkungan dan sesuaikan variabel di dalamnya:
```bash
cp .env.example .env

```


Pastikan Anda mengonfigurasi instrumen zona waktu dan token akses bot Telegram Anda:
```env
APP_TIMEZONE=Asia/Jakarta

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=smart_kost
DB_USERNAME=root
DB_PASSWORD=

TELEGRAM_BOT_TOKEN=masukkan_token_rahasia_bot_anda_disini

```


4. **Inisialisasi Database & Enkripsi Kunci**
```bash
php artisan key:generate
php artisan migrate:fresh --seed

```


*Catatan: Proses pemuatan ulang autoload akan otomatis mengeksekusi pembuatan link direktori (`php artisan storage:link --force`) secara aman.*
5. **Menjalankan Server Lokal**
```bash
php artisan serve

```



---

## 🚀 Panduan Konfigurasi Produksi (Railway Deployment)

1. **Start Command:** Kosongkan kolom konfigurasi *Start Command* pada tab Settings Railway Anda. Sistem Nixpacks secara bawaan akan membaca instruksi web server PHP-FPM dan Nginx secara optimal.
2. **Automated Storage Link:** Script otomatisasi tautan direktori penyimpanan telah dikunci di dalam struktur `"post-autoload-dump"` pada file `composer.json` menggunakan parameter `--force` untuk menghindari bug gambar rusak (*broken image*) pasca-build ulang kontainer:
```json
"post-autoload-dump": [
    "Illuminate\\Foundation\\ComposerScripts::postAutoloadDump",
    "@php artisan package:discover --ansi",
    "@php artisan storage:link --force"
]

```


3. **Persistent Volume:** Karena kontainer Railway menerapkan sistem berkas sementara (*ephemeral storage*), buat satu buah komponen **Volume** baru pada dashboard Railway Anda dan pasang (*mount*) ke direktori `/app/storage/app/public` agar seluruh unggahan file KTP tenant tersimpan secara permanen.
4. **Set Webhook Telegram:** Daftarkan URL publik server produksi Anda ke API Telegram melalui peramban dengan format berikut:
```text
https://api.telegram.org/bot<TOKEN_BOT_ANDA>/setWebhook?url=https://<DOMAIN_RAILWAY_ANDA>/api/telegram/webhook

```



---

## 🔑 Akun Akses Demo (Database Seeder)

Gunakan kredensial pengujian berikut setelah Anda mengeksekusi perintah seeder database:

* **Owner / Administrator Account:**
* **Email:** `admin@gmail.com`
* **Password:** `123`