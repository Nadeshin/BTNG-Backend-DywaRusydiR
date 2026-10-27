# BTNG Backend — API Showroom Mobil

RESTful API untuk manajemen data showroom mobil, dibangun dengan **Laravel 12** dan **MySQL** sebagai Tugas Akhir BTNG Kelas Backend.

- **Live API** : <https://app-4b0ebed8-d424-4dd4-86b3-a7310555fc1d.cleverapps.io/api/mobil>
- **Dokumentasi interaktif** : <https://app-4b0ebed8-d424-4dd4-86b3-a7310555fc1d.cleverapps.io/docs/api>

> Deploy live di **Clever Cloud** (PHP 8.3 + MySQL eksternal), di-deploy lewat `clever deploy`.

---

## Daftar Isi

- [Fitur](#fitur)
- [Teknologi](#teknologi)
- [Endpoint API](#endpoint-api)
- [Contoh Request & Response](#contoh-request--response)
- [Instalasi Lokal](#instalasi-lokal)
- [Dokumentasi API](#dokumentasi-api)
- [Struktur Proyek](#struktur-proyek)
- [Koleksi Postman](#koleksi-postman)
- [Deployment](#deployment)
- [Identitas](#identitas)

---

## Fitur

**Wajib**

- ✅ CRUD lengkap 5 endpoint (`index`, `store`, `show`, `update`, `destroy`)
- ✅ Migration skema tabel `mobils`
- ✅ Respons JSON seragam lewat API Resource (`message` + `data`)
- ✅ Validasi request dengan `$request->validate()` dan pesan Bahasa Indonesia
- ✅ HTTP status code semantik: `200`, `201`, `404`, `422`, `500`
- ✅ Dokumentasi interaktif Scramble di `/docs/api` (bisa **Try it out**)
- ✅ Seeder 10 data dummy (`php artisan db:seed`)
- ✅ Deployment live di Clever Cloud + database MySQL eksternal

**Tambahan (bonus)**

- 🔎 Pencarian: `?search=avanza`
- 🧮 Penyaringan: `?brand=Toyota&status=tersedia&bahan_bakar=Bensin`
- ↕️ Pengurutan: `?sort_by=harga&sort_dir=asc`
- 📄 Pagination: `?per_page=5&page=2` (maksimal 100)
- 🛡️ Semua request `/api/*` dipaksa membalas JSON (tidak pernah redirect 302)
- 📬 Koleksi Postman siap pakai dengan 25 assertion

---

## Teknologi

| Komponen | Versi |
|---|---|
| PHP | 8.3 |
| Laravel | 12.x |
| Database | MySQL 8 |
| Dokumentasi | dedoc/scramble |
| Deployment | Clever Cloud (live) / Docker (Railway, alternatif) |

---

## Endpoint API

Base URL: `{{baseUrl}}/api`

| Method | Endpoint | Deskripsi | Status Sukses |
|---|---|---|---|
| `GET` | `/api/mobil` | Daftar mobil (search, filter, sort, pagination) | `200 OK` |
| `GET` | `/api/mobil/{id}` | Detail satu mobil | `200 OK` |
| `POST` | `/api/mobil` | Tambah mobil baru | `201 Created` |
| `PUT` / `PATCH` | `/api/mobil/{id}` | Perbarui data mobil | `200 OK` |
| `DELETE` | `/api/mobil/{id}` | Hapus mobil | `200 OK` |

### Atribut Mobil

| Kolom | Tipe | Aturan Validasi |
|---|---|---|
| `nama` | string | wajib, maks 150 karakter |
| `brand` | string | wajib, maks 100 karakter |
| `harga` | integer | wajib, ≥ 0 (Rupiah) |
| `tahun` | integer | wajib, 1900 s/d tahun depan |
| `transmisi` | enum | wajib, `Manual` \| `Matic` \| `CVT` |
| `bahan_bakar` | enum | wajib, `Bensin` \| `Diesel` \| `Hybrid` \| `Listrik` |
| `status` | enum | opsional, `tersedia` \| `sold` (default `tersedia`) |
| `foto` | string | opsional, harus URL valid, maks 2048 karakter |

### Query Parameter (GET /api/mobil)

| Parameter | Contoh | Keterangan |
|---|---|---|
| `search` | `?search=avanza` | Cari pada `nama` dan `brand` |
| `brand` | `?brand=Toyota` | Filter brand persis |
| `status` | `?status=tersedia` | Filter status |
| `bahan_bakar` | `?bahan_bakar=Listrik` | Filter bahan bakar |
| `sort_by` | `?sort_by=harga` | `id`, `nama`, `brand`, `harga`, `tahun`, `created_at` |
| `sort_dir` | `?sort_dir=asc` | `asc` atau `desc` (default `desc`) |
| `per_page` | `?per_page=5` | 1–100 (default 10) |
| `page` | `?page=2` | Halaman ke- |

---

## Contoh Request & Response

### 1. Daftar mobil — `GET /api/mobil`

```json
{
  "data": [
    {
      "id": 1,
      "nama": "Avanza 1.5 G CVT",
      "brand": "Toyota",
      "harga": 275000000,
      "harga_format": "Rp 275.000.000",
      "tahun": 2024,
      "transmisi": "CVT",
      "bahan_bakar": "Bensin",
      "status": "tersedia",
      "foto": "https://images.unsplash.com/photo-1549399542-7e3f8b79c341",
      "created_at": "2026-10-27T00:54:14+00:00",
      "updated_at": "2026-10-27T00:54:14+00:00"
    }
  ],
  "links": { "first": "...", "last": "...", "prev": null, "next": null },
  "meta": { "current_page": 1, "per_page": 10, "total": 10 },
  "message": "Daftar mobil berhasil diambil"
}
```

### 2. Tambah mobil — `POST /api/mobil` → `201 Created`

Request body:

```json
{
  "nama": "Civic RS Turbo",
  "brand": "Honda",
  "harga": 615000000,
  "tahun": 2024,
  "transmisi": "CVT",
  "bahan_bakar": "Bensin",
  "status": "tersedia",
  "foto": "https://images.unsplash.com/photo-1552519507-da3b142c6e3d"
}
```

Response:

```json
{
  "data": { "id": 11, "nama": "Civic RS Turbo", "brand": "Honda", "...": "..." },
  "message": "Data mobil berhasil ditambahkan"
}
```

### 3. Gagal validasi — `POST /api/mobil` → `422 Unprocessable Content`

```json
{
  "message": "Kolom nama mobil wajib diisi. (and 1 more error)",
  "errors": {
    "nama": ["Kolom nama mobil wajib diisi."],
    "harga": ["Kolom harga harus berupa angka."]
  }
}
```

### 4. Data tidak ditemukan — `GET /api/mobil/9999` → `404 Not Found`

```json
{
  "message": "Data tidak ditemukan",
  "data": null
}
```

### 5. Hapus mobil — `DELETE /api/mobil/1` → `200 OK`

```json
{
  "message": "Data mobil berhasil dihapus",
  "data": null
}
```

---

## Instalasi Lokal

Prasyarat: **PHP 8.2+**, **Composer**, **MySQL** (Laragon/XAMPP).

```bash
# 1. Clone repository
git clone https://github.com/username/BTNG-Backend-NamaLengkap.git
cd BTNG-Backend-NamaLengkap

# 2. Install dependency
composer install

# 3. Siapkan environment
cp .env.example .env
php artisan key:generate

# 4. Buat database MySQL, misal: showroom_mobil
#    lalu sesuaikan DB_DATABASE, DB_USERNAME, DB_PASSWORD di file .env

# 5. Migrate + isi 10 data dummy
php artisan migrate --seed

# 6. Jalankan server
php artisan serve
```

Aplikasi berjalan di `http://127.0.0.1:8000`.

Cek cepat:

```bash
curl http://127.0.0.1:8000/api/mobil
```

### Perintah Seeder

```bash
php artisan db:seed              # isi data dummy (aman dijalankan berulang)
php artisan migrate:fresh --seed # reset database + isi ulang data dummy
```

---

## Dokumentasi API

Setelah server berjalan, buka:

**http://127.0.0.1:8000/docs/api**

Dokumentasi dihasilkan otomatis oleh **Scramble** dari route, aturan validasi, dan API Resource. Semua endpoint dapat diuji langsung dengan tombol **Try it out**.

Spesifikasi OpenAPI mentah tersedia di `/docs/api.json`.

Akses dokumentasi di production diatur oleh Gate `viewApiDocs` pada `app/Providers/AppServiceProvider.php`.

---

## Struktur Proyek

```
app/
├── Http/
│   ├── Controllers/Api/MobilController.php   # 5 method CRUD + validasi
│   ├── Middleware/ForceJsonResponse.php      # paksa balasan JSON untuk /api/*
│   └── Resources/MobilResource.php           # bentuk data JSON
├── Models/Mobil.php                          # $fillable, casts, scope search/filter
└── Providers/AppServiceProvider.php          # Gate viewApiDocs

bootstrap/app.php                             # 404 API -> amplop JSON

database/
├── factories/MobilFactory.php
├── migrations/..._create_mobils_table.php
└── seeders/
    ├── DatabaseSeeder.php
    └── MobilSeeder.php                       # 10 data dummy

routes/api.php                                # apiResource mobil
config/scramble.php                           # pengaturan dokumentasi
postman/                                      # koleksi Postman + environment
```

---

## Koleksi Postman

File: `postman/BTNG-Backend-Showroom-Mobil.postman_collection.json`

Cara pakai:

1. Buka Postman → **Import** → pilih file di atas.
2. Sesuaikan variabel `baseUrl` (`http://127.0.0.1:8000` atau URL live).
3. Jalankan **Collection Runner** dari atas ke bawah.

Koleksi berisi 8 request dengan **25 assertion** yang menguji: status code, amplop `message`/`data`, atribut lengkap, filter, pagination, validasi 422, 404, hingga verifikasi data benar-benar terhapus.

Menjalankan lewat CLI:

```bash
postman collection run postman/BTNG-Backend-Showroom-Mobil.postman_collection.json
```

---

## Deployment

Deploy live memakai **Clever Cloud** (PHP 8.3) + database MySQL eksternal.
Konfigurasi **Railway** (Docker) tetap disertakan sebagai alternatif.

### A. Clever Cloud (dipakai untuk live)

Deploy dilakukan dengan **Clever CLI**, tanpa integrasi GitHub:

```bash
clever login
clever link <APP_ID>
clever deploy
```

Environment variable yang wajib diisi di console Clever Cloud:

| Variable | Nilai |
|---|---|
| `APP_ENV` | `production` |
| `APP_DEBUG` | `false` |
| `APP_KEY` | hasil `php artisan key:generate --show` |
| `APP_URL` | `https://[nama-app].cleverapps.io` |
| `APP_TIMEZONE` | `Asia/Jakarta` |
| `LOG_CHANNEL` | `stderr` |
| `DB_CONNECTION` | `mysql` |
| `DB_HOST` / `DB_PORT` | host & port database |
| `DB_DATABASE` / `DB_USERNAME` / `DB_PASSWORD` | kredensial database |
| `CC_PHP_VERSION` | `8.3` |
| `CC_WEBROOT` | `/public` |
| `CC_POST_BUILD_HOOK` | `./.clever.cloud/post-build.sh` |
| `CC_PRE_RUN_HOOK` | `./.clever.cloud/pre-run.sh` |

- `post-build.sh` → `composer install` produksi + cache config/view.
- `pre-run.sh` → `php artisan migrate --force` + `php artisan db:seed --force` setiap aplikasi start, jadi database otomatis terisi 10 data dummy.
- `composer.json` mengunci `config.platform.php` ke **8.3.0** supaya lock file selalu kompatibel dengan runtime Clever Cloud.

### B. Railway (alternatif)

Proyek sudah menyertakan `Dockerfile` (PHP 8.3 + Apache, document root `/public`) dan `docker/entrypoint.sh` yang otomatis menjalankan `migrate` + `db:seed` setiap container start.

1. Push proyek ke GitHub (repository **Public**).
2. Buka [railway.app](https://railway.app) → **New Project** → **Deploy from GitHub repo** → pilih repository ini.
   Railway otomatis mendeteksi `Dockerfile`.
3. **Settings → Networking → Generate Domain** untuk mendapat URL publik.
4. Isi **Variables**:

   | Variable | Nilai |
   |---|---|
   | `APP_ENV` | `production` |
   | `APP_DEBUG` | `false` |
   | `APP_KEY` | hasil `php artisan key:generate --show` |
   | `APP_URL` | `https://[domain-railway]` |
   | `APP_TIMEZONE` | `Asia/Jakarta` |
   | `LOG_CHANNEL` | `stderr` |
   | `DB_CONNECTION` | `mysql` |
   | `DB_HOST` / `DB_PORT` | host & port database |
   | `DB_DATABASE` / `DB_USERNAME` / `DB_PASSWORD` | kredensial database |

5. **Deploy** ulang (Redeploy) setelah variabel terisi.
6. Verifikasi: `https://[domain-railway]/api/mobil` dan `https://[domain-railway]/docs/api`.

---

## Identitas

- **Nama** : Dywa Rusydi R
- **NIM** : A11.2026.16980
- **Kelas** : BTNG — Website Backend
