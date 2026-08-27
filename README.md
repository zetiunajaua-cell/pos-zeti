# Modul 1 - Eksplorasi Struktur Folder Laravel

## Nama

* Nama : Zeti Unajaua
* Project : POS Laravel

---

# Struktur Folder Laravel

## 1. app

Folder utama yang berisi logika aplikasi Laravel.

Berisi:

* Models → Menghubungkan aplikasi dengan database.
* Http → Berisi Controller, Middleware, dan Request.
* Providers → Konfigurasi service provider.
* View → Komponen tampilan Laravel.

---

## 2. bootstrap

Berfungsi untuk proses inisialisasi (booting) Laravel saat aplikasi dijalankan.

---

## 3. config

Berisi seluruh konfigurasi aplikasi Laravel, seperti:

* database
* mail
* cache
* session
* filesystem

---

## 4. database

Berisi seluruh kebutuhan database.

Terdiri dari:

* migrations → Struktur tabel database.
* seeders → Data awal database.
* factories → Membuat data dummy untuk testing.

---

## 5. node_modules

Berisi library JavaScript yang diinstall menggunakan npm.

Contoh:

* Tailwind CSS
* Vite
* Axios

---

## 6. public

Folder yang dapat diakses langsung oleh browser.

Berisi:

* index.php
* favicon
* gambar publik

---

## 7. resources

Berisi tampilan (frontend) aplikasi.

Terdiri dari:

* css
* js
* views (Blade Template)

---

## 8. routes

Berisi seluruh daftar URL atau route aplikasi.

Contohnya:

* web.php
* auth.php
* console.php

---

## 9. storage

Berfungsi menyimpan file aplikasi.

Contohnya:

* cache
* log
* session
* file upload

---

## 10. tests

Berisi file pengujian (testing) aplikasi.

---

## 11. vendor

Berisi library Laravel dan package Composer.

Folder ini dibuat otomatis oleh Composer dan tidak diedit secara manual.

---

# File Penting

## .env

Berisi konfigurasi project seperti:

* APP_NAME
* APP_URL
* DB_DATABASE
* DB_USERNAME
* DB_PASSWORD

---

## artisan

File yang digunakan untuk menjalankan perintah Laravel.

Contoh:

* php artisan serve
* php artisan migrate
* php artisan make:model
* php artisan make:controller

---

## composer.json

Berisi daftar package PHP yang digunakan project.

---

## package.json

Berisi daftar package JavaScript yang digunakan project.

---

## README.md

Berisi dokumentasi project.

---

# Kesimpulan

Laravel memiliki struktur folder yang terorganisir sehingga setiap folder memiliki fungsi masing-masing. Pemisahan folder ini memudahkan proses pengembangan, pemeliharaan, dan pengelolaan aplikasi.

---

# Modul 7 - Testing, Optimasi & Persiapan Deployment

Project ini menggunakan automated testing untuk memastikan fitur berjalan sesuai kebutuhan.

## Hasil Automated Test

* Test transaksi berhasil mengurangi stok: PASS
* Test transaksi ditolak jika stok tidak mencukupi: PASS
* Seluruh automated test: 27 passed (67 assertions)

---

## Checklist Sebelum Deployment

| No | Checklist | Status | Catatan |
| -- | --------- | ------ | ------- |
| 1 | APP_ENV=production dan APP_DEBUG=false pada file `.env` server. | ✅ | Sudah diuji pada environment lokal dengan `APP_ENV=production` dan `APP_DEBUG=false`. Saat mengakses URL yang tidak tersedia, aplikasi menampilkan `404 Not Found` tanpa stack trace teknis. |
| 2 | APP_KEY sudah di-generate. | ✅ | `APP_KEY` sudah tersedia dan berhasil digunakan pada environment project. |
| 3 | Menjalankan `php artisan config:cache`. | ✅ | Berhasil dijalankan dengan pesan `Configuration cached successfully.` |
| 4 | Menjalankan `php artisan route:cache`. | ✅ | Berhasil dijalankan dengan pesan `Routes cached successfully.` |
| 5 | Menjalankan `php artisan view:cache`. | ✅ | Berhasil dijalankan dengan pesan `Blade templates cached successfully.` |
| 6 | Folder storage dan bootstrap/cache memiliki izin tulis yang benar. | ✅ | Kedua folder sudah diverifikasi dan dapat ditulis pada environment. |
| 7 | Password akun default sudah diganti. | ✅ | Password akun Administrator dan Kasir sudah berhasil diganti. |
| 8 | Backup database sudah dipersiapkan. | ✅ | Backup database manual sudah dipersiapkan sebelum aplikasi digunakan secara live. |
| 9 | HTTPS aktif jika aplikasi di-deploy ke domain publik. | ✅ | Sudah diterapkan menggunakan HTTPS pada domain publik `https://pos-zeti.pkl-banjar.my.id`. |

---

## Demo Production

Aplikasi telah diuji menggunakan konfigurasi:

```env
APP_ENV=production
APP_DEBUG=false