# 🇮🇩 Indonesia Location for Laravel

**edi-prasetyo/indonesia-location** adalah package Laravel untuk menyediakan data wilayah administratif Indonesia (Provinsi, Kabupaten/Kota, Kecamatan, Desa) lengkap dengan **relasi database** dan **model Eloquent**.

Package ini dirancang untuk:

* Laravel **11 ke atas**
* Import data wilayah Indonesia dari file JSON
* Relasi internal berbasis `id` (bukan hanya kode)
* Siap dipakai langsung oleh developer

---

## ✨ Fitur

* ✅ Data lengkap: **Provinsi, Kabupaten/Kota, Kecamatan, Desa**
* ✅ Relasi internal (`province_id`, `regency_id`, `district_id`)
* ✅ Tetap menyimpan `code` resmi wilayah Indonesia
* ✅ Eloquent Model siap pakai
* ✅ Artisan command untuk install & import data
* ✅ Bisa digunakan untuk Web & Mobile API

---

## 📦 Instalasi

Install package via Composer:

```bash
composer require edi-prasetyo/indonesia-location
```

Package ini mendukung **auto-discovery**, tidak perlu menambahkan Service Provider secara manual.

```bash
php artisan package:discover
---

## 🗄 Publish & Migrasi Database

### 1️⃣ Publish file migration

```bash
php artisan vendor:publish --tag=indonesia-location-migrations
```

### 2️⃣ Jalankan migration

```bash
php artisan migrate
```

---

## 🚀 Import Data Wilayah Indonesia

Jalankan command berikut:

```bash
php artisan indonesia-location:install
```

Command ini akan:

1. Mengimport **provinces**
2. Mengimport **regencies** (mapping ke `province_id`)
3. Mengimport **districts** (mapping ke `regency_id`)
4. Mengimport **villages** (mapping ke `district_id`)

⚠️ Aman dijalankan ulang karena menggunakan `updateOrCreate()`.

---

## 🧠 Struktur Database

### provinces

| column    | type    |
| --------- | ------- |
| id        | bigint  |
| code      | string  |
| name      | string  |
| latitude  | decimal |
| longitude | decimal |

### regencies

| column        | type        |
| ------------- | ----------- |
| id            | bigint      |
| province_id   | bigint (FK) |
| province_code | string      |
| code          | string      |
| name          | string      |

### districts

| column      | type        |
| ----------- | ----------- |
| id          | bigint      |
| province_id | bigint (FK) |
| regency_id  | bigint (FK) |
| code        | string      |
| name        | string      |

### villages

| column      | type        |
| ----------- | ----------- |
| id          | bigint      |
| province_id | bigint (FK) |
| regency_id  | bigint (FK) |
| district_id | bigint (FK) |
| code        | string      |
| name        | string      |
| postal_code | string      |

---

## 📘 Penggunaan Model

### Ambil semua provinsi

```php
use EdiPrasetyo\IndonesiaLocation\Models\Province;

$provinces = Province::all();
```

### Dengan relasi

```php
$provinces = Province::with('regencies.districts.villages')->get();
```

### Contoh pencarian

```php
$aceh = Province::where('code', '11')->first();

$kabupaten = $aceh->regencies;
```

---

## 🔗 Relasi Eloquent

* Province → hasMany → Regencies
* Regency → hasMany → Districts
* District → hasMany → Villages

Semua relasi menggunakan **foreign key berbasis ID** untuk performa optimal.

---

## ⚙️ Kebutuhan Sistem

* PHP **8.2+**
* Laravel **11 atau lebih baru**
* Database MySQL / PostgreSQL

---

## 📄 Lisensi

Package ini dilisensikan di bawah **MIT License**.

---

## 👨‍💻 Author

**Edi Prasetyo**
GitHub: [https://github.com/edi-prasetyo](https://github.com/edi-prasetyo)

---

## 🤝 Kontribusi

Pull request & issue sangat diterima.
Silakan fork repository ini dan ajukan perubahan.

---

## ⭐ Penutup

Jika package ini membantu, jangan lupa beri ⭐ di GitHub 🙌

Happy coding 🚀
