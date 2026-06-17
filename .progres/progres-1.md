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


# Checkpoint 7 - Halaman Order Pelanggan

## Status
Checkpoint 7 selesai.

## Tujuan Checkpoint

Checkpoint 7 dibuat untuk mengembangkan halaman pelanggan setelah QR meja dibuka. Sebelumnya, halaman QR pelanggan hanya menampilkan informasi dasar seperti nama cafe, outlet, nomor meja, kode meja, dan tombol lanjut pilih menu.

Pada checkpoint ini, halaman tersebut sudah dikembangkan menjadi halaman order pelanggan yang menampilkan kategori menu dan daftar menu aktif yang tersedia.

## Alur Sistem

```text
Pelanggan scan QR meja
↓
Sistem membaca token QR
↓
Sistem mengenali outlet dan meja
↓
Sistem menampilkan halaman order pelanggan
↓
Pelanggan melihat kategori menu
↓
Pelanggan melihat daftar menu aktif dan tersedia
```

## Data yang Digunakan

```text
table_qr_codes
restaurant_tables
outlets
cafe_profiles
categories
menus
```

## File yang Diperbarui

```text
app/Http/Controllers/Customer/OrderTableController.php
resources/views/customer/order-table-preview.blade.php
routes/web.php
```

## Perubahan yang Dibuat

- Halaman QR pelanggan tidak lagi hanya menampilkan informasi meja.
- Halaman pelanggan sekarang menampilkan identitas Cafe A.
- Sistem menampilkan slogan cafe.
- Sistem menampilkan outlet, nomor meja, kode meja, dan status siap order.
- Sistem menampilkan kategori menu aktif.
- Sistem menampilkan daftar menu aktif dan tersedia.
- Sistem menampilkan foto menu jika tersedia.
- Sistem menampilkan fallback No Image jika foto kosong.
- Sistem menampilkan nama menu, deskripsi, estimasi proses, dan harga.
- Tombol Tambah sudah tampil pada setiap menu.
- Route pelanggan tetap public dan tidak masuk middleware auth.

## Filter Data yang Ditampilkan

Kategori yang tampil hanya kategori dengan kondisi:

```text
status = active
```

Menu yang tampil hanya menu dengan kondisi:

```text
is_active = true
stock_status = available
```

Menu dengan status inactive dan sold out tidak ditampilkan pada halaman pelanggan.

## Tampilan yang Sudah Berjalan

Halaman order pelanggan sudah menampilkan:

```text
Nama Cafe A
Slogan cafe
Outlet
Nomor meja
Kode meja
Status siap order
Kategori menu
Daftar menu berdasarkan kategori
Foto menu
Fallback No Image
Nama menu
Deskripsi menu
Estimasi proses
Harga menu
Tombol Tambah
```

## Hasil Testing

| Pengujian | Status |
|---|---|
| Link QR pelanggan dapat dibuka | Berhasil |
| Halaman tidak meminta login | Berhasil |
| Sistem mengenali outlet | Berhasil |
| Sistem mengenali nomor meja | Berhasil |
| Sistem mengenali kode meja | Berhasil |
| Profil Cafe A tampil | Berhasil |
| Slogan cafe tampil | Berhasil |
| Kategori aktif tampil | Berhasil |
| Menu tampil berdasarkan kategori | Berhasil |
| Menu inactive tidak tampil | Berhasil |
| Menu sold out tidak tampil | Berhasil |
| Foto menu tampil jika ada | Berhasil |
| Fallback No Image tampil jika foto kosong | Berhasil |
| Harga tampil format rupiah | Berhasil |
| Tombol Tambah tampil | Berhasil |
| Tampilan mobile-friendly | Berhasil |

## Catatan

Pada Checkpoint 7, tombol Tambah sudah tampil pada halaman pelanggan, tetapi belum berfungsi penuh untuk memasukkan menu ke keranjang. Fungsi tombol Tambah dikembangkan pada Checkpoint 8.

## Status Akhir

```text
Checkpoint 7 - Halaman Order Pelanggan selesai.
```

---

# Checkpoint 8 - Keranjang Pelanggan

## Status
Checkpoint 8 selesai untuk fungsi utama.

## Tujuan Checkpoint

Checkpoint 8 dibuat agar tombol Tambah pada halaman order pelanggan benar-benar berfungsi. Pada checkpoint sebelumnya, pelanggan sudah dapat melihat menu, tetapi belum dapat menyimpan pilihan menu.

Pada checkpoint ini, pelanggan sudah dapat menambahkan menu ke keranjang, melihat jumlah item, melihat total harga sementara, dan membuka halaman keranjang.

## Alur Sistem

```text
Pelanggan scan QR meja
↓
Sistem mengenali outlet dan meja
↓
Pelanggan melihat kategori dan menu
↓
Pelanggan klik tombol Tambah
↓
Menu masuk ke keranjang session
↓
Floating button Lihat Keranjang muncul
↓
Pelanggan membuka halaman keranjang
↓
Pelanggan melihat item dan total sementara
```

## Konsep Penyimpanan Keranjang

Keranjang disimpan menggunakan session berdasarkan token QR meja.

Format session key:

```text
cart_{token_qr}
```

Contoh:

```text
cart_YwcxpAvOQfVfC65hCjEwJbZW2I7zdIY
```

Dengan struktur ini, setiap token QR memiliki keranjang masing-masing.

## Struktur Item Keranjang

Setiap item dalam keranjang menyimpan data:

```text
menu_id
menu_name
menu_code
category_name
price
qty
subtotal
note
image_path
```

## File yang Dibuat

```text
app/Http/Controllers/Customer/CartController.php
resources/views/customer/cart.blade.php
```

## File yang Diperbarui

```text
app/Http/Controllers/Customer/OrderTableController.php
resources/views/customer/order-table-preview.blade.php
routes/web.php
```

## Method pada CartController

```text
add
show
increase
decrease
remove
updateNote
getActiveQrCode
cartKey
cartSummary
```

Fungsi masing-masing method:

| Method | Fungsi |
|---|---|
| add | Menambahkan menu ke keranjang |
| show | Menampilkan halaman keranjang |
| increase | Menambah jumlah item |
| decrease | Mengurangi jumlah item |
| remove | Menghapus item dari keranjang |
| updateNote | Menyimpan catatan item |
| getActiveQrCode | Memastikan token QR valid dan aktif |
| cartKey | Membuat key session berdasarkan token |
| cartSummary | Menghitung total item dan total harga |

## Route Keranjang

```text
POST    /order/table/{token}/cart/add/{menu}
GET     /order/table/{token}/cart
PATCH   /order/table/{token}/cart/{menu}/increase
PATCH   /order/table/{token}/cart/{menu}/decrease
DELETE  /order/table/{token}/cart/{menu}/remove
PATCH   /order/table/{token}/cart/{menu}/note
```

Nama route:

```text
customer.cart.add
customer.cart.show
customer.cart.increase
customer.cart.decrease
customer.cart.remove
customer.cart.note
```

Semua route customer cart berada di luar middleware auth sehingga pelanggan dapat menggunakan keranjang tanpa login.

## Fitur yang Sudah Dibuat

- Tambah menu ke keranjang.
- Item yang sama akan menambah qty.
- Pesan sukses tampil setelah menu ditambahkan.
- Floating button Lihat Keranjang tampil jika keranjang memiliki item.
- Jumlah item tampil pada floating button.
- Total harga sementara tampil pada floating button.
- Halaman keranjang sudah tersedia.
- Qty item dapat ditambah.
- Qty item dapat dikurangi.
- Item dapat dihapus dari keranjang.
- Catatan per item dapat disimpan.
- Subtotal item dihitung otomatis.
- Total harga keranjang dihitung otomatis.
- Tombol Tambah Menu tersedia untuk kembali ke halaman order.
- Tombol Lanjut Checkout sudah tersedia sebagai placeholder.

## Validasi yang Diterapkan

Saat pelanggan menambahkan menu ke keranjang, sistem mengecek:

```text
Token QR harus valid
QR meja harus aktif
Menu harus aktif
Menu harus tersedia
```

Menu hanya bisa masuk keranjang jika:

```text
is_active = true
stock_status = available
```

Jika menu tidak tersedia, sistem menampilkan pesan error:

```text
Menu tidak tersedia.
```

## Hasil Testing

| Pengujian | Status |
|---|---|
| Klik tombol Tambah | Berhasil |
| Menu masuk ke keranjang | Berhasil |
| Pesan sukses tampil | Berhasil |
| Floating button Lihat Keranjang tampil | Berhasil |
| Jumlah item tampil | Berhasil |
| Total harga tampil | Berhasil |
| Keranjang sesuai token QR | Berhasil |
| Halaman order tetap mengenali meja | Berhasil |
| Halaman tetap mobile-friendly | Berhasil |

Hasil pengujian yang terlihat:

```text
Chicken Wings berhasil ditambahkan ke keranjang.
Keranjang berisi 2 item.
Total sementara Rp52.000 tampil pada floating button.
```

## Pengujian Lanjutan

Pengujian lanjutan yang perlu dipastikan agar fungsi keranjang stabil penuh:

| Pengujian | Target |
|---|---|
| Buka halaman keranjang | Item yang dipilih tampil |
| Klik tombol plus | Qty bertambah |
| Klik tombol minus | Qty berkurang |
| Qty berkurang sampai 0 | Item terhapus |
| Klik hapus item | Item hilang dari keranjang |
| Simpan catatan item | Catatan tersimpan |
| Tambah beberapa menu berbeda | Total dihitung benar |
| Klik Tambah Menu | Kembali ke halaman order |
| Klik Lanjut Checkout | Placeholder checkout tampil |

## Batasan Checkpoint 8

Checkpoint ini belum membuat:

```text
Form nama pelanggan
Nomor WhatsApp pelanggan
Guest checkout
Penyimpanan order ke database
Tabel guest_customers
Tabel orders
Tabel order_items
Pembayaran
Order masuk kasir
Cetak nota
```

Keranjang masih bersifat sementara dan disimpan dalam session.

## Status Akhir

```text
Checkpoint 8 - Keranjang Pelanggan selesai untuk fungsi utama.
```

---

# Update Fitur yang Belum Dibuat

Fitur berikut belum dibuat:

```text
Guest checkout
Form data pelanggan
Simpan order
Simpan order item
Order masuk kasir
Pembayaran manual
Cetak nota pelanggan
Cetak nota dapur
Laporan dasar
Integrasi Midtrans QRIS
Deploy
```

Catatan:

```text
Halaman order pelanggan dan keranjang sudah tidak termasuk fitur belum dibuat karena sudah dikerjakan pada Checkpoint 7 dan Checkpoint 8.
```

---

# Update Urutan Pengembangan Berikutnya

Urutan pengembangan berikutnya:

```text
Checkpoint 9  - Guest Checkout
Checkpoint 10 - Order Masuk Kasir
Checkpoint 11 - Pembayaran Manual
Checkpoint 12 - Cetak Nota
Checkpoint 13 - Laporan Dasar
Checkpoint 14 - Integrasi Midtrans QRIS
Checkpoint 15 - Deploy
```

---

# Update Posisi Project Setelah Checkpoint 8

Project Cafe A App sudah menyelesaikan fondasi admin, master data, halaman pelanggan, dan keranjang sementara.

Fitur yang sudah selesai atau berjalan:

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
Halaman Order Pelanggan
Keranjang Pelanggan berbasis session
```

Alur pelanggan yang sudah berjalan:

```text
Pelanggan scan QR meja
↓
Sistem mengenali outlet dan meja
↓
Pelanggan melihat kategori menu
↓
Pelanggan melihat daftar menu aktif
↓
Pelanggan menambahkan menu ke keranjang
↓
Sistem menampilkan total sementara
↓
Pelanggan dapat membuka halaman keranjang
```

---

# Update Kesimpulan

Project Cafe A App sudah menyelesaikan delapan checkpoint utama. Fondasi sistem admin dan master data sudah stabil. Sisi pelanggan juga sudah mulai berjalan karena pelanggan dapat membuka QR, melihat menu, dan memasukkan menu ke keranjang.

Tahap berikutnya adalah Checkpoint 9, yaitu Guest Checkout. Pada tahap tersebut, isi keranjang akan diubah menjadi order yang tersimpan ke database.


# 🟣 CHECKPOINT 9 - GUEST CHECKOUT SYSTEM (FULL DOCUMENTATION)

---

# 📌 STATUS
Checkpoint 9 selesai dan sudah terintegrasi dengan sistem cart (Checkpoint 8).

---

# 🎯 TUJUAN CHECKPOINT

Checkpoint ini mengubah sistem:

```text
Session Cart → Checkout Form → Database Orders