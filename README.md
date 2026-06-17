# 🏫 Backend Sekolah API

Backend Laravel REST API untuk Aplikasi Mobile Sekolah. Menyediakan API untuk siswa, guru, admin, dan superadmin.

## 🛠 Tech Stack

- **Framework:** Laravel 12
- **Bahasa:** PHP 8+
- **Database:** PostgreSQL
- **Auth:** Laravel Sanctum (API) + Laravel Auth (Web Admin)
- **ORM:** Eloquent

## 📦 Fitur

- 🔐 Authentication (Login/Logout)
- 📊 Dashboard (Siswa & Guru)
- 📅 Jadwal Pelajaran
- 📝 Nilai / Rapor
- 📋 Absensi
- 📢 Pengumuman
- 📰 Artikel
- 📚 E-Book
- 🔔 Notifikasi
- 📌 Tugas / PR
- ⭐ Poin Siswa
- 📄 Izin Siswa
- 📝 Catatan Guru
- 👤 Profil Siswa

---

## 🚀 Instalasi

### Prasyarat

- PHP 8.2+
- Composer
- PostgreSQL
- Node.js & NPM (opsional, untuk asset)

### Langkah Setup

```bash
# 1. Clone repository
git clone https://github.com/zainalsalamun/backend_sekolah_api.git
cd backend_sekolah_api

# 2. Install dependencies
composer install

# 3. Copy environment file
cp .env.example .env

# 4. Generate application key
php artisan key:generate

# 5. Konfigurasi database di .env
# DB_CONNECTION=pgsql
# DB_HOST=127.0.0.1
# DB_PORT=5432
# DB_DATABASE=db_sekolah
# DB_USERNAME=postgres
# DB_PASSWORD=

# 6. Buat database
psql -U postgres -c "CREATE DATABASE db_sekolah;"

# 7. Jalankan migrasi
php artisan migrate

# 8. Jalankan seeder (data dummy untuk testing)
php artisan db:seed

# 9. Jalankan server
php artisan serve --port=8000
```

### Jalankan seeder ulang

```bash
php artisan migrate:fresh --seed
```

---

## 🔑 Data Login (Default)

### Web Admin Panel (`http://localhost:8000/admin/login`)

| Role | Email | Password |
|------|-------|----------|
| **Admin** | `admin@sekolah.sch.id` | `admin123` |
| **Superadmin** | `superadmin@sekolah.sch.id` | `superadmin123` |

> ⚠️ Admin web panel login menggunakan **email**, bukan username.

### Mobile App API (Login via `/api/login`)

| Role | Username | Password |
|------|----------|----------|
| **Siswa** | `zainal` | `123` |
| **Siswa** | `siswa1` | `123` |
| **Guru** | `guru1` | `123` |
| **Admin** | `admin` | `admin123` |
| **Superadmin** | `superadmin` | `superadmin123` |

---

## 📡 API Endpoints

### Base URL

```
http://localhost:8000/api
```

### Authentication

| Method | Endpoint | Description |
|--------|----------|-------------|
| POST | `/api/login` | Login (username + password) |
| POST | `/api/logout` | Logout (butuh token) |

### Siswa

| Method | Endpoint | Description |
|--------|----------|-------------|
| GET | `/api/siswa/{id}/dashboard` | Dashboard siswa |
| GET | `/api/siswa/{id}/jadwal` | Jadwal pelajaran |
| GET | `/api/siswa/{id}/nilai` | Daftar nilai |
| GET | `/api/siswa/{id}/absensi` | Data absensi |
| GET | `/api/siswa/{id}/absensi/rekap` | Rekap absensi |
| GET | `/api/siswa/{id}/tugas` | Daftar tugas |
| GET | `/api/siswa/{id}/poin` | Poin siswa |
| GET | `/api/siswa/{id}/izin` | Data izin |
| POST | `/api/siswa/{id}/izin` | Ajukan izin |
| GET | `/api/siswa/{id}/profil` | Profil lengkap |
| PUT | `/api/siswa/{id}/profil` | Update profil |

### Guru

| Method | Endpoint | Description |
|--------|----------|-------------|
| GET | `/api/guru/{id}/dashboard` | Dashboard guru |
| GET | `/api/guru/{id}/tugas` | Kelola tugas |
| GET | `/api/guru/{id}/siswa` | Data siswa |
| GET | `/api/guru/{id}/rekap-nilai` | Rekap nilai |
| GET | `/api/guru/{id}/catatan-siswa` | Catatan siswa |

### Public (Tanpa Login)

| Method | Endpoint | Description |
|--------|----------|-------------|
| GET | `/api/pengumuman` | Daftar pengumuman |
| GET | `/api/pengumuman/{id}` | Detail pengumuman |
| GET | `/api/articles` | Daftar artikel |
| GET | `/api/articles/{id}` | Detail artikel |
| GET | `/api/ebooks` | Daftar e-book |
| GET | `/api/ebooks/{id}` | Detail e-book |

### User

| Method | Endpoint | Description |
|--------|----------|-------------|
| GET | `/api/users/{id}/notifikasi` | Notifikasi user |
| POST | `/api/change-password` | Ganti password |

---

## 🗂 Struktur Database

- `users` — Akun siswa, guru, admin, superadmin
- `siswa` — Data siswa
- `guru` — Data guru
- `jadwals` — Jadwal pelajaran
- `nilais` — Data nilai
- `absensis` — Data absensi
- `artikels` — Artikel
- `ebooks` — E-Book
- `pengumumans` — Pengumuman
- `notifikasis` — Notifikasi
- `tugases` — Tugas dari guru
- `tugaskus` — Tugas siswa
- `poin_siswas` — Poin siswa
- `izin_siswas` — Izin siswa
- `catatan_siswas` — Catatan siswa dari guru

---

## 📱 Flutter Frontend

Frontend Flutter ada di repository terpisah:

```bash
git clone https://github.com/zainalsalamun/mobile_sekolah_apps.git
cd mobile_sekolah_apps
flutter pub get
flutter run
```

### Konfigurasi API di Flutter

- **Android Emulator:** `http://10.0.2.2:8000/api`
- **iOS Simulator:** `http://localhost:8000/api`
- **Real Device:** `http://[IP_KOMPUTER]:8000/api`

Konfigurasi ada di `lib/core/service/api_service.dart`.

---

## 👨‍💻 Development

```bash
# Jalankan server dalam mode development
php artisan serve --port=8000

# Jalankan seeder ulang
php artisan migrate:fresh --seed

# Cek routes
php artisan route:list

# Cek database
php artisan db:show
```

---

## 📄 License

MIT License.
