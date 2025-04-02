# Instalasi Backend Satu Data AI (Laravel 11)

## Persyaratan
Pastikan sistem Anda telah menginstal:
- PHP >= 8.2
- Composer
- MySQL / PostgreSQL / SQLite
- Node.js & NPM (untuk frontend jika dibutuhkan)

## Instalasi

1. **Clone repository ini:**
   ```bash
   git clone https://github.com/MallombasiMattawang/be-satu-data-ai
   cd satu-data-ai-be
   ```

2. **Instal dependensi Laravel:**
   ```bash
   composer install
   ```

3. **Buat file `.env` dan konfigurasi database:**
   ```bash
   cp .env.example .env
   ```
   Sesuaikan file `.env` sesuai konfigurasi database Anda:
   ```ini
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=satu_data_ai
   DB_USERNAME=root
   DB_PASSWORD=
   ```

4. **Generate key aplikasi:**
   ```bash
   php artisan key:generate
   ```

5. **Jalankan migrasi dan seeder (jika ada):**
   ```bash
   php artisan migrate --seed
   ```

6. **Jalankan server lokal:**
   ```bash
   php artisan serve
   ```
   Akses backend di `http://127.0.0.1:8000`

## API Dokumentasi
Gunakan Postman atau Insomnia untuk mengakses endpoint API. 

## Deployment
Gunakan `php artisan config:cache` dan sesuaikan `.env` saat deployment ke server produksi.

---
Dikembangkan dengan ❤️ menggunakan Laravel 11.
