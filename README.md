# E-Pokir Backend

Backend API untuk sistem **E-Pokir DPRD** — aplikasi manajemen aspirasi anggota dewan dan dokumen Pokok-Pokok Pikiran (Pokir). Dibangun dengan Laravel 13 dan terintegrasi dengan SSO Pusat untuk autentikasi terpusat.

---

## Daftar Isi

- [Teknologi](#teknologi)
- [Persyaratan Sistem](#persyaratan-sistem)
- [Instalasi](#instalasi)
- [Konfigurasi Environment](#konfigurasi-environment)
- [Menjalankan Aplikasi](#menjalankan-aplikasi)
- [Autentikasi & SSO](#autentikasi--sso)
- [API Endpoints](#api-endpoints)
- [Role & Hak Akses](#role--hak-akses)
- [Struktur Proyek](#struktur-proyek)
- [Dokumentasi API](#dokumentasi-api)
- [Testing](#testing)

---

## Teknologi

| Komponen | Teknologi |
|----------|-----------|
| Runtime | PHP 8.3+ |
| Framework | Laravel 13.8 |
| Autentikasi API | Laravel Passport 13 (OAuth2) |
| Database | MySQL |
| Dokumentasi API | L5-Swagger (OpenAPI 3.0) |
| Testing | Pest 4.7 |
| Build Tool | Vite 8 + Tailwind CSS 4 |

---

## Persyaratan Sistem

- PHP >= 8.3 dengan ekstensi: `pdo`, `mbstring`, `openssl`, `tokenizer`, `xml`, `ctype`, `json`
- Composer >= 2
- MySQL >= 8.0
- Node.js >= 18 (untuk build asset)

---

## Instalasi

### 1. Clone & Install Dependensi

```bash
git clone epokir-backend
cd epokir-backend
```

Jalankan setup otomatis (install composer, buat `.env`, generate key, migrasi, install npm, build asset):

```bash
composer setup
```

Atau lakukan secara manual:

```bash
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate
npm install && npm run build
```

### 2. Setup Laravel Passport

```bash
php artisan passport:keys
```

Jika menggunakan key dari environment variable (direkomendasikan untuk production), isi `PASSPORT_PRIVATE_KEY` dan `PASSPORT_PUBLIC_KEY` di `.env` 

### 3. Buat Personal Access Client

```bash
php artisan passport:client --personal
```

---

## Konfigurasi Environment

Salin file contoh lalu sesuaikan:

```bash
cp .env.example .env
```

### Konfigurasi Wajib

```env
# Aplikasi
APP_NAME="E-Pokir DPRD"
APP_URL=http://localhost:8001
APP_KEY=                        # Di-generate otomatis via php artisan key:generate

# Database
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=epokir_backend
DB_USERNAME=root
DB_PASSWORD=

# SSO Pusat — wajib diisi
SSO_PUSAT_URL=http://127.0.0.1:8001/api
SSO_EXCHANGE_SECRET=Qm8Kp2Xv7LzR4HsNc9TdWf6BgYu3JeAa5MnPk8RtVx2Lc7Ds
SSO_ALLOWED_IPS=127.0.0.1

L5_SWAGGER_GENERATE_ALWAYS=true
L5_SWAGGER_CONST_HOST=http://localhost:8000
```

### Passport Keys (Production)

Untuk production, simpan key Passport di environment variable agar tidak perlu file `.pem`:

```env
PASSPORT_PRIVATE_KEY="-----BEGIN RSA PRIVATE KEY-----
...isi private key...
-----END RSA PRIVATE KEY-----"

PASSPORT_PUBLIC_KEY="-----BEGIN PUBLIC KEY-----
...isi public key...
-----END PUBLIC KEY-----"
```

### Konfigurasi Opsional

```env
# Cache & Session (default: database)
CACHE_STORE=database
SESSION_DRIVER=database
QUEUE_CONNECTION=database

# Log
LOG_CHANNEL=stack
LOG_LEVEL=debug           # Ganti ke "error" di production

# Mail (opsional, untuk notifikasi)
MAIL_MAILER=smtp
MAIL_HOST=smtp.example.com
MAIL_PORT=587
MAIL_USERNAME=
MAIL_PASSWORD=
MAIL_FROM_ADDRESS="noreply@example.com"
MAIL_FROM_NAME="${APP_NAME}"
```

---

## Menjalankan Aplikasi

### Development

Jalankan server Laravel, queue listener, dan Vite secara bersamaan:

```bash
composer dev
```

Atau jalankan terpisah:

```bash
php artisan serve --port=8001   # API server
php artisan queue:listen         # Queue worker
npm run dev                      # Vite HMR
```

### Production

```bash
composer install --no-dev --optimize-autoloader
php artisan config:cache
php artisan route:cache
php artisan migrate --force
npm run build
```

---

## Autentikasi & SSO

Sistem menggunakan **Authorization Code Flow** dari SSO Pusat. Berikut alurnya:

```
Pengguna  →  SSO Pusat  →  redirect ke Frontend (code + tahun)
Frontend  →  GET /api/v1/auth/sso/callback?code=xxx  →  Backend
Backend   →  exchange code ke SSO Pusat (server-to-server)
Backend   →  upsert user lokal → buat token Passport → return token
```

### Endpoint Callback

```
GET /api/v1/auth/sso/callback?code={code}
```

Backend melakukan exchange ke SSO Pusat:

- **URL:** `POST {SSO_PUSAT_URL}/v1/auth/sso/exchange`
- **Header:** `X-SSO-Secret: {SSO_EXCHANGE_SECRET}`
- **Body:** `{ "code": "...", "app": "epokir" }`
- **Response:** `{ "sso_token": "...", "user": { ... } }`

Token Passport lokal yang dikembalikan berlaku **8 jam**. Semua request berikutnya wajib menyertakan:

```
Authorization: Bearer {token}
```

### Parameter `tahun`

`tahun` dikirim dari SSO Pusat via URL redirect dan disimpan di **localStorage** oleh frontend (Nuxt). Untuk memfilter data berdasarkan tahun, frontend menyertakan `?tahun=2026` sebagai query parameter pada setiap request daftar aspirasi.

---

## API Endpoints

Base URL: `/api/v1`

### Autentikasi

| Method | Endpoint | Keterangan | Auth |
|--------|----------|------------|------|
| GET | `/auth/sso/callback` | SSO callback, tukar code → token | — |
| GET | `/auth/profile` | Profil user aktif | ✓ |
| POST | `/auth/logout` | Logout & revoke token | ✓ |

### Aspirasi

| Method | Endpoint | Keterangan |
|--------|----------|------------|
| GET | `/aspirasi` | Daftar aspirasi (filter: `opd_id`, `dapil_id`, `source`, `is_complete`, `tahun`, `search`, `per_page`) |
| POST | `/aspirasi` | Buat aspirasi baru |
| GET | `/aspirasi/{id}` | Detail aspirasi |
| PUT | `/aspirasi/{id}` | Perbarui aspirasi |
| DELETE | `/aspirasi/{id}` | Hapus aspirasi (soft delete) |

**Field wajib saat create:** `title`, `tanggal` (format: `YYYY-MM-DD`), `source`

**Nilai `source`:** `reses` · `tatap_muka` · `surat` · `lainnya`

### Pokir

| Method | Endpoint | Keterangan |
|--------|----------|------------|
| GET | `/pokir` | Daftar Pokir (filter: `status`, `opd_id`, `dapil_id`, `search`, `per_page`) |
| POST | `/pokir` | Buat Pokir baru (status: `draft`) |
| GET | `/pokir/{id}` | Detail Pokir |
| PUT | `/pokir/{id}` | Perbarui Pokir |
| DELETE | `/pokir/{id}` | Hapus Pokir |
| POST | `/pokir/{id}/submit` | Ajukan ke Setwan |
| POST | `/pokir/{id}/verify` | Verifikasi (Setwan/Admin) |
| POST | `/pokir/{id}/request-revision` | Minta revisi dengan catatan |
| POST | `/pokir/{id}/finalize` | Finalisasi Pokir |
| POST | `/pokir/{id}/aspirasi` | Tambah aspirasi ke Pokir |
| DELETE | `/pokir/{id}/aspirasi/{aspirasiId}` | Hapus aspirasi dari Pokir |

**Alur status Pokir:**

```
draft → submitted → verified → finalized
                 ↘ revision_needed → (kembali ke draft)
```

### Master Data

Semua endpoint master data menggunakan REST standar (`index`, `store`, `show`, `update`, `destroy`):

| Resource | Endpoint | Keterangan |
|----------|----------|------------|
| Dapil | `/dapils` | Daerah pemilihan |
| OPD | `/opds` | Organisasi Perangkat Daerah |
| Kecamatan | `/kecamatans` | Data kecamatan |
| Desa | `/desas` | Data desa/kelurahan |
| Kamus Pokir | `/kamus-pokir` | Template Pokir (hierarkis) |

---

## Role & Hak Akses

| Role | Keterangan | Batasan |
|------|------------|---------|
| `dewan` | Anggota dewan | Hanya bisa melihat & mengelola data miliknya sendiri |
| `setwan` | Sekretariat dewan | Bisa verifikasi dan minta revisi Pokir |
| `admin` | Administrator | Akses penuh ke semua data |

---

## Struktur Proyek

```
epokir-backend/
├── app/
│   ├── Helpers/               # ApiResponse helper
│   ├── Http/
│   │   ├── Controllers/Api/   # AuthController, PokirController, AspirasiController, dll.
│   │   └── Requests/          # Validasi request (Store/Update per resource)
│   ├── Models/                # Eloquent models (User, Pokir, Aspirasi, dll.)
│   ├── Services/              # Business logic
│   │   ├── AuthService.php    # SSO callback + manajemen token lokal
│   │   ├── SsoPusatService.php
│   │   ├── PokirService.php   # CRUD + alur workflow Pokir
│   │   ├── AspirasiService.php
│   │   └── ...
│   └── Swagger/               # Anotasi OpenAPI (schema & docs per resource)
├── config/
│   ├── services.php           # Konfigurasi SSO Pusat (url, secret, allowed IPs)
│   └── passport.php           # Konfigurasi OAuth2
├── database/
│   ├── migrations/            # Skema database
│   ├── factories/             # Factory untuk testing
│   └── seeders/               # Seeder data awal
├── routes/
│   └── api.php                # Semua route API (prefix: /api/v1)
├── tests/
│   ├── Feature/
│   └── Unit/
├── .env.example
└── composer.json
```

---

## Dokumentasi API

Swagger UI tersedia setelah aplikasi berjalan:

```
http://localhost:8001/api/documentation
```

Generate ulang dokumentasi setelah ada perubahan anotasi:

```bash
php artisan l5-swagger:generate
```

---

## Testing

```bash
# Jalankan semua test
composer test

# Atau langsung via Pest
./vendor/bin/pest

# Test file tertentu
./vendor/bin/pest tests/Feature/AspirasiTest.php
```

Untuk menggunakan SQLite in-memory agar test lebih cepat, tambahkan di `phpunit.xml`:

```xml
<env name="DB_CONNECTION" value="sqlite"/>
<env name="DB_DATABASE" value=":memory:"/>
```
