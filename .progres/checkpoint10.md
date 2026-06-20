# CHECKPOINT 10 - ORDER MASUK KASIR

## Status

Checkpoint 10 selesai dan sudah diuji melalui browser serta database.

## Gambaran Umum

Checkpoint 10 berfokus pada penambahan fitur operasional kasir untuk melihat dan memproses order pelanggan yang sudah masuk ke database.

Pada Checkpoint 9, pelanggan sudah dapat melakukan checkout melalui QR meja. Setelah checkout berhasil, sistem menyimpan data pesanan ke tabel `orders` dan `order_items`. Namun, pesanan tersebut belum dapat dilihat dan dikelola oleh kasir melalui dashboard internal.

Checkpoint 10 dibuat untuk menghubungkan proses checkout pelanggan dengan operasional kasir. Setelah checkpoint ini selesai, kasir dapat melihat daftar order masuk, membuka detail order, dan mengubah status order.

## Prinsip Pengembangan

Pengembangan pada checkpoint ini dilakukan dengan prinsip menambahkan fitur baru tanpa merombak fitur yang sudah berjalan.

Prinsip yang digunakan:

```text
Tidak mengubah alur checkout pelanggan
Tidak mengubah alur cart pelanggan
Tidak mengubah sistem QR meja
Tidak mengubah fitur admin yang sudah berjalan
Tidak mengubah struktur database yang sudah ada
Tidak mengubah logic utama pada Checkpoint 9
Mengikuti gaya penulisan kode yang sudah ada
Menambahkan file baru jika memungkinkan
Mengubah file lama hanya pada bagian yang diperlukan
```

Dengan pendekatan ini, sistem lama tetap aman dan fitur kasir dapat ditambahkan secara bertahap.

## Tujuan Checkpoint

Tujuan utama Checkpoint 10 adalah membuat halaman khusus kasir untuk mengelola order masuk.

Tujuan detail:

```text
Kasir dapat melihat daftar order masuk
Kasir dapat melihat detail order
Kasir dapat melihat informasi meja dan outlet
Kasir dapat melihat item pesanan pelanggan
Kasir dapat melihat total harga pesanan
Kasir dapat mengubah status order
Status order tersimpan ke database
Dashboard kasir mulai memiliki fungsi operasional
```

## Alur Sistem

Alur sistem pada Checkpoint 10:

```text
Pelanggan scan QR meja
↓
Pelanggan memilih menu
↓
Pelanggan checkout
↓
Order tersimpan ke database
↓
Kasir login
↓
Kasir membuka dashboard kasir
↓
Kasir membuka menu Order Masuk
↓
Kasir melihat daftar order
↓
Kasir membuka detail order
↓
Kasir mengubah status order
↓
Status order tersimpan ke database
```

## Fitur yang Dibuat

### 1. Halaman Daftar Order Masuk

Halaman daftar order masuk digunakan kasir untuk melihat semua order yang telah dibuat oleh pelanggan.

Data yang ditampilkan:

```text
Kode order
Nama pelanggan
Nomor HP pelanggan
Nomor meja
Nama outlet
Total harga
Status order
Status pembayaran
Waktu order
Tombol detail
```

Order terbaru ditampilkan di bagian atas agar kasir lebih mudah melihat pesanan yang baru masuk.

### 2. Filter Status Order

Kasir dapat memfilter order berdasarkan status.

Filter yang tersedia:

```text
Semua Order
Pending
Confirmed
Preparing
Ready
Served
Cancelled
```

Filter ini membantu kasir membedakan order baru, order yang sedang diproses, order yang siap disajikan, dan order yang sudah selesai.

### 3. Halaman Detail Order

Halaman detail order digunakan untuk melihat informasi lengkap dari satu pesanan.

Informasi yang ditampilkan:

```text
Kode order
Nama pelanggan
Nomor HP pelanggan
Catatan pelanggan
Nama outlet
Nomor meja
Daftar menu pesanan
Harga menu
Jumlah menu
Subtotal item
Total harga
Status order
Form ubah status order
```

Halaman ini menjadi halaman utama kasir sebelum memproses pesanan.

### 4. Update Status Order

Kasir dapat mengubah status pesanan melalui form pada halaman detail order.

Status yang digunakan:

```text
pending
confirmed
preparing
ready
served
cancelled
```

Penjelasan status:

| Status    | Keterangan                              |
| --------- | --------------------------------------- |
| pending   | Order baru masuk dan belum dikonfirmasi |
| confirmed | Order sudah diterima oleh kasir         |
| preparing | Order sedang disiapkan                  |
| ready     | Order sudah siap disajikan              |
| served    | Order sudah disajikan kepada pelanggan  |
| cancelled | Order dibatalkan                        |

Pada tahap ini, perubahan status dilakukan melalui dropdown sederhana agar mudah digunakan dan tetap aman untuk alur sistem yang sudah berjalan.

## File Baru yang Dibuat

File baru yang dibuat pada Checkpoint 10:

```text
app/Http/Controllers/Cashier/OrderController.php
resources/views/kasir/orders/index.blade.php
resources/views/kasir/orders/show.blade.php
.progres/checkpoint-10.md
```

## File Lama yang Diperbarui

File lama yang diperbarui secukupnya:

```text
routes/web.php
resources/views/layouts/navigation.blade.php
resources/views/kasir/dashboard.blade.php
```

Keterangan perubahan:

| File                        | Perubahan                                     |
| --------------------------- | --------------------------------------------- |
| `routes/web.php`            | Menambahkan route untuk halaman order kasir   |
| `navigation.blade.php`      | Menambahkan menu Order Masuk untuk role kasir |
| `kasir/dashboard.blade.php` | Menambahkan tombol menuju halaman Order Masuk |

Perubahan pada file lama hanya dilakukan pada bagian yang diperlukan. Tidak ada perubahan pada alur checkout, cart, QR meja, atau fitur admin.

## Route yang Ditambahkan

Route yang ditambahkan:

```text
GET     /kasir/orders
GET     /kasir/orders/{order}
PATCH   /kasir/orders/{order}/status
```

Nama route:

```text
kasir.orders.index
kasir.orders.show
kasir.orders.update-status
```

Route tersebut menggunakan middleware:

```text
auth
verified
role:kasir
```

Dengan middleware tersebut, halaman order kasir hanya dapat diakses oleh user yang sudah login dan memiliki role kasir.

## Controller yang Digunakan

Controller baru:

```text
app/Http/Controllers/Cashier/OrderController.php
```

Method yang dibuat:

| Method          | Fungsi                                  |
| --------------- | --------------------------------------- |
| `index`         | Menampilkan daftar order masuk          |
| `show`          | Menampilkan detail order                |
| `updateStatus`  | Mengubah status order                   |
| `statusOptions` | Menyediakan daftar pilihan status order |

## Data yang Digunakan

Checkpoint 10 menggunakan tabel yang sudah tersedia dari Checkpoint 9.

Tabel utama:

```text
orders
order_items
restaurant_tables
outlets
```

Relasi data yang digunakan:

```text
Order memiliki banyak OrderItem
Order terhubung dengan RestaurantTable
Order terhubung dengan Outlet
OrderItem menyimpan detail menu pesanan
```

Checkpoint ini tidak membutuhkan migration baru karena tabel order sudah tersedia.

## Validasi Status Order

Saat kasir mengubah status order, sistem hanya menerima status yang valid.

Status valid:

```text
pending
confirmed
preparing
ready
served
cancelled
```

Jika status di luar daftar tersebut dikirim ke sistem, perubahan status akan ditolak melalui validasi.

Aturan tambahan yang diterapkan:

```text
Order dengan status served tidak dapat langsung dibatalkan.
```

Aturan ini menjaga agar order yang sudah selesai tidak berubah menjadi cancelled secara sembarangan.

## Tampilan Halaman Daftar Order

Halaman daftar order berisi:

```text
Judul halaman Order Masuk Kasir
Filter status order
Tabel daftar order
Status order
Status pembayaran
Tombol detail
Pesan kosong jika belum ada order
```

Kolom tabel:

```text
Kode Order
Pelanggan
Meja
Outlet
Total
Status Order
Pembayaran
Waktu
Aksi
```

## Tampilan Halaman Detail Order

Halaman detail order berisi:

```text
Informasi item pesanan
Informasi pelanggan
Informasi outlet
Informasi meja
Catatan pelanggan
Total harga
Form ubah status order
Tombol kembali
```

Halaman ini membantu kasir memeriksa pesanan sebelum mengubah status.

## Hasil Pengujian

Pengujian Checkpoint 10 telah dilakukan melalui browser dan database.

Hasil pengujian:

| Pengujian                                                    | Hasil    |
| ------------------------------------------------------------ | -------- |
| Route kasir terdaftar                                        | Berhasil |
| Halaman `/kasir/orders` dapat dibuka oleh kasir              | Berhasil |
| Daftar order tampil dari database                            | Berhasil |
| Kode order tampil                                            | Berhasil |
| Nama pelanggan tampil                                        | Berhasil |
| Nomor meja tampil                                            | Berhasil |
| Outlet tampil                                                | Berhasil |
| Total harga tampil                                           | Berhasil |
| Status order tampil                                          | Berhasil |
| Status pembayaran tampil                                     | Berhasil |
| Waktu order tampil                                           | Berhasil |
| Tombol detail tampil                                         | Berhasil |
| Halaman detail order dapat dibuka                            | Berhasil |
| Item pesanan tampil di halaman detail                        | Berhasil |
| Informasi pelanggan tampil di halaman detail                 | Berhasil |
| Informasi outlet dan meja tampil                             | Berhasil |
| Catatan pelanggan tampil                                     | Berhasil |
| Form ubah status tampil                                      | Berhasil |
| Status order dapat diubah dari pending ke confirmed          | Berhasil |
| Perubahan status tampil di daftar order                      | Berhasil |
| Perubahan status tersimpan di database                       | Berhasil |
| Filter status pending berjalan                               | Berhasil |
| User dengan role selain kasir ditolak                        | Berhasil |
| Sistem menampilkan halaman 403 untuk akses tidak sesuai role | Berhasil |

## Bukti Pengujian

Pengujian perubahan status berhasil dilakukan pada order berikut:

```text
Kode Order: ORD-20260617033139-434
Nama Pelanggan: aran
Status Awal: pending
Status Setelah Diubah: confirmed
Payment Status: unpaid
```

Setelah status diubah melalui halaman detail order, data pada tabel `orders` juga ikut berubah menjadi `confirmed`. Hal ini menunjukkan bahwa proses update status tidak hanya berubah pada tampilan, tetapi benar-benar tersimpan ke database.

Pengujian filter juga berhasil. Ketika filter `pending` dipilih, sistem hanya menampilkan order yang masih memiliki status `pending`. Order yang sudah berubah menjadi `confirmed` tidak lagi tampil pada filter tersebut.

Pengujian akses juga berhasil. Ketika halaman `/kasir/orders` dibuka oleh user yang tidak memiliki role kasir, sistem menolak akses dan menampilkan halaman 403 dengan pesan:

```text
KAMU TIDAK PUNYA AKSES KE HALAMAN INI.
```

Hal ini menunjukkan bahwa middleware role sudah berjalan dengan benar.

## Batasan Checkpoint 10

Checkpoint ini belum mencakup fitur berikut:

```text
Pembayaran manual
Payment gateway Midtrans
Cetak nota pelanggan
Cetak nota dapur
Laporan owner
Realtime order notification
Diskon
Pajak
Service charge
Refund
Kitchen display system
```

Fitur tersebut akan dikembangkan pada checkpoint berikutnya agar pengembangan tetap terarah dan tidak merusak fitur yang sudah berjalan.

## Catatan Realtime

Pada Checkpoint 10, sistem belum berjalan realtime penuh.

Order baru akan muncul ketika halaman dibuka ulang atau direfresh. Hal ini sudah cukup untuk tahap awal karena fokus checkpoint ini adalah membuat fitur order masuk kasir terlebih dahulu.

Realtime dapat dikembangkan pada checkpoint berikutnya menggunakan polling atau WebSocket.

## Status Akhir

```text
Checkpoint 10 - Order Masuk Kasir selesai.
```

## Kesimpulan

Checkpoint 10 berhasil menambahkan fitur penting pada sisi kasir. Setelah checkpoint ini selesai, order pelanggan tidak hanya tersimpan ke database, tetapi juga dapat dilihat dan diproses oleh kasir melalui dashboard internal.

Fitur utama yang sudah berjalan meliputi daftar order masuk, detail order, filter status, perubahan status order, dan proteksi akses berdasarkan role kasir.

Pengembangan dilakukan dengan pendekatan menambahkan modul baru tanpa merombak sistem lama. Dengan cara ini, fitur checkout, cart, QR meja, dan admin master data tetap aman untuk checkpoint berikutnya.
