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