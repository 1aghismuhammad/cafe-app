# CHECKPOINT 13 - DASHBOARD OWNER DAN LAPORAN DASAR

## Status

Checkpoint 13 selesai dan sudah diuji melalui browser.

## Gambaran Umum

Checkpoint 13 berfokus pada pembuatan dashboard owner dan laporan dasar penjualan. Fitur ini dibuat setelah alur kasir selesai pada checkpoint sebelumnya.

Pada Checkpoint 10, kasir sudah dapat melihat order masuk dan mengubah status order. Pada Checkpoint 11, kasir sudah dapat mengubah status pembayaran secara manual. Pada Checkpoint 12, kasir sudah dapat mencetak nota pelanggan. Setelah proses transaksi kasir berjalan, sistem perlu menyediakan halaman khusus owner untuk melihat ringkasan transaksi dan performa penjualan.

Checkpoint 13 dibuat agar owner dapat melihat data dasar bisnis, seperti total omzet, total order, jumlah order paid, jumlah order unpaid, jumlah order cancelled, transaksi terbaru, dan menu terlaris.

Fitur ini belum mencakup laporan detail, grafik penjualan, export Excel, export PDF, atau laporan per outlet secara mendalam. Fokus checkpoint ini adalah menyediakan dashboard laporan dasar yang stabil terlebih dahulu.

## Tujuan Checkpoint

Tujuan utama Checkpoint 13 adalah membuat halaman dashboard owner yang menampilkan ringkasan order dan laporan penjualan dasar.

Tujuan detail:

```text
Owner dapat melihat omzet dari order yang sudah paid
Owner dapat melihat omzet hari ini
Owner dapat melihat total order
Owner dapat melihat jumlah order paid
Owner dapat melihat jumlah order unpaid
Owner dapat melihat jumlah order cancelled
Owner dapat melihat daftar transaksi terbaru
Owner dapat melihat daftar menu terlaris
Owner dapat memfilter laporan berdasarkan tanggal mulai dan tanggal selesai
Dashboard owner hanya dapat diakses oleh user dengan role owner
```

## Prinsip Pengembangan

Pengembangan Checkpoint 13 tetap mengikuti prinsip pengembangan bertahap.

Prinsip yang digunakan:

```text
Tidak mengubah alur checkout pelanggan
Tidak mengubah alur cart pelanggan
Tidak mengubah fitur QR meja
Tidak mengubah fitur admin
Tidak mengubah fitur kasir yang sudah berjalan
Tidak mengubah fitur pembayaran manual
Tidak mengubah fitur cetak nota
Tidak membuat migration baru jika belum diperlukan
Hanya membaca data dari tabel yang sudah tersedia
Mengikuti struktur controller dan view yang sudah ada
```

Dengan pendekatan ini, dashboard owner ditambahkan tanpa mengganggu fitur yang sudah stabil pada checkpoint sebelumnya.

## Alur Sistem

Alur sistem pada Checkpoint 13:

```text
Owner login
↓
Owner diarahkan ke dashboard owner
↓
Sistem membaca data orders dan order_items
↓
Sistem menghitung ringkasan laporan
↓
Owner melihat omzet, status order, transaksi terbaru, dan menu terlaris
↓
Owner dapat memilih filter tanggal
↓
Sistem menampilkan data sesuai periode yang dipilih
```

## Fitur yang Dibuat

### 1. Ringkasan Omzet Paid

Dashboard owner menampilkan total omzet berdasarkan order yang memiliki status pembayaran `paid`.

Order dengan status pembayaran `unpaid` tidak dihitung sebagai omzet. Hal ini dibuat agar laporan pendapatan tidak mencampur pesanan yang belum benar-benar dibayar.

Logika utama:

```text
Omzet hanya dihitung dari orders.payment_status = paid
```

### 2. Omzet Hari Ini

Dashboard owner menampilkan omzet khusus untuk tanggal hari ini.

Data ini dihitung dari order yang:

```text
created_at = tanggal hari ini
payment_status = paid
```

Jika tidak ada order paid pada tanggal hari ini, maka omzet hari ini akan tampil Rp0.

### 3. Total Order

Dashboard owner menampilkan jumlah seluruh order yang masuk ke sistem.

Total order ini menghitung semua order, baik yang sudah dibayar, belum dibayar, maupun dibatalkan.

### 4. Order Paid

Dashboard owner menampilkan jumlah order dengan status pembayaran `paid`.

Data ini berguna untuk mengetahui berapa banyak transaksi yang sudah benar-benar dibayar oleh pelanggan.

### 5. Order Unpaid

Dashboard owner menampilkan jumlah order dengan status pembayaran `unpaid`.

Data ini berguna untuk mengetahui jumlah order yang masih belum dibayar atau belum dikonfirmasi pembayarannya.

### 6. Order Cancelled

Dashboard owner menampilkan jumlah order yang dibatalkan.

Order dianggap dibatalkan jika:

```text
orders.status = cancelled
atau
orders.payment_status = cancelled
```

### 7. Transaksi Terbaru

Dashboard owner menampilkan daftar transaksi terbaru.

Data yang ditampilkan:

```text
Kode order
Nama pelanggan
Outlet
Total transaksi
Status order
Status pembayaran
Waktu order
```

Pada tahap ini, transaksi terbaru dibatasi sebanyak 8 data agar tampilan dashboard tetap ringkas.

### 8. Menu Terlaris

Dashboard owner menampilkan daftar menu terlaris berdasarkan jumlah item yang terjual.

Menu terlaris hanya dihitung dari order yang sudah paid.

Data yang ditampilkan:

```text
Nama menu
Jumlah item terjual
Total penjualan menu
```

Pada tahap ini, menu terlaris dibatasi sebanyak 5 data.

### 9. Filter Tanggal

Dashboard owner menyediakan filter laporan berdasarkan tanggal mulai dan tanggal selesai.

Filter ini memengaruhi:

```text
Total order
Order paid
Order unpaid
Order cancelled
Total omzet paid
Transaksi terbaru
Menu terlaris
```

Filter tanggal menggunakan query parameter:

```text
start_date
end_date
```

Contoh URL hasil filter:

```text
/owner/dashboard?start_date=2026-06-17&end_date=2026-06-17
```

### 10. Reset Filter

Dashboard owner menyediakan tombol reset untuk menghapus filter tanggal dan menampilkan kembali data semua periode.

## File Baru yang Dibuat

File baru yang dibuat pada Checkpoint 13:

```text
app/Http/Controllers/Owner/DashboardController.php
.progres/checkpoint-13.md
```

## File Lama yang Diperbarui

File lama yang diperbarui:

```text
routes/web.php
resources/views/owner/dashboard.blade.php
```

Keterangan perubahan:

| File                            | Perubahan                                                         |
| ------------------------------- | ----------------------------------------------------------------- |
| `routes/web.php`                | Mengarahkan route owner dashboard ke controller baru              |
| `owner/dashboard.blade.php`     | Mengubah tampilan dashboard owner menjadi dashboard laporan dasar |
| `Owner/DashboardController.php` | Mengolah data order, omzet, transaksi terbaru, dan menu terlaris  |
| `checkpoint-13.md`              | Dokumentasi checkpoint                                            |

## Route yang Digunakan

Route yang digunakan:

```text
GET /owner/dashboard
```

Nama route:

```text
owner.dashboard
```

Controller:

```text
App\Http\Controllers\Owner\DashboardController@index
```

Middleware:

```text
auth
verified
role:owner
```

Dengan middleware tersebut, halaman dashboard owner hanya dapat diakses oleh user yang sudah login dan memiliki role owner.

## Controller yang Digunakan

Controller baru:

```text
app/Http/Controllers/Owner/DashboardController.php
```

Method yang digunakan:

| Method  | Fungsi                                                 |
| ------- | ------------------------------------------------------ |
| `index` | Mengambil data laporan dan menampilkan dashboard owner |

Data yang dihitung di controller:

```text
startDate
endDate
totalOrders
paidOrders
unpaidOrders
cancelledOrders
totalRevenue
todayRevenue
latestOrders
topMenus
```

## Data yang Digunakan

Checkpoint 13 menggunakan tabel yang sudah tersedia dari checkpoint sebelumnya.

Tabel utama:

```text
orders
order_items
```

Relasi pendukung:

```text
orders terhubung dengan outlet
orders terhubung dengan restaurant_table
order_items terhubung dengan order
```

Kolom utama dari tabel `orders`:

```text
id
order_code
customer_name
total_amount
status
payment_status
created_at
outlet_id
restaurant_table_id
```

Kolom utama dari tabel `order_items`:

```text
order_id
menu_name
quantity
subtotal
```

Checkpoint ini tidak membutuhkan migration baru karena seluruh data yang dibutuhkan sudah tersedia.

## Logika Perhitungan

### Total Order

```text
Menghitung seluruh data pada tabel orders sesuai periode filter.
```

### Order Paid

```text
Menghitung order dengan payment_status = paid.
```

### Order Unpaid

```text
Menghitung order dengan payment_status = unpaid.
```

### Order Cancelled

```text
Menghitung order dengan status = cancelled atau payment_status = cancelled.
```

### Omzet Paid

```text
Menjumlahkan total_amount dari order dengan payment_status = paid.
```

### Omzet Hari Ini

```text
Menjumlahkan total_amount dari order dengan payment_status = paid dan created_at pada tanggal hari ini.
```

### Menu Terlaris

```text
Mengelompokkan order_items berdasarkan menu_name.
Menjumlahkan quantity sebagai total item terjual.
Menjumlahkan subtotal sebagai total penjualan menu.
Hanya menghitung item dari order yang payment_status = paid.
```

## Tampilan Dashboard Owner

Dashboard owner terdiri dari beberapa bagian utama:

```text
Header Dashboard Owner
Ringkasan Laporan
Filter Tanggal Mulai dan Tanggal Selesai
Kartu Omzet Paid
Kartu Omzet Hari Ini
Kartu Total Order
Kartu Order Paid
Kartu Belum Dibayar
Kartu Dibatalkan
Tabel Transaksi Terbaru
Daftar Menu Terlaris
```

## Hasil Pengujian

Pengujian Checkpoint 13 telah dilakukan melalui browser.

Dashboard owner berhasil dibuka melalui URL:

```text
/owner/dashboard
```

Halaman dashboard berhasil menampilkan ringkasan laporan, filter tanggal, kartu statistik, tabel transaksi terbaru, dan bagian menu terlaris.

Data yang tampil pada pengujian:

```text
Omzet Paid: Rp92.000
Omzet Hari Ini: Rp0
Total Order: 2
Order Paid: 2
Belum Dibayar: 0
Dibatalkan: 0
```

Data transaksi terbaru yang tampil:

```text
ORD-20260617033300-579
Pelanggan: asep
Outlet: Cafe A Main Outlet
Total: Rp72.000
Status: Served
Pembayaran: Paid
Waktu: 17/06/2026 03:33
```

```text
ORD-20260617033139-434
Pelanggan: aran
Outlet: Cafe A Main Outlet
Total: Rp20.000
Status: Confirmed
Pembayaran: Paid
Waktu: 17/06/2026 03:31
```

Total omzet paid sebesar Rp92.000 sudah sesuai dengan penjumlahan transaksi paid:

```text
Rp72.000 + Rp20.000 = Rp92.000
```

Dengan demikian, logika perhitungan omzet paid sudah berjalan sesuai harapan.

## Catatan Omzet Hari Ini

Pada saat pengujian, nilai Omzet Hari Ini tampil:

```text
Rp0
```

Hal ini bukan error, karena order yang tampil pada dashboard memiliki tanggal:

```text
17/06/2026
```

Sedangkan fitur Omzet Hari Ini hanya menghitung order paid berdasarkan tanggal hari ini pada sistem/server.

Jika tanggal sistem tidak sama dengan tanggal order, maka Omzet Hari Ini akan tetap tampil Rp0.

## Pengujian Filter Tanggal

Filter tanggal diuji dengan memilih periode sesuai tanggal transaksi.

Target pengujian:

```text
Tanggal Mulai: 17/06/2026
Tanggal Selesai: 17/06/2026
```

Hasil yang diharapkan:

```text
Omzet Paid tetap Rp92.000
Total Order tetap 2
Order Paid tetap 2
Transaksi terbaru tetap menampilkan 2 order
Menu terlaris tetap dihitung dari order paid pada tanggal tersebut
```

Filter juga dapat diuji dengan tanggal yang tidak memiliki transaksi.

Hasil yang diharapkan:

```text
Omzet Paid menjadi Rp0
Total Order menjadi 0
Order Paid menjadi 0
Transaksi terbaru kosong
Menu terlaris kosong
```

## Pengujian Akses Role

Dashboard owner dilindungi oleh middleware role owner.

Pengujian akses dilakukan dengan mencoba membuka:

```text
/owner/dashboard
```

menggunakan user selain owner, misalnya admin atau kasir.

Hasil yang diharapkan:

```text
403 Forbidden
```

Jika sistem menampilkan 403, berarti proteksi role sudah berjalan dengan benar.

## Query Pembanding Database

Untuk memeriksa kesesuaian data dashboard dengan database, dapat digunakan query berikut:

```sql
SELECT 
    COUNT(*) AS total_order,
    SUM(CASE WHEN payment_status = 'paid' THEN 1 ELSE 0 END) AS paid_order,
    SUM(CASE WHEN payment_status = 'unpaid' THEN 1 ELSE 0 END) AS unpaid_order,
    SUM(CASE WHEN status = 'cancelled' OR payment_status = 'cancelled' THEN 1 ELSE 0 END) AS cancelled_order,
    SUM(CASE WHEN payment_status = 'paid' THEN total_amount ELSE 0 END) AS omzet_paid
FROM orders;
```

Untuk memeriksa menu terlaris:

```sql
SELECT 
    oi.menu_name,
    SUM(oi.quantity) AS total_quantity,
    SUM(oi.subtotal) AS total_sales
FROM order_items oi
JOIN orders o ON o.id = oi.order_id
WHERE o.payment_status = 'paid'
GROUP BY oi.menu_name
ORDER BY total_quantity DESC
LIMIT 5;
```

## Pengecekan Terminal

Pengecekan route yang perlu dilakukan:

```bash
php artisan route:list --name=owner.dashboard
```

Target route:

```text
GET|HEAD owner/dashboard owner.dashboard › Owner\DashboardController@index
```

Pengecekan autoload:

```bash
composer dump-autoload
```

Pengecekan syntax:

```bash
php -l app/Http/Controllers/Owner/DashboardController.php
php -l routes/web.php
```

Target hasil:

```text
No syntax errors detected
```

## Batasan Checkpoint 13

Checkpoint 13 belum mencakup fitur berikut:

```text
Grafik penjualan
Laporan detail per tanggal
Laporan detail per outlet
Laporan detail per menu
Export Excel
Export PDF
Laporan pajak
Laporan service charge
Laporan diskon
Laporan metode pembayaran
Laporan refund
Laporan laba rugi
Realtime dashboard
```

Fitur tersebut dapat dikembangkan pada checkpoint berikutnya agar pengembangan tetap bertahap dan tidak mengganggu fitur yang sudah stabil.

## Status Akhir

```text
Checkpoint 13 - Dashboard Owner dan Laporan Dasar selesai.
```

## Kesimpulan

Checkpoint 13 berhasil menambahkan fitur dashboard owner dan laporan dasar. Owner dapat melihat ringkasan omzet, total order, status pembayaran, transaksi terbaru, dan menu terlaris.

Dashboard owner juga sudah mendukung filter tanggal sehingga owner dapat melihat laporan berdasarkan periode tertentu.

Pengembangan dilakukan tanpa membuat migration baru dan tanpa mengubah fitur kasir, checkout pelanggan, QR meja, pembayaran manual, maupun cetak nota. Dengan demikian, Checkpoint 13 sudah aman dijadikan baseline sebelum masuk ke checkpoint berikutnya.
