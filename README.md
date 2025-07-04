# Panduan Setup Proyek Laravel

## Persyaratan Sistem

Sebelum memulai, pastikan sistem Anda memiliki:

- **PHP 8.2** atau versi yang lebih baru
- **Composer** (untuk mengelola dependensi PHP)
- **Node.js** versi 18 atau lebih baru (kalau menggunakan laragon bisa langsung install)
- **NPM** (biasanya sudah terinstal bersama Node.js)
- **Laragon** (sudah terinstal dan berjalan)

## Langkah-langkah Setup

### 1. Persiapan Proyek

1. **Download/Clone proyek** ini ke folder
```bash
git clone https://github.com/EricSijabat1/aquafresh-market-toba.git
cd aquafresh-market-toba
```
2. **Buka Command Prompt/Terminal** di folder proyek
3. **Pastikan Laragon sudah berjalan** (Apache & MySQL)

### 2. Install Dependencies PHP

Jalankan perintah berikut untuk menginstall semua package PHP yang diperlukan:

```bash
composer install
```

### 3. Install Dependencies JavaScript

Jalankan perintah berikut untuk menginstall semua package JavaScript:

```bash
npm install
```

### 4. Setup Environment

1. **Salin file environment:**
   ```bash
   copy .env.example .env
   ```

2. **Generate aplikasi key:**
   ```bash
   php artisan key:generate
   ```

3. **Edit file `.env`** sesuai konfigurasi database Anda:
   ```
    DB_CONNECTION=mysql
    DB_HOST=127.0.0.1
    DB_PORT=3306
    DB_DATABASE=aquafresh_market
    DB_USERNAME=root
    DB_PASSWORD=
   ```

### 5. Setup Database

1. **Buat database baru** melalui phpMyAdmin atau HeidiSQL
2. **Jalankan migrasi database:**
   ```bash
   php artisan migrate --seed
   ```

### 6. Build Assets

Untuk development, jalankan:
```bash
npm run dev
```

Untuk production, jalankan:
```bash
npm run build
```

## Menjalankan Aplikasi

### Cara Manual

Jika ingin menjalankan satu per satu:

1. **Jalankan Laravel server:**
   ```bash
   php artisan serve
   ```


## Akses Aplikasi

- **Website:** http://localhost:8000
- **Database:** Melalui phpMyAdmin di http://localhost/phpmyadmin

## Fitur Utama Proyek

Proyek ini menggunakan:

- **Laravel 12** - Framework PHP modern
- **Laravel Breeze** - Sistem autentikasi sederhana
- **Livewire 3** - Komponen dynamic tanpa JavaScript kompleks
- **Tailwind CSS** - Framework CSS utility-first
- **Alpine.js** - JavaScript framework ringan
- **Intervention Image** - Library untuk manipulasi gambar

## Perintah Berguna

### Masalah Umum

1. **"Class not found"** - Jalankan `composer dump-autoload`
2. **"Permission denied"** - Pastikan folder storage dan bootstrap/cache dapat ditulis
3. **"Mix file not found"** - Jalankan `npm run dev` atau `npm run build`
4. **Database connection failed** - Periksa konfigurasi database di file `.env`

### Reset Proyek

Jika ada masalah, reset dengan:

```bash
composer install
npm install
php artisan key:generate
php artisan migrate:fresh
npm run dev
```

## Bantuan

Jika mengalami kesulitan:

1. **Pastikan semua persyaratan sistem terpenuhi**
2. **Cek apakah Laragon berjalan dengan baik**
3. **Periksa log error** di `storage/logs/laravel.log`
4. **Gunakan `php artisan tinker`** untuk debugging

---

**Catatan:** Proyek ini menggunakan Laravel 12 dengan PHP 8.2. Pastikan versi PHP dan Composer Anda kompatibel.
