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

## Automated Testing

Automated testing digunakan untuk memastikan fitur aplikasi tetap berjalan dengan benar setelah terjadi perubahan kode.

Pada project POS Laravel ini digunakan feature test untuk menguji alur transaksi kasir dari request sampai perubahan stok produk.

### Test Transaksi Berhasil

Test ini memastikan stok produk berkurang sesuai jumlah barang yang dibeli.

Skenario:

* Stok awal produk = 10
* Produk dibeli = 3
* Stok setelah transaksi = 7

Hasil pengujian:

```text
PASS  Tests\Feature\TransactionTest
✓ it mengurangi stok produk setelah transaksi berhasil

Tests:    1 passed (2 assertions)
```

### Test Transaksi Gagal

Test ini memastikan transaksi ditolak ketika jumlah produk yang dibeli melebihi stok yang tersedia.

Skenario:

* Stok awal produk = 2
* Produk yang dibeli = 5
* Transaksi ditolak karena stok tidak mencukupi.
* Stok produk tetap 2.

Hasil pengujian:

```text
PASS  Tests\Feature\TransactionTest
✓ it menolak transaksi jika stok produk tidak mencukupi

Tests:    1 passed (2 assertions)
```

### Hasil Seluruh Automated Test

Seluruh test aplikasi dijalankan menggunakan:

```bash
php artisan test
```

Hasil pengujian:

```text
Tests:    25 passed (61 assertions)
Duration: 9.79s
```

Seluruh automated test berhasil dijalankan tanpa error atau test yang gagal.

---

## Optimasi Laravel

Beberapa perintah optimasi Laravel telah dijalankan untuk mempersiapkan aplikasi sebelum deployment.

### Configuration Cache

```bash
php artisan config:cache
```

Perintah ini digunakan untuk melakukan cache terhadap konfigurasi Laravel.

### Route Cache

```bash
php artisan route:cache
```

Perintah ini digunakan untuk melakukan cache terhadap route aplikasi.

### View Cache

```bash
php artisan view:cache
```

Perintah ini digunakan untuk melakukan cache terhadap template Blade.

### Storage Link

```bash
php artisan storage:link
```

Perintah ini digunakan agar file yang berada pada storage dapat diakses melalui folder public.

---

# Checklist Sebelum Deployment

| No | Checklist                     | Status | Catatan                                                                             |
| -- | ----------------------------- | ------ | ----------------------------------------------------------------------------------- |
| 1  | APP_ENV=production            | ⬜      | Belum diterapkan karena aplikasi masih digunakan pada environment development lokal |
| 2  | APP_DEBUG=false               | ✅      | Berhasil didemokan. Halaman 404 tidak menampilkan stack trace atau informasi teknis |
| 3  | APP_KEY khusus production     | ⬜      | Belum dibuat khusus untuk environment production                                    |
| 4  | `php artisan config:cache`    | ✅      | Berhasil dijalankan                                                                 |
| 5  | `php artisan route:cache`     | ✅      | Berhasil dijalankan                                                                 |
| 6  | `php artisan view:cache`      | ✅      | Berhasil dijalankan                                                                 |
| 7  | Permission storage            | ✅      | Storage link berhasil dibuat                                                        |
| 8  | Password akun default diganti | ⬜      | Perlu dilakukan sebelum deployment production                                       |
| 9  | Backup database               | ⬜      | Perlu disiapkan sebelum aplikasi digunakan secara resmi                             |
| 10 | HTTPS/SSL aktif               | ⬜      | Diperlukan jika aplikasi menggunakan domain publik                                  |

> Catatan: aplikasi masih dikembangkan pada environment lokal. Oleh karena itu, konfigurasi production seperti APP_ENV=production, APP_KEY production, backup database, dan HTTPS belum diterapkan pada komputer development.

---

## Demo APP_DEBUG=false

Pengujian dilakukan dengan menjalankan aplikasi menggunakan:

```env
APP_DEBUG=false
```

Kemudian diakses URL yang tidak tersedia untuk memicu halaman error 404.

Hasil pengujian:

```text
404
Not Found
```

Aplikasi tidak menampilkan stack trace, path file, atau informasi teknis kepada pengguna.

**Hasil: Berhasil.**

---

# Kesimpulan Modul 7

Pada Modul 7 telah dilakukan automated testing menggunakan feature test dengan Pest untuk menguji alur transaksi pada aplikasi POS Laravel.

Pengujian mencakup dua skenario, yaitu transaksi berhasil yang mengurangi stok produk dan transaksi gagal ketika stok produk tidak mencukupi. Kedua test berhasil dijalankan dengan hasil **2 passed (4 assertions)**.

Seluruh automated test aplikasi juga berhasil dijalankan dengan hasil **25 passed (61 assertions)**.

Selain testing, dilakukan optimasi Laravel menggunakan configuration cache, route cache, dan view cache. Storage link juga berhasil dibuat.

Checklist persiapan deployment telah dibuat dan diverifikasi berdasarkan kondisi aplikasi saat ini. Pengujian `APP_DEBUG=false` juga berhasil dilakukan dengan hasil halaman 404 yang tidak menampilkan stack trace atau informasi teknis kepada pengguna.

Dengan demikian, fitur automated testing, optimasi, dan persiapan deployment pada Modul 7 telah selesai dilakukan.
