# MABIPRO — Manajemen Bisnis Properti

Sistem manajemen properti berbasis web untuk memantau penjualan unit, progres pembangunan, legalitas, dan pembayaran.

---

## Tech Stack

- **Backend** : Laravel 11 (PHP)
- **Database** : MySQL 8
- **Frontend** : Blade + Vite
- **Auth**     : Laravel built-in session authentication

---

## Prasyarat

Pastikan sudah terinstall di komputermu:

| Tool | Versi minimum |
|------|--------------|
| PHP  | 8.2+         |
| Composer | terbaru  |
| MySQL | 8.0+       |
| Node.js | 18+     |
| npm  | terbaru      |

---

## Setup Awal (Clone Pertama Kali)

### 1. Install dependencies

```bash
composer install
npm install
```

### 2. Buat file `.env`

```bash
cp .env.example .env
php artisan key:generate
```

### 3. Konfigurasi database di `.env`

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=mabipro
DB_USERNAME=root
DB_PASSWORD=        # sesuaikan dengan password MySQL kamu
```

> Buat dulu database-nya di MySQL: `CREATE DATABASE mabipro;`

### 4. Jalankan migrasi + seeder

```bash
php artisan migrate --seed
```

Ini akan membuat semua tabel dan mengisi data awal:
- **2 Blok**: Blok A (5 unit), Blok B (3 unit)
- **Akun admin default**: `admin@mabipro.test` / `password`

---

## Menjalankan Aplikasi

Buka **dua terminal** secara bersamaan:

**Terminal 1 — Laravel server:**
```bash
php artisan serve
```

**Terminal 2 — Vite (asset compiler):**
```bash
npm run dev
```

Akses di browser: **http://127.0.0.1:8000**

---

## Struktur Database

```
blocks              → Data blok perumahan (Blok A, Blok B, dst.)
units               → Data unit per blok (nomor, status jual, progres, KPR, dll.)
legal_documents     → Dokumen legalitas per unit
progress_photos     → Foto progres pembangunan per unit
users               → Akun pengguna aplikasi
```

### Role Pengguna

| Role | Akses |
|------|-------|
| `Admin` | Full access, manajemen user |
| `Marketing` | Data penjualan & pembayaran |
| `Produksi` | Progres pembangunan & foto |
| `Legalitas` | Dokumen legalitas |

---

## Perintah Berguna

```bash
# Reset database + isi ulang data awal
php artisan migrate:fresh --seed

# Buat akun admin saja (tanpa reset)
php artisan db:seed --class=AdminSeeder

# Lihat semua route
php artisan route:list

# Clear cache
php artisan optimize:clear
```

---

## Branching

- `main` → branch utama / production-ready
- Buat branch sendiri saat mengerjakan fitur: `git checkout -b nama-fitur`
- Jangan langsung push ke `main`

---

## Catatan Penting

- File `.env` **tidak di-commit** ke git — masing-masing developer setup sendiri.
- Folder `storage/` dan `bootstrap/cache/` sudah di-gitignore.
- Setelah pull perubahan dari teman, selalu jalankan:
  ```bash
  composer install
  php artisan migrate
  npm install
  ```
