# 🏠 SmartKost - Management System

**SmartKost** adalah platform manajemen rumah kost digital yang dirancang untuk mempermudah interaksi antara pemilik kost (Owner) dan penghuni (Tenant). Proyek ini dibangun menggunakan **Laravel** dengan fokus pada otomatisasi tagihan, sistem keluhan terintegrasi, dan kemudahan pembayaran.

---

## 🚀 Fitur Utama

### 🛠️ Mode Owner (Admin)

* **Dashboard Statistik:** Ringkasan total kamar, penghuni aktif, keluhan pending, dan pendapatan bulanan.
* **Manajemen Kamar:** Pengaturan tipe kamar (Reguler/VIP/Suite), harga, dan status ketersediaan.
* **Manajemen Penghuni:** Pendaftaran penghuni baru dan otomatisasi pembuatan tagihan bulan pertama.
* **Otomatisasi Invoicing:** Konfirmasi pembayaran cash dan pembuatan tagihan manual jika diperlukan.
* **Sistem Keluhan:** Monitoring dan respon cepat terhadap laporan kerusakan dari penghuni.
* **Manajemen Akun:** Kontrol penuh terhadap akun user, termasuk fitur *Emergency Password Reset*.

### 👤 Mode Tenant (Penghuni)

* **Personal Dashboard:** Informasi detail kamar yang disewa secara real-time.
* **Pembayaran QRIS:** Simulasi pembayaran tagihan secara mandiri via scan QRIS.
* **Lapor Keluhan:** Form pengaduan fasilitas kamar yang langsung terhubung ke Owner.
* **Cetak Bukti Sah:** Fitur ekspor bukti pembayaran lunas ke format **PDF**.

---

## 🛠️ Tech Stack

* **Framework:** Laravel 11
* **Styling:** Tailwind CSS (via CDN/Vite)
* **Database:** MySQL / MariaDB
* **Library:** `barryvdh/laravel-dompdf` (Export PDF)
* **Icons & UI:** Heroicons & Custom CSS 3 Animations

---

## 📦 Instalasi Proyek

Ikuti langkah berikut untuk menjalankan proyek di lingkungan lokal:

1. **Clone Repository**
```bash
git clone https://github.com/username/smart-kost.git
cd smart-kost

```


2. **Install Dependencies**
```bash
composer install
npm install && npm run build

```


3. **Konfigurasi Environment**
* Salin file `.env.example` menjadi `.env`.
* Atur koneksi database di bagian `DB_DATABASE`, `DB_USERNAME`, dan `DB_PASSWORD`.


4. **Generate Key & Migrate**
```bash
php artisan key:generate
php artisan migrate:fresh --seed

```


5. **Jalankan Server**
```bash
php artisan serve

```



---

## 🔑 Akun Demo (Seeder)

Gunakan akun berikut untuk mencoba sistem setelah menjalankan perintah `--seed`:

* **Owner/Admin:** `admin@gmail.com` / `123`

---