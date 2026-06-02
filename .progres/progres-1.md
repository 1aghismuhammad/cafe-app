# Laporan Progress Project Cafe A App

## 1. Identitas Project

| Bagian | Keterangan |
|---|---|
| Nama Project | Cafe A App |
| Jenis Aplikasi | Sistem pemesanan restoran digital berbasis QR meja |
| Framework | Laravel |
| Frontend | Blade Laravel |
| Database | MySQL |
| Database Aktif | cafea_db |
| Mode Pengembangan | Lokal |
| URL Lokal | http://127.0.0.1:8000 |
| Status Saat Ini | Fondasi aplikasi dan modul Profil Cafe sudah berjalan |

---

## 2. Gambaran Umum Project

Cafe A App adalah aplikasi web restoran yang dirancang untuk membantu proses pemesanan makanan dan minuman secara digital. Pada rancangan awal, sistem ini akan mendukung pemesanan melalui QR meja, guest checkout, pembayaran QRIS, nota digital, dashboard kasir, dan laporan dasar.

Pada tahap awal ini, pengembangan difokuskan pada aplikasi lokal terlebih dahulu. Sistem belum menggunakan domain, subdomain, multi-tenant, dan payment gateway. Tujuan tahap awal adalah memastikan fondasi aplikasi stabil sebelum masuk ke fitur order, QR meja, menu, dan pembayaran.

---

## 3. Keputusan Teknis yang Sudah Diambil

## 3.1 Belum Menggunakan Domain

Karena domain belum tersedia, aplikasi dijalankan secara lokal melalui:

```text
http://127.0.0.1:8000