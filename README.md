# 📚 Bookify — Mini Library System

> Sistem Peminjaman Buku Perpustakaan Berbasis REST API & Microservices

![PHP](https://img.shields.io/badge/PHP-Laravel-red?logo=laravel)
![MySQL](https://img.shields.io/badge/Database-MySQL-blue?logo=mysql)
![Architecture](https://img.shields.io/badge/Architecture-Microservices-green)
![License](https://img.shields.io/badge/License-MIT-yellow)

---

## Deskripsi

**Bookify** adalah aplikasi backend berbasis REST API untuk mengelola sistem peminjaman buku perpustakaan mini. Sistem ini dibangun menggunakan arsitektur **microservices** sederhana dengan 3 service utama yang saling berkomunikasi melalui HTTP.

Dilengkapi dengan **frontend statis** yang dapat dijalankan secara lokal untuk mengakses semua fitur melalui browser.

---

## Arsitektur Sistem

```
┌─────────────────┐     ┌─────────────────┐     ┌─────────────────┐
│   User Service  │     │   Book Service  │     │   Loan Service  │
│   (Port 8001)   │◄────│   (Port 8002)   │◄────│   (Port 8003)   │
│                 │     │                 │     │                 │
│  - Register     │     │  - Tambah buku  │     │  - Peminjaman   │
│  - List user    │     │  - List buku    │     │  - Pengembalian │
│  - Detail user  │     │  - Update buku  │     │  - Riwayat      │
│                 │     │  - Hapus buku   │     │                 │
└────────┬────────┘     └────────┬────────┘     └────────┬────────┘
         │                       │                        │
         └───────────────────────┴────────────────────────┘
                                 │
                           MySQL Database
```

Setiap service memiliki database dan tanggung jawab masing-masing. Loan Service berkomunikasi dengan User Service dan Book Service untuk validasi dan update stok buku.

---

## Fitur

### User Service
- Register member baru
- Menampilkan daftar seluruh member
- Melihat detail user
- Validasi email unik & field wajib

### Book Service
- Tambah buku ke katalog
- Lihat daftar buku & stok
- Update data buku
- Hapus buku
- Validasi stok (tidak boleh negatif)

### Loan Service
- Proses peminjaman buku
- Proses pengembalian buku
- Riwayat peminjaman
- Validasi user & buku ke masing-masing service
- Auto-update stok buku saat peminjaman/pengembalian

---

## Tampilan Dashboard

| Halaman | Deskripsi |
|---------|-----------|
| **Dashboard** | Overview total user, buku, pinjaman aktif, dan riwayat terbaru |
| **Users** | Manajemen anggota perpustakaan |
| **Books** | Katalog buku dengan stok |
| **Loans** | Riwayat dan manajemen peminjaman |

---

## Tech Stack

| Layer | Teknologi |
|-------|-----------|
| Backend | PHP (Laravel) |
| Database | MySQL |
| Frontend | HTML, CSS, JavaScript (Static) |
| Server | Laravel Built-in Server |
| Architecture | Microservices / REST API |

---

## Cara Menjalankan

### Prerequisites
- PHP >= 8.0
- Composer
- MySQL
- Python 3 (untuk frontend server)

### Instalasi

**1. Clone repository**
```bash
git clone https://github.com/Adeliaswa/Bookify.git
cd Bookify
```

**2. Install dependencies untuk setiap service**
```bash
# User Service
cd user-service && composer install && cp .env.example .env
php artisan key:generate && php artisan migrate

# Book Service
cd ../book-service && composer install && cp .env.example .env
php artisan key:generate && php artisan migrate

# Loan Service
cd ../loan-service && composer install && cp .env.example .env
php artisan key:generate && php artisan migrate
```

**3. Konfigurasi database**

Edit file `.env` pada masing-masing service dan sesuaikan konfigurasi database:
```env
DB_DATABASE=bookify_users   # atau bookify_books / bookify_loans
DB_USERNAME=root
DB_PASSWORD=
```

### Menjalankan Aplikasi

Buka **4 terminal** secara bersamaan:

**Terminal 1 — User Service**
```bash
cd user-service
php artisan serve --port=8001
```

**Terminal 2 — Book Service**
```bash
cd book-service
php artisan serve --port=8002
```

**Terminal 3 — Loan Service**
```bash
cd loan-service
php artisan serve --port=8003
```

**Terminal 4 — Frontend**
```bash
cd frontend
python -m http.server 3000
```

**Buka browser:**
```
http://localhost:3000
```

---

## API Endpoints

### User Service — `http://localhost:8001`

| Method | Endpoint | Deskripsi |
|--------|----------|-----------|
| `POST` | `/api/users` | Register user baru |
| `GET` | `/api/users` | Daftar semua user |
| `GET` | `/api/users/{id}` | Detail user |

### Book Service — `http://localhost:8002`

| Method | Endpoint | Deskripsi |
|--------|----------|-----------|
| `GET` | `/api/books` | Daftar semua buku |
| `POST` | `/api/books` | Tambah buku baru |
| `PUT` | `/api/books/{id}` | Update data buku |
| `DELETE` | `/api/books/{id}` | Hapus buku |

### Loan Service — `http://localhost:8003`

| Method | Endpoint | Deskripsi |
|--------|----------|-----------|
| `GET` | `/api/loans` | Riwayat peminjaman |
| `POST` | `/api/loans` | Pinjam buku |
| `PUT` | `/api/loans/{id}/return` | Kembalikan buku |

---

## Tim Pengembang

| Nama | Service | Tanggung Jawab |
|------|---------|----------------|
| **Adelia** | Book Service | API manajemen buku, stok, validasi |
| **Nadhifa** | User Service | API manajemen user, validasi email |
| **Devi** | Loan Service | API peminjaman & pengembalian, integrasi antar service |

---

## Struktur Proyek

```
Bookify/
├── user-service/       # Laravel — manajemen user (port 8001)
├── book-service/       # Laravel — manajemen buku (port 8002)
├── loan-service/       # Laravel — manajemen peminjaman (port 8003)
└── frontend/           # Static HTML frontend (port 3000)
```

---

## Catatan

- Pastikan semua 3 service Laravel berjalan sebelum membuka frontend
- Loan Service memerlukan koneksi ke User Service dan Book Service untuk validasi
- Stok buku otomatis berkurang saat peminjaman dan bertambah saat pengembalian
- Status buku akan tampil **OUT** jika stok = 0

---

*© 2026 Developed for Technology Integration Systems Assignment — Group 9, FILKOM UB*
