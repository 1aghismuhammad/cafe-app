# Progress 1 - Fondasi Aplikasi dan Profil Cafe A

## Status
Checkpoint 1 selesai.

## Yang Sudah Dibuat
- Laravel sudah terinstall
- Database cafea_db sudah terhubung
- Laravel Breeze sudah aktif
- Login, register, logout, dan profile sudah berjalan
- Role admin, kasir, dan owner sudah dibuat
- Middleware role sudah dibuat
- Dashboard berdasarkan role sudah dibuat
- Modul Profil Cafe A sudah dibuat
- Navbar admin sudah menampilkan menu Dashboard dan Profil Cafe

## Modul Selesai
### Profil Cafe A
File yang dibuat:
- app/Models/CafeProfile.php
- app/Http/Controllers/Admin/CafeProfileController.php
- database/migrations/create_cafe_profiles_table.php
- resources/views/admin/cafe-profile/edit.blade.php

Data yang bisa dikelola:
- Nama brand
- Nama legal usaha
- Slogan
- Deskripsi cafe
- Warna utama
- Warna sekunder
- Warna aksen
- WhatsApp
- Instagram
- TikTok
- Email
- Google Maps

## Error yang Sudah Diselesaikan
- Route profile.edit not defined
- Undefined CafeProfileController
- Class App\Models\CafeProfile not found
- Migration cafe_profiles rusak
- Error Vite Tailwind Unknown word use strict

## Fitur yang Belum Dibuat
- Outlet
- Area meja
- Meja
- QR meja
- Kategori menu
- Menu
- Halaman order pelanggan
- Keranjang
- Checkout
- Order masuk kasir
- Pembayaran manual
- Cetak nota pelanggan
- Cetak nota dapur
- Laporan dasar
- Midtrans QRIS
- Deploy

## Langkah Berikutnya
Lanjut membuat Modul Outlet Cafe A.

## Checkpoint 2 - Modul Outlet Cafe A

Status: Selesai.

### Yang Sudah Dibuat
- Model Outlet
- Migration tabel outlets
- Controller Admin/OutletController
- Route CRUD Outlet
- View daftar outlet
- View tambah outlet
- View edit outlet
- Menu Outlet di navbar admin

### Fitur yang Sudah Dites
- Tambah outlet berhasil
- Edit outlet berhasil
- Hapus outlet berhasil
- Data outlet tampil di tabel
- Pesan sukses tampil setelah aksi CRUD
- Route admin.outlets.* berjalan normal

### Catatan
Outlet menjadi dasar untuk modul Area Meja, Meja, QR Meja, Order, Nota, dan Laporan.

### Langkah Berikutnya
Lanjut ke Modul Area Meja.

## Checkpoint 3 - Modul Meja

Status: Selesai.

### Yang Sudah Dibuat
- Model RestaurantTable
- Migration tabel restaurant_tables
- Relasi restaurant_tables ke outlets
- Relasi Outlet ke RestaurantTable
- Controller Admin/RestaurantTableController
- Route CRUD Meja
- View daftar meja
- View tambah meja
- View edit meja
- Menu Meja di navbar admin

### Fitur yang Sudah Dites
- Tambah meja berhasil
- Data meja tampil di tabel
- Meja terhubung ke outlet
- Status meja tampil dengan benar
- Menu Meja tampil di navbar admin

### Data Awal yang Dibuat
- Meja 1, kode T01, kapasitas 2
- Meja 2, kode T02, kapasitas 2
- Meja 3, kode T03, kapasitas 4

### Catatan
Modul Area Meja dilewati sementara karena kebutuhan MVP saat ini cukup menggunakan nomor meja. Struktur relasi dibuat langsung dari outlet ke meja.

### Langkah Berikutnya
Lanjut ke Checkpoint 4 - Modul QR Meja.

## Checkpoint 4 - Modul QR Meja

Status: Selesai.

### Yang Sudah Dibuat
- Model TableQrCode
- Migration tabel table_qr_codes
- Relasi QR ke RestaurantTable
- Controller Admin/TableQrCodeController
- Controller Customer/OrderTableController
- Route admin QR Meja
- Route pelanggan /order/table/{token}
- View daftar QR Meja
- View detail QR Meja
- View preview halaman order meja
- Menu QR Meja di navbar admin

### Fitur yang Sudah Dites
- Generate QR meja berhasil
- Token QR berhasil dibuat
- URL QR berhasil dibuat
- QR Code berhasil tampil
- Detail QR meja berhasil tampil
- QR terhubung ke outlet dan nomor meja yang benar

### Catatan
URL QR masih menggunakan 127.0.0.1 karena aplikasi masih berjalan lokal. Untuk scan dari HP, APP_URL perlu diarahkan ke IP laptop atau domain production.

### Langkah Berikutnya
Lanjut ke Checkpoint 5 - Modul Kategori Menu.

## Checkpoint 5 - Modul Kategori Menu

Status: Selesai.

### Yang Sudah Dibuat
- Model Category
- Migration tabel categories
- Controller Admin/CategoryController
- Route CRUD kategori menu
- View daftar kategori
- View tambah kategori
- View edit kategori
- Menu Kategori Menu di navbar admin

### Fitur yang Sudah Dites
- Tambah kategori berhasil
- Kategori tampil di tabel
- Kategori tampil sesuai urutan display_order
- Status kategori tampil dengan benar
- Menu Kategori Menu tampil di navbar admin

### Data Awal yang Dibuat
- Coffee
- Non-Coffee
- Tea Series
- Main Course
- Snack
- Dessert
- Paket Hemat

### Catatan
Modul kategori menu menjadi dasar untuk Modul Menu. Setiap menu nanti wajib terhubung ke salah satu kategori.

# Checkpoint 6 - Modul Menu

## Tujuan Checkpoint

Checkpoint 6 dibuat untuk mengelola daftar menu Cafe A berdasarkan kategori yang sudah dibuat. Modul ini menjadi dasar sebelum halaman order pelanggan menampilkan menu.

Struktur relasi:

```text
Kategori Menu
↓
Menu
↓
Halaman Order Pelanggan
```

## Yang Sudah Dibuat

File yang dibuat:

```text
app/Models/Menu.php
app/Http/Controllers/Admin/MenuController.php
database/migrations/create_menus_table.php
resources/views/admin/menus/index.blade.php
resources/views/admin/menus/create.blade.php
resources/views/admin/menus/edit.blade.php
```

File yang diperbarui:

```text
app/Models/Category.php
routes/web.php
resources/views/layouts/navigation.blade.php
```

Tabel:

```text
menus
```

Kolom utama:

```text
id
category_id
menu_code
menu_name
description
price
image_path
preparation_time
stock_status
is_active
created_at
updated_at
```

Relasi:

```text
categories
↓
menus
```

Route:

```text
GET     /admin/menus
GET     /admin/menus/create
POST    /admin/menus
GET     /admin/menus/{menu}/edit
PUT     /admin/menus/{menu}
DELETE  /admin/menus/{menu}
```

Nama route:

```text
admin.menus.index
admin.menus.create
admin.menus.store
admin.menus.edit
admin.menus.update
admin.menus.destroy
```

Fitur yang sudah berjalan:

```text
Daftar menu
Tambah menu
Edit menu
Hapus menu
Pilih kategori menu
Kode menu unik
Nama menu
Deskripsi menu
Harga menu
Estimasi proses
Status stok
Status tampil
Upload foto menu sederhana
Fallback No Image jika foto kosong
Menu Menu di navbar admin
```

Status stok:

```text
available
sold_out
```

Status tampil:

```text
active
inactive
```

Data awal yang dibuat:

```text
Americano Hot
Americano Ice
Cafe Latte Hot
French Fries
Chicken Wings
Nasi Ayam Sambal Matah
```

Kategori yang sudah terhubung:

```text
Coffee
Snack
Main Course
```

Testing:

| Pengujian | Status |
|---|---|
| Tambah menu | Berhasil |
| Edit menu | Berhasil |
| Hapus menu | Berhasil |
| Validasi kode menu duplikat | Berhasil |
| Menu tampil di tabel | Berhasil |
| Kategori tampil di tabel | Berhasil |
| Harga tampil format rupiah | Berhasil |
| Status Available tampil benar | Berhasil |
| Status Active tampil benar | Berhasil |
| Status Inactive tampil benar | Berhasil |
| Foto menu tampil jika diunggah | Berhasil |
| Fallback No Image tampil jika foto kosong | Berhasil |
| Menu admin tampil di navbar | Berhasil |

Catatan testing:

```text
Sistem berhasil menolak kode menu yang sudah digunakan.
Harga besar seperti 2 miliar dapat ditampilkan sesuai format rupiah.
```

Status akhir:

```text
Checkpoint 6 selesai
```

---

# 6. Navbar Admin Saat Ini

Navbar admin saat ini berisi:

```text
Dashboard
Profil Cafe
Outlet
Meja
QR Meja
Kategori Menu
Menu
```

Semua menu tersebut ditampilkan untuk role admin.

---

# 7. Fitur yang Belum Dibuat

Fitur berikut belum dibuat:

```text
Halaman order pelanggan yang menampilkan menu
Filter menu berdasarkan kategori di halaman pelanggan
Keranjang
Checkout
Order
Pembayaran manual
Dashboard order kasir
Cetak nota pelanggan
Cetak nota dapur
Laporan dasar
Integrasi Midtrans QRIS
Deploy
```

---

# 8. Urutan Pengembangan Berikutnya

Urutan berikutnya:

```text
Checkpoint 7 - Halaman Order Pelanggan
Checkpoint 8 - Keranjang
Checkpoint 9 - Checkout
Checkpoint 10 - Order Masuk Kasir
Checkpoint 11 - Pembayaran Manual
Checkpoint 12 - Cetak Nota
Checkpoint 13 - Laporan Dasar
Checkpoint 14 - Integrasi Midtrans QRIS
Checkpoint 15 - Deploy
```

---

# 9. Rencana Checkpoint 7 - Halaman Order Pelanggan

## Tujuan

Mengembangkan halaman hasil scan QR agar pelanggan dapat melihat kategori dan daftar menu aktif.

Saat ini halaman QR pelanggan baru menampilkan:

```text
Nama cafe
Outlet
Nomor meja
Kode meja
Tombol Lanjut Pilih Menu
```

Pada Checkpoint 7, halaman ini akan dikembangkan agar menampilkan:

```text
Kategori menu
Daftar menu berdasarkan kategori
Nama menu
Deskripsi menu
Harga menu
Foto menu
Status available
Tombol tambah ke keranjang
```

Pada Checkpoint 7, keranjang belum perlu dibuat penuh. Fokus utamanya adalah menampilkan menu pelanggan setelah QR dibuka.

---

# 10. Kesimpulan

Project Cafe A App sudah menyelesaikan enam checkpoint utama. Fondasi sistem sudah cukup kuat untuk masuk ke sisi pelanggan.

Fitur yang sudah selesai meliputi:

```text
Auth
Role user
Dashboard berdasarkan role
Profil Cafe
Outlet
Meja
QR Meja
Kategori Menu
Menu
```

Tahap berikutnya adalah membuat halaman order pelanggan agar QR meja tidak hanya mengenali meja, tetapi juga menampilkan menu yang bisa dipilih pelanggan.
