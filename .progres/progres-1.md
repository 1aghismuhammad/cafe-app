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