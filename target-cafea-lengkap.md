# TARGET PROJECT CAFE A

## Sistem Pemesanan Restoran Digital Berbasis QR Meja untuk Cafe A

Dokumen ini menjadi patokan pengembangan aplikasi untuk client pertama, yaitu **Cafe A**. Fokus sistem adalah pemesanan dine-in berbasis QR meja, guest checkout, pembayaran QRIS melalui Midtrans, nota digital, kitchen display, dashboard kasir, dan laporan transaksi dasar.

Dokumen ini memakai pendekatan **multi-tenant database per client**.

Artinya:

```text
1 aplikasi Laravel utama
1 database master
1 database khusus Cafe A
1 subdomain khusus Cafe A
```

---

## 1. Identitas Project

| Bagian | Isi |
|---|---|
| Nama project | Sistem Pemesanan Restoran Digital Cafe A |
| Nama client | Cafe A |
| Jenis aplikasi | Aplikasi web restoran |
| Model aplikasi | Multi-tenant dengan database per client |
| Subdomain client | cafea.domainkamu.com |
| Database master | app_master |
| Database client | tenant_cafea |
| Platform utama | Browser web |
| Backend | Laravel |
| Frontend | Blade Laravel |
| Database | MySQL |
| Payment gateway | Midtrans |
| Metode pembayaran | QRIS saja |
| Deployment | Hosting yang mendukung Laravel, MySQL, HTTPS, subdomain, dan callback Midtrans |

---

## 2. Konsep Sistem untuk Cafe A

Cafe A akan memiliki satu subdomain khusus, yaitu:

```text
cafea.domainkamu.com
```

Saat pelanggan, kasir, dapur, admin, atau owner membuka subdomain tersebut, sistem akan mengenali bahwa subdomain itu milik Cafe A.

Alur teknisnya:

```text
cafea.domainkamu.com
↓
Laravel membaca subdomain cafea
↓
Sistem mencari data Cafe A di database master
↓
Sistem mengaktifkan koneksi database tenant_cafea
↓
Semua data menu, meja, order, pembayaran, dan laporan diambil dari database Cafe A
```

Dengan model ini, data Cafe A tidak bercampur dengan data client lain.

---

## 3. Latar Belakang Masalah

Cafe A membutuhkan sistem pemesanan yang lebih cepat, rapi, dan mudah digunakan oleh pelanggan. Proses pemesanan manual dapat menyebabkan antrean, salah catat pesanan, dan keterlambatan komunikasi antara pelanggan, kasir, dan dapur.

Sistem ini dirancang agar pelanggan Cafe A dapat memesan langsung dari meja melalui QR code. Pelanggan tidak perlu membuat akun. Pelanggan cukup scan QR meja, memilih menu, mengisi nama, nomor telepon, dan email, lalu membayar menggunakan QRIS Midtrans.

Setelah pembayaran berhasil, pesanan otomatis masuk ke dashboard kasir dan kitchen display dapur Cafe A.

---

## 4. Tujuan Project

1. Membuat sistem pemesanan digital khusus untuk Cafe A.
2. Mempercepat proses pemesanan pelanggan dari meja.
3. Mengurangi antrean pada kasir.
4. Mengurangi risiko salah catat pesanan.
5. Menghubungkan pesanan pelanggan dengan nomor meja secara otomatis.
6. Mengirim pesanan ke kasir dan dapur secara rapi.
7. Menyediakan pembayaran QRIS melalui Midtrans.
8. Menyimpan riwayat transaksi pelanggan tamu Cafe A.
9. Menyediakan laporan transaksi dasar untuk admin dan owner Cafe A.
10. Menyiapkan struktur sistem agar client lain dapat ditambahkan tanpa membuat aplikasi baru.

---

## 5. Target Pengguna Cafe A

| Role | Fungsi Utama |
|---|---|
| Pelanggan tamu | Scan QR meja, memilih menu, checkout, mengisi data diri, membayar QRIS, menerima nota digital |
| Kasir Cafe A | Memantau pesanan masuk, mengecek status pembayaran, melihat detail transaksi, mengirim ulang nota jika diperlukan |
| Dapur Cafe A | Melihat pesanan masuk, melihat detail menu dan nomor meja, mengubah status pesanan |
| Admin Cafe A | Mengelola menu, kategori, area meja, meja, QR meja, user internal, dan data outlet Cafe A |
| Owner Cafe A | Melihat dashboard, laporan transaksi, omzet, dan menu terlaris Cafe A |
| Super Admin Sistem | Mengelola data client, subdomain, database tenant, status langganan, dan konfigurasi global |

---

## 6. Ruang Lingkup Project

### 6.1 Fitur yang Dikerjakan untuk Cafe A

1. Subdomain khusus Cafe A.
2. Database khusus Cafe A.
3. QR unik untuk setiap meja Cafe A.
4. Halaman menu digital Cafe A.
5. Keranjang pesanan.
6. Guest checkout tanpa login pelanggan.
7. Input nama pelanggan, nomor telepon, dan email.
8. Generate QRIS melalui Midtrans.
9. Callback atau notifikasi status pembayaran dari Midtrans.
10. Nota digital.
11. Dashboard pesanan untuk kasir Cafe A.
12. Kitchen display untuk dapur Cafe A.
13. Status pesanan.
14. Manajemen kategori menu.
15. Manajemen menu.
16. Manajemen outlet Cafe A.
17. Manajemen area dan meja Cafe A.
18. Manajemen QR meja Cafe A.
19. Manajemen user dan role internal Cafe A.
20. Laporan transaksi dasar Cafe A.
21. Database master untuk menyimpan identitas client dan subdomain.

### 6.2 Batasan Project

1. Sistem untuk Cafe A hanya berfokus pada pemesanan dine-in melalui QR meja.
2. Pembayaran hanya menggunakan QRIS dari Midtrans.
3. Pelanggan tidak perlu membuat akun dan tidak perlu login.
4. Sistem belum mencakup marketplace.
5. Sistem belum mencakup loyalty member.
6. Sistem belum mencakup stok bahan baku detail.
7. Sistem belum mencakup akuntansi lengkap.
8. Sistem belum mencakup payroll.
9. Sistem belum mencakup aplikasi mobile native.
10. Sistem belum mencakup multi-outlet kompleks untuk Cafe A, kecuali jika ditambahkan pada tahap berikutnya.

---

## 7. Arsitektur Multi-Tenant Cafe A

### 7.1 Database Master

Database master digunakan untuk menyimpan data client, domain, konfigurasi database, status langganan, dan konfigurasi pembayaran.

Nama database:

```text
app_master
```

Tabel utama pada database master:

| Tabel | Fungsi |
|---|---|
| tenants | Menyimpan data client, termasuk Cafe A |
| tenant_domains | Menyimpan subdomain setiap client |
| tenant_database_configs | Menyimpan nama database dan koneksi database client |
| tenant_payment_configs | Menyimpan konfigurasi Midtrans client |
| subscriptions | Menyimpan status langganan client |
| super_admins | Menyimpan akun pengelola sistem utama |

Contoh data tenant Cafe A:

| Kolom | Isi |
|---|---|
| tenant_name | Cafe A |
| subdomain | cafea |
| domain | cafea.domainkamu.com |
| database_name | tenant_cafea |
| status | active |

### 7.2 Database Client Cafe A

Database client Cafe A menyimpan semua data operasional Cafe A.

Nama database:

```text
tenant_cafea
```

Tabel utama pada database Cafe A:

| Tabel | Fungsi |
|---|---|
| users | Menyimpan user internal Cafe A |
| roles | Menyimpan role kasir, dapur, admin, dan owner |
| outlets | Menyimpan data outlet Cafe A |
| dining_areas | Menyimpan area meja Cafe A |
| restaurant_tables | Menyimpan data meja Cafe A |
| table_qr_codes | Menyimpan token dan URL QR meja Cafe A |
| guest_customers | Menyimpan data pelanggan tamu Cafe A |
| guest_sessions | Menyimpan sesi pelanggan tamu berdasarkan browser |
| categories | Menyimpan kategori menu Cafe A |
| menus | Menyimpan data menu Cafe A |
| orders | Menyimpan transaksi pesanan Cafe A |
| order_items | Menyimpan detail item pesanan Cafe A |
| payments | Menyimpan data pembayaran Midtrans Cafe A |
| invoices | Menyimpan data nota digital Cafe A |
| order_status_logs | Menyimpan riwayat perubahan status pesanan Cafe A |

---

## 8. Alur Utama Sistem Cafe A

### 8.1 Alur Pelanggan Cafe A

1. Pelanggan duduk di meja Cafe A.
2. Pelanggan scan QR pada meja.
3. Sistem membaca token QR meja.
4. Sistem mengenali bahwa QR berasal dari subdomain Cafe A.
5. Sistem mengenali outlet, area, dan nomor meja.
6. Pelanggan melihat menu digital Cafe A.
7. Pelanggan memilih menu.
8. Pelanggan memasukkan menu ke keranjang.
9. Pelanggan mengecek ulang pesanan.
10. Pelanggan mengisi nama, nomor telepon, dan email.
11. Pelanggan checkout.
12. Sistem membuat transaksi di database `tenant_cafea`.
13. Sistem generate QRIS melalui Midtrans.
14. Pelanggan membayar menggunakan QRIS.
15. Midtrans mengirim status pembayaran ke sistem.
16. Jika pembayaran berhasil, pesanan masuk ke dashboard kasir dan kitchen display Cafe A.
17. Sistem membuat nota digital.
18. Nota dikirim atau dapat dilihat melalui link nota.

### 8.2 Alur Kasir Cafe A

1. Kasir Cafe A login melalui `cafea.domainkamu.com`.
2. Sistem membaca subdomain dan mengarahkan koneksi ke database `tenant_cafea`.
3. Kasir melihat daftar pesanan masuk.
4. Kasir melihat detail pelanggan, meja, menu, dan status pembayaran.
5. Kasir memantau pesanan yang sudah dibayar.
6. Kasir dapat melihat nota digital.
7. Kasir dapat mengirim ulang nota jika pelanggan membutuhkan.
8. Kasir dapat membantu pelanggan jika pembayaran belum berhasil.
9. Kasir dapat melihat status pesanan sampai selesai.

### 8.3 Alur Dapur Cafe A

1. Staf dapur Cafe A login.
2. Staf dapur membuka kitchen display.
3. Sistem menampilkan pesanan Cafe A yang sudah dibayar.
4. Dapur melihat pesanan berdasarkan urutan waktu masuk.
5. Dapur melihat nomor meja, area, detail menu, jumlah, dan catatan.
6. Dapur mengubah status menjadi `processing`.
7. Dapur mengubah status menjadi `completed` setelah pesanan selesai.
8. Status pesanan tampil di dashboard kasir dan halaman status pelanggan.

### 8.4 Alur Admin Cafe A

1. Admin Cafe A login.
2. Admin mengelola data outlet Cafe A.
3. Admin mengelola area meja.
4. Admin mengelola data meja.
5. Admin membuat atau mencetak QR meja.
6. Admin mengelola kategori menu.
7. Admin mengelola menu.
8. Admin mengatur menu aktif dan tidak aktif.
9. Admin mengelola akun kasir, dapur, admin, dan owner Cafe A.
10. Admin memantau transaksi Cafe A.

### 8.5 Alur Owner Cafe A

1. Owner Cafe A login.
2. Owner melihat dashboard.
3. Owner melihat jumlah transaksi.
4. Owner melihat omzet.
5. Owner melihat menu terlaris.
6. Owner memfilter laporan berdasarkan tanggal.
7. Owner melihat detail transaksi Cafe A.

### 8.6 Alur Super Admin Sistem

1. Super admin login ke panel utama.
2. Super admin membuat data tenant Cafe A.
3. Super admin membuat subdomain Cafe A.
4. Super admin membuat database `tenant_cafea`.
5. Super admin menjalankan migration untuk database Cafe A.
6. Super admin mengisi konfigurasi Midtrans Cafe A.
7. Super admin mengaktifkan status Cafe A.
8. Super admin memantau status langganan Cafe A.

---

## 9. Fitur MVP Cafe A

| No | Fitur | Prioritas | Keterangan |
|---|---|---|---|
| 1 | Subdomain Cafe A | Tinggi | Cafe A memiliki akses khusus melalui `cafea.domainkamu.com` |
| 2 | Database Cafe A | Tinggi | Data operasional Cafe A disimpan di `tenant_cafea` |
| 3 | QR meja | Tinggi | Setiap meja Cafe A memiliki QR unik |
| 4 | Menu digital | Tinggi | Pelanggan melihat daftar menu aktif Cafe A |
| 5 | Keranjang | Tinggi | Pelanggan memilih menu dan jumlah pesanan |
| 6 | Guest checkout | Tinggi | Pelanggan checkout tanpa akun |
| 7 | Data pelanggan tamu | Tinggi | Nama, nomor telepon, dan email disimpan |
| 8 | QRIS Midtrans | Tinggi | Sistem generate QRIS saat checkout |
| 9 | Status pembayaran | Tinggi | Status diperbarui dari Midtrans |
| 10 | Order kasir | Tinggi | Kasir melihat transaksi dan status pembayaran |
| 11 | Kitchen display | Tinggi | Dapur melihat pesanan yang perlu diproses |
| 12 | Status pesanan | Tinggi | Pesanan memiliki status pending, processing, completed, atau cancelled |
| 13 | Nota digital | Sedang | Nota dibuat setelah transaksi berhasil |
| 14 | Laporan transaksi | Sedang | Admin dan owner melihat laporan dasar Cafe A |
| 15 | Manajemen menu | Tinggi | Admin mengelola menu dan kategori Cafe A |
| 16 | Manajemen meja | Tinggi | Admin mengelola meja dan QR meja Cafe A |
| 17 | Manajemen user | Sedang | Admin mengelola akun kasir, dapur, admin, dan owner Cafe A |

---

## 10. Business Rules Cafe A

1. Cafe A wajib memiliki subdomain aktif.
2. Cafe A wajib memiliki database tenant sendiri.
3. Setiap request dari `cafea.domainkamu.com` harus memakai database `tenant_cafea`.
4. Setiap meja Cafe A wajib memiliki QR unik.
5. QR meja harus memakai token acak, bukan hanya ID meja.
6. QR hanya dapat digunakan jika statusnya aktif.
7. Pelanggan Cafe A tidak wajib login untuk memesan.
8. Pelanggan wajib mengisi nama dan nomor telepon.
9. Email digunakan untuk nota digital.
10. Pesanan wajib terhubung dengan outlet, area, dan meja Cafe A.
11. Pesanan wajib memiliki minimal satu item menu.
12. Menu yang tidak aktif tidak boleh tampil pada halaman pelanggan.
13. Harga pesanan mengikuti harga menu saat transaksi dibuat.
14. Pembayaran hanya menggunakan QRIS Midtrans.
15. Pesanan masuk ke dapur setelah pembayaran berhasil.
16. Status pembayaran diperbarui berdasarkan callback atau notifikasi Midtrans.
17. Pesanan yang sudah dibayar tidak boleh dihapus sembarangan.
18. Dapur hanya dapat mengubah status proses pesanan.
19. Owner hanya membaca laporan dan dashboard.
20. Admin Cafe A memiliki akses untuk mengelola data master Cafe A.
21. Super admin tidak mengubah transaksi Cafe A kecuali untuk kebutuhan teknis dan audit.

---

## 11. Validasi Utama Cafe A

| Bagian | Validasi |
|---|---|
| Subdomain | Subdomain harus terdaftar di database master |
| Tenant | Tenant Cafe A harus aktif |
| Database | Database `tenant_cafea` harus tersedia dan dapat diakses |
| QR meja | Token QR wajib valid dan aktif |
| Meja | Meja wajib terhubung dengan outlet dan area Cafe A |
| Pelanggan | Nama dan nomor telepon wajib diisi |
| Email | Email harus valid jika diisi |
| Menu | Menu harus aktif agar bisa dipesan |
| Qty | Qty minimal 1 |
| Order | Order wajib memiliki minimal satu item |
| Payment | Payment wajib memiliki order_id dan status dari Midtrans |
| Role | User hanya boleh mengakses menu sesuai role |
| Laporan | Filter tanggal harus valid |

---

## 12. Rancangan Kolom Minimal Database Master

### 12.1 tenants

| Kolom | Keterangan |
|---|---|
| id | Primary key |
| tenant_name | Nama client, contoh Cafe A |
| tenant_slug | Slug client, contoh cafea |
| status | active, inactive, suspended |
| created_at | Waktu dibuat |
| updated_at | Waktu diperbarui |

### 12.2 tenant_domains

| Kolom | Keterangan |
|---|---|
| id | Primary key |
| tenant_id | Relasi ke tenants |
| subdomain | Subdomain, contoh cafea |
| full_domain | Domain lengkap, contoh cafea.domainkamu.com |
| is_primary | Penanda domain utama |
| is_active | Status domain |
| created_at | Waktu dibuat |
| updated_at | Waktu diperbarui |

### 12.3 tenant_database_configs

| Kolom | Keterangan |
|---|---|
| id | Primary key |
| tenant_id | Relasi ke tenants |
| database_name | Nama database, contoh tenant_cafea |
| database_host | Host database |
| database_username | Username database |
| database_password | Password database terenkripsi |
| is_active | Status koneksi database |
| created_at | Waktu dibuat |
| updated_at | Waktu diperbarui |

### 12.4 tenant_payment_configs

| Kolom | Keterangan |
|---|---|
| id | Primary key |
| tenant_id | Relasi ke tenants |
| payment_gateway | Midtrans |
| merchant_id | Merchant ID Midtrans |
| client_key | Client key Midtrans |
| server_key | Server key Midtrans terenkripsi |
| environment | sandbox atau production |
| is_active | Status konfigurasi pembayaran |
| created_at | Waktu dibuat |
| updated_at | Waktu diperbarui |

### 12.5 subscriptions

| Kolom | Keterangan |
|---|---|
| id | Primary key |
| tenant_id | Relasi ke tenants |
| plan_name | Nama paket langganan |
| monthly_fee | Biaya langganan bulanan |
| status | active, unpaid, suspended, cancelled |
| start_date | Tanggal mulai |
| end_date | Tanggal berakhir |
| created_at | Waktu dibuat |
| updated_at | Waktu diperbarui |

---

## 13. Rancangan Kolom Minimal Database Cafe A

### 13.1 users

| Kolom | Keterangan |
|---|---|
| id | Primary key |
| name | Nama user |
| email | Email login |
| password | Password hash |
| role_id | Relasi ke roles |
| outlet_id | Relasi ke outlets |
| created_at | Waktu dibuat |
| updated_at | Waktu diperbarui |

### 13.2 outlets

| Kolom | Keterangan |
|---|---|
| id | Primary key |
| outlet_name | Nama outlet Cafe A |
| address | Alamat outlet Cafe A |
| created_at | Waktu dibuat |
| updated_at | Waktu diperbarui |

### 13.3 dining_areas

| Kolom | Keterangan |
|---|---|
| id | Primary key |
| outlet_id | Relasi ke outlets |
| area_name | Nama area meja |
| created_at | Waktu dibuat |
| updated_at | Waktu diperbarui |

### 13.4 restaurant_tables

| Kolom | Keterangan |
|---|---|
| id | Primary key |
| outlet_id | Relasi ke outlets |
| area_id | Relasi ke dining_areas |
| table_number | Nomor meja |
| table_code | Kode meja |
| capacity | Kapasitas meja |
| status | Status meja |
| created_at | Waktu dibuat |
| updated_at | Waktu diperbarui |

### 13.5 table_qr_codes

| Kolom | Keterangan |
|---|---|
| id | Primary key |
| table_id | Relasi ke restaurant_tables |
| qr_token | Token QR unik |
| qr_url | URL order berdasarkan QR |
| is_active | Status aktif QR |
| created_at | Waktu dibuat |
| updated_at | Waktu diperbarui |

### 13.6 guest_customers

| Kolom | Keterangan |
|---|---|
| id | Primary key |
| name | Nama pelanggan |
| phone | Nomor telepon pelanggan |
| email | Email pelanggan |
| customer_type | Nilai default guest |
| created_at | Waktu dibuat |
| updated_at | Waktu diperbarui |

### 13.7 orders

| Kolom | Keterangan |
|---|---|
| id | Primary key |
| order_code | Kode pesanan unik |
| guest_customer_id | Relasi ke guest_customers |
| outlet_id | Relasi ke outlets |
| table_id | Relasi ke restaurant_tables |
| total_price | Total harga pesanan |
| order_status | Status pesanan |
| payment_status | Status pembayaran |
| created_at | Waktu dibuat |
| updated_at | Waktu diperbarui |

### 13.8 order_items

| Kolom | Keterangan |
|---|---|
| id | Primary key |
| order_id | Relasi ke orders |
| menu_id | Relasi ke menus |
| qty | Jumlah item |
| price | Harga satuan saat transaksi |
| subtotal | Total harga item |
| note | Catatan item |
| created_at | Waktu dibuat |
| updated_at | Waktu diperbarui |

### 13.9 payments

| Kolom | Keterangan |
|---|---|
| id | Primary key |
| order_id | Relasi ke orders |
| midtrans_order_id | ID order untuk Midtrans |
| payment_method | QRIS |
| amount | Nominal pembayaran |
| transaction_status | Status transaksi dari Midtrans |
| payment_status | Status pembayaran internal |
| paid_at | Waktu pembayaran berhasil |
| raw_response | Response Midtrans |
| created_at | Waktu dibuat |
| updated_at | Waktu diperbarui |

### 13.10 invoices

| Kolom | Keterangan |
|---|---|
| id | Primary key |
| order_id | Relasi ke orders |
| invoice_number | Nomor nota |
| invoice_token | Token akses nota |
| invoice_url | URL nota digital |
| sent_to_email | Email tujuan nota |
| sent_at | Waktu nota dikirim |
| created_at | Waktu dibuat |
| updated_at | Waktu diperbarui |

---

## 14. Tech Stack

| Komponen | Teknologi |
|---|---|
| Backend | Laravel |
| Frontend | Blade Laravel |
| Database master | MySQL, `app_master` |
| Database client | MySQL, `tenant_cafea` |
| Auth internal | Session Laravel |
| Payment gateway | Midtrans |
| Payment method | QRIS |
| Styling | CSS, Bootstrap, atau Tailwind sesuai kebutuhan |
| Server | Hosting yang mendukung Laravel, MySQL, HTTPS, subdomain, dan callback Midtrans |
| Version control | Git |

---

## 15. Payment Flow Midtrans QRIS Cafe A

1. Pelanggan Cafe A checkout.
2. Sistem membuat order di database `tenant_cafea` dengan status payment `pending`.
3. Sistem membaca konfigurasi Midtrans Cafe A dari database master.
4. Sistem mengirim request transaksi ke Midtrans.
5. Midtrans mengembalikan data QRIS.
6. Sistem menampilkan QRIS ke pelanggan.
7. Pelanggan membayar QRIS.
8. Midtrans mengirim callback atau notifikasi ke sistem.
9. Sistem memverifikasi notifikasi Midtrans.
10. Sistem memperbarui status pembayaran di database `tenant_cafea`.
11. Jika pembayaran berhasil, pesanan diteruskan ke kitchen display Cafe A.
12. Sistem membuat nota digital Cafe A.

---

## 16. Status Sistem

### 16.1 Status Pesanan

| Status | Arti |
|---|---|
| pending | Pesanan dibuat, pembayaran belum berhasil |
| paid | Pembayaran berhasil, pesanan siap diproses |
| processing | Pesanan sedang diproses dapur |
| completed | Pesanan selesai |
| cancelled | Pesanan dibatalkan |

### 16.2 Status Pembayaran

| Status | Arti |
|---|---|
| unpaid | Belum dibayar |
| pending | Menunggu pembayaran Midtrans |
| paid | Pembayaran berhasil |
| failed | Pembayaran gagal |
| expired | Pembayaran kedaluwarsa |

---

## 17. Hak Akses Role Cafe A

| Fitur | Pelanggan | Kasir | Dapur | Admin Cafe A | Owner Cafe A | Super Admin |
|---|---|---|---|---|---|---|
| Scan QR | Ya | Tidak | Tidak | Tidak | Tidak | Tidak |
| Melihat menu | Ya | Ya | Tidak | Ya | Tidak | Tidak |
| Membuat pesanan | Ya | Ya | Tidak | Tidak | Tidak | Tidak |
| Membayar QRIS | Ya | Tidak | Tidak | Tidak | Tidak | Tidak |
| Melihat pesanan masuk | Tidak | Ya | Ya | Ya | Tidak | Tidak |
| Mengubah status dapur | Tidak | Tidak | Ya | Ya | Tidak | Tidak |
| Kelola menu | Tidak | Tidak | Tidak | Ya | Tidak | Tidak |
| Kelola meja dan QR | Tidak | Tidak | Tidak | Ya | Tidak | Tidak |
| Kelola user Cafe A | Tidak | Tidak | Tidak | Ya | Tidak | Tidak |
| Melihat laporan Cafe A | Tidak | Tidak | Tidak | Ya | Ya | Tidak |
| Kelola tenant Cafe A | Tidak | Tidak | Tidak | Tidak | Tidak | Ya |
| Kelola subdomain Cafe A | Tidak | Tidak | Tidak | Tidak | Tidak | Ya |
| Kelola database Cafe A | Tidak | Tidak | Tidak | Tidak | Tidak | Ya |

---

## 18. Testing Utama Cafe A

| No | Fitur | Skenario Uji | Hasil yang Diharapkan |
|---|---|---|---|
| 1 | Subdomain | User membuka `cafea.domainkamu.com` | Sistem mengenali tenant Cafe A |
| 2 | Database tenant | User mengakses Cafe A | Sistem memakai database `tenant_cafea` |
| 3 | QR meja | Pelanggan scan QR aktif | Sistem membuka halaman order sesuai meja Cafe A |
| 4 | QR meja | Pelanggan membuka QR tidak aktif | Sistem menampilkan pesan QR tidak valid |
| 5 | Menu | Pelanggan membuka halaman menu | Menu aktif Cafe A tampil |
| 6 | Keranjang | Pelanggan memilih menu dan qty | Item masuk keranjang dan total dihitung |
| 7 | Guest checkout | Nama atau nomor telepon kosong | Sistem menolak checkout |
| 8 | Guest checkout | Data pelanggan valid | Sistem membuat data guest customer Cafe A |
| 9 | Midtrans QRIS | Pelanggan checkout | Sistem generate QRIS |
| 10 | Callback Midtrans | Pembayaran berhasil | Status pembayaran menjadi paid |
| 11 | Kitchen display | Pembayaran berhasil | Pesanan tampil di dapur Cafe A |
| 12 | Status pesanan | Dapur klik diproses | Status berubah menjadi processing |
| 13 | Status pesanan | Dapur klik selesai | Status berubah menjadi completed |
| 14 | Nota digital | Pembayaran berhasil | Sistem membuat nota digital |
| 15 | Role access | Kasir membuka menu admin | Sistem menolak akses |
| 16 | Laporan | Owner filter tanggal | Sistem menampilkan transaksi Cafe A sesuai tanggal |
| 17 | Isolasi data | Cafe A membuka data tenant lain | Sistem menolak atau tidak menampilkan data tenant lain |

---

## 19. Deployment Target Cafe A

1. Aplikasi dideploy pada hosting yang mendukung Laravel dan MySQL.
2. Hosting harus mendukung PHP sesuai versi Laravel yang digunakan.
3. Hosting harus menyediakan minimal 2 database MySQL, yaitu database master dan database Cafe A.
4. Hosting harus mendukung subdomain `cafea.domainkamu.com`.
5. Hosting harus mendukung HTTPS untuk subdomain Cafe A.
6. Hosting harus mendukung konfigurasi environment file.
7. Hosting harus dapat menerima callback atau notifikasi dari Midtrans.
8. Database harus dapat dimigrasikan melalui migration Laravel.
9. File `.env` tidak boleh terbuka untuk publik.
10. Debug mode harus dimatikan pada production.
11. Route callback Midtrans harus dapat diakses publik.
12. Backup database `app_master` dan `tenant_cafea` harus dilakukan berkala.

---

## 20. Kriteria Berhasil Cafe A

Project Cafe A dianggap berhasil jika seluruh kondisi berikut terpenuhi.

1. Subdomain `cafea.domainkamu.com` dapat diakses.
2. Sistem dapat mengenali tenant Cafe A dari subdomain.
3. Sistem dapat memakai database `tenant_cafea`.
4. Pelanggan dapat scan QR meja Cafe A.
5. Sistem dapat mengenali meja, area, dan outlet dari QR Cafe A.
6. Pelanggan dapat melihat menu digital Cafe A.
7. Pelanggan dapat memilih menu dan memasukkannya ke keranjang.
8. Pelanggan dapat checkout tanpa login.
9. Sistem dapat menyimpan data pelanggan tamu Cafe A.
10. Sistem dapat generate QRIS Midtrans.
11. Sistem dapat menerima status pembayaran dari Midtrans.
12. Pesanan yang sudah dibayar dapat tampil pada dashboard kasir Cafe A.
13. Pesanan yang sudah dibayar dapat tampil pada kitchen display Cafe A.
14. Dapur dapat mengubah status pesanan.
15. Sistem dapat membuat nota digital.
16. Admin Cafe A dapat mengelola menu, meja, QR, dan user.
17. Owner Cafe A dapat melihat laporan transaksi.
18. Hak akses berjalan sesuai role.
19. Data Cafe A tidak bercampur dengan data client lain.

---

## 21. Urutan Pengembangan Cafe A

1. Setup project Laravel utama.
2. Setup database master `app_master`.
3. Buat migration database master.
4. Buat tabel `tenants`, `tenant_domains`, `tenant_database_configs`, `tenant_payment_configs`, dan `subscriptions`.
5. Buat data tenant Cafe A di database master.
6. Setup subdomain `cafea.domainkamu.com`.
7. Setup database client `tenant_cafea`.
8. Buat mekanisme deteksi subdomain.
9. Buat mekanisme koneksi database tenant berdasarkan subdomain.
10. Jalankan migration untuk database `tenant_cafea`.
11. Setup layout Blade.
12. Buat auth internal untuk kasir, dapur, admin, dan owner Cafe A.
13. Buat role access.
14. Buat seeder role dan admin awal Cafe A.
15. Buat manajemen outlet Cafe A.
16. Buat manajemen area meja.
17. Buat manajemen meja.
18. Buat generate QR meja Cafe A.
19. Buat manajemen kategori menu.
20. Buat manajemen menu.
21. Buat halaman order dari QR.
22. Buat keranjang pesanan.
23. Buat guest checkout.
24. Buat integrasi Midtrans QRIS.
25. Buat callback Midtrans.
26. Buat dashboard kasir.
27. Buat kitchen display.
28. Buat status pesanan.
29. Buat nota digital.
30. Buat laporan transaksi dasar.
31. Lakukan testing subdomain dan isolasi database.
32. Lakukan testing fitur utama.
33. Deploy ke hosting Laravel dan MySQL.
34. Aktifkan HTTPS untuk subdomain Cafe A.
35. Lakukan final testing production.

---

## 22. Catatan Pengembangan

1. Gunakan token QR yang acak dan sulit ditebak.
2. Jangan gunakan `table_id` langsung pada URL pelanggan.
3. Simpan harga menu pada `order_items` agar riwayat transaksi tidak berubah saat harga menu berubah.
4. Verifikasi callback Midtrans sebelum mengubah status pembayaran.
5. Pisahkan status pesanan dan status pembayaran.
6. Gunakan middleware untuk membatasi akses role.
7. Gunakan migration dan seeder agar setup database lebih rapi.
8. Pastikan halaman pelanggan nyaman digunakan melalui smartphone.
9. Pastikan kitchen display mudah dibaca oleh staf dapur.
10. Pastikan laporan dapat difilter berdasarkan tanggal.
11. Jangan hardcode database Cafe A di controller.
12. Koneksi database tenant harus diatur dari middleware berdasarkan subdomain.
13. Konfigurasi Midtrans Cafe A harus disimpan aman dan terenkripsi.
14. Pastikan callback Midtrans dapat mengenali transaksi milik Cafe A.
15. Backup database master dan database Cafe A secara berkala.
16. Super admin tidak boleh mengakses data transaksi Cafe A tanpa kebutuhan teknis yang jelas.
17. Setiap query operasional Cafe A harus berjalan pada database `tenant_cafea`.

---

## 23. Ringkasan Implementasi Cafe A

```text
Subdomain:
cafea.domainkamu.com

Database master:
app_master

Database client:
tenant_cafea

Alur utama:
Scan QR meja Cafe A
↓
Pilih menu Cafe A
↓
Guest checkout
↓
Generate QRIS Midtrans
↓
Pembayaran berhasil
↓
Pesanan masuk kasir dan dapur Cafe A
↓
Dapur proses pesanan
↓
Nota digital dibuat
↓
Laporan Cafe A tercatat
```


---

# TAMBAHAN PROFIL BISNIS CAFE A

Bagian ini digunakan untuk menyimpan data identitas, branding, lokasi, menu, operasional, dan informasi bisnis yang dimiliki oleh Cafe A. Data ini menjadi acuan tampilan halaman pelanggan, dashboard admin, nota digital, QR meja, dan laporan internal.

---

## 24. Profil Brand Cafe A

| Bagian | Isi |
|---|---|
| Nama brand | Cafe A |
| Nama legal usaha | Cafe A Indonesia |
| Jenis usaha | Cafe dan restoran dine-in |
| Konsep usaha | Cafe casual untuk makan, minum, nongkrong, kerja ringan, dan pertemuan santai |
| Segmentasi pelanggan | Pelajar, mahasiswa, pekerja, keluarga, komunitas, dan pelanggan umum |
| Positioning | Cafe lokal dengan pemesanan digital cepat, menu terjangkau, dan pelayanan rapi |
| Gaya komunikasi brand | Ramah, sederhana, cepat, dan modern |
| Slogan awal | Scan, Order, Enjoy |
| Deskripsi singkat | Cafe A adalah cafe lokal yang menyediakan makanan ringan, makanan utama, kopi, non-kopi, dan minuman segar dengan sistem pemesanan digital berbasis QR meja. |

---

## 25. Identitas Visual Cafe A

| Bagian | Isi |
|---|---|
| Logo | File logo Cafe A dapat diunggah melalui dashboard admin |
| Warna utama | Cokelat kopi |
| Warna sekunder | Krem |
| Warna aksen | Hijau tua atau emas lembut |
| Font utama | Sans-serif modern |
| Gaya tampilan | Bersih, hangat, mudah dibaca, dan nyaman untuk mobile |
| Ikon utama | Kopi, meja, QR, keranjang, pembayaran, nota |
| Gambar pendukung | Foto menu, foto interior, foto banner promosi |

### 25.1 Penggunaan Identitas Visual

1. Logo Cafe A tampil pada halaman menu digital.
2. Warna utama digunakan pada tombol checkout, header, dan elemen penting.
3. Warna sekunder digunakan sebagai latar halaman.
4. Foto menu harus jelas dan ringan agar halaman pelanggan cepat dibuka.
5. Tampilan pelanggan harus diprioritaskan untuk smartphone.

---

## 26. Data Outlet Cafe A

| Bagian | Isi |
|---|---|
| Nama outlet | Cafe A Main Outlet |
| Kode outlet | CFA-001 |
| Alamat | Jl. Contoh Raya No. 10, Kota Contoh |
| Kota | Kota Contoh |
| Provinsi | Provinsi Contoh |
| Negara | Indonesia |
| Nomor telepon outlet | 08xxxxxxxxxx |
| Email outlet | cafea@example.com |
| Jam operasional | 10.00 sampai 22.00 |
| Status outlet | Active |
| Tipe layanan | Dine-in berbasis QR meja |
| Catatan lokasi | Lokasi dapat diubah melalui dashboard admin |

---

## 27. Area dan Meja Cafe A

### 27.1 Area Meja

| Kode Area | Nama Area | Keterangan |
|---|---|---|
| IN-01 | Indoor | Area dalam ruangan |
| OUT-01 | Outdoor | Area luar ruangan |
| VIP-01 | VIP Room | Area khusus reservasi atau private group |

### 27.2 Daftar Meja Awal

| Kode Meja | Area | Kapasitas | Status |
|---|---|---:|---|
| A1 | Indoor | 2 | Active |
| A2 | Indoor | 2 | Active |
| A3 | Indoor | 4 | Active |
| A4 | Indoor | 4 | Active |
| B1 | Outdoor | 2 | Active |
| B2 | Outdoor | 4 | Active |
| B3 | Outdoor | 4 | Active |
| VIP1 | VIP Room | 6 | Active |

### 27.3 Aturan QR Meja

1. Setiap meja memiliki satu QR aktif.
2. QR menampilkan halaman order sesuai meja.
3. QR tidak boleh memakai ID meja langsung pada URL.
4. QR harus memakai token acak.
5. QR dapat dicetak ulang oleh admin.
6. QR lama dapat dinonaktifkan jika rusak, hilang, atau berpindah tempat.

---

## 28. Kategori Menu Cafe A

| Kode Kategori | Nama Kategori | Keterangan |
|---|---|---|
| COF | Coffee | Menu kopi panas dan dingin |
| NCF | Non-Coffee | Minuman tanpa kopi |
| TEA | Tea Series | Teh dan minuman berbasis teh |
| FOD | Main Course | Makanan utama |
| SNK | Snack | Makanan ringan |
| DST | Dessert | Makanan manis |
| PKG | Paket Hemat | Paket bundling makanan dan minuman |

---

## 29. Daftar Menu Awal Cafe A

### 29.1 Coffee

| Kode Menu | Nama Menu | Harga | Status | Deskripsi Singkat |
|---|---|---:|---|---|
| COF-001 | Americano Hot | 18000 | Active | Kopi hitam panas dengan rasa ringan dan bersih |
| COF-002 | Americano Ice | 20000 | Active | Kopi hitam dingin yang segar |
| COF-003 | Cafe Latte Hot | 23000 | Active | Espresso dan susu panas |
| COF-004 | Cafe Latte Ice | 25000 | Active | Espresso, susu, dan es |
| COF-005 | Cappuccino Hot | 23000 | Active | Espresso, susu, dan foam |
| COF-006 | Kopi Susu Gula Aren | 24000 | Active | Kopi susu dengan gula aren |
| COF-007 | Caramel Latte Ice | 28000 | Active | Latte dingin dengan rasa karamel |
| COF-008 | Vanilla Latte Ice | 28000 | Active | Latte dingin dengan rasa vanila |

### 29.2 Non-Coffee

| Kode Menu | Nama Menu | Harga | Status | Deskripsi Singkat |
|---|---|---:|---|---|
| NCF-001 | Matcha Latte Hot | 26000 | Active | Matcha dan susu panas |
| NCF-002 | Matcha Latte Ice | 28000 | Active | Matcha dan susu dingin |
| NCF-003 | Chocolate Hot | 24000 | Active | Cokelat panas |
| NCF-004 | Chocolate Ice | 26000 | Active | Cokelat dingin |
| NCF-005 | Red Velvet Ice | 27000 | Active | Minuman red velvet dingin |
| NCF-006 | Taro Latte Ice | 26000 | Active | Minuman taro dengan susu |

### 29.3 Tea Series

| Kode Menu | Nama Menu | Harga | Status | Deskripsi Singkat |
|---|---|---:|---|---|
| TEA-001 | Lemon Tea Ice | 18000 | Active | Teh lemon dingin |
| TEA-002 | Lychee Tea Ice | 22000 | Active | Teh leci dingin |
| TEA-003 | Peach Tea Ice | 22000 | Active | Teh peach dingin |
| TEA-004 | Thai Tea Ice | 23000 | Active | Thai tea dingin dengan susu |

### 29.4 Main Course

| Kode Menu | Nama Menu | Harga | Status | Deskripsi Singkat |
|---|---|---:|---|---|
| FOD-001 | Nasi Ayam Sambal Matah | 32000 | Active | Nasi ayam dengan sambal matah |
| FOD-002 | Rice Bowl Chicken Teriyaki | 35000 | Active | Rice bowl ayam teriyaki |
| FOD-003 | Rice Bowl Beef Blackpepper | 39000 | Active | Rice bowl beef blackpepper |
| FOD-004 | Spaghetti Bolognese | 36000 | Active | Pasta dengan saus bolognese |
| FOD-005 | Spaghetti Carbonara | 38000 | Active | Pasta creamy carbonara |
| FOD-006 | Nasi Goreng Cafe A | 30000 | Active | Nasi goreng khas Cafe A |

### 29.5 Snack

| Kode Menu | Nama Menu | Harga | Status | Deskripsi Singkat |
|---|---|---:|---|---|
| SNK-001 | French Fries | 20000 | Active | Kentang goreng |
| SNK-002 | Chicken Wings | 32000 | Active | Sayap ayam berbumbu |
| SNK-003 | Onion Rings | 22000 | Active | Onion ring renyah |
| SNK-004 | Cireng Bumbu Rujak | 20000 | Active | Cireng dengan bumbu rujak |
| SNK-005 | Roti Bakar Cokelat Keju | 24000 | Active | Roti bakar cokelat keju |

### 29.6 Dessert

| Kode Menu | Nama Menu | Harga | Status | Deskripsi Singkat |
|---|---|---:|---|---|
| DST-001 | Brownies Slice | 22000 | Active | Potongan brownies |
| DST-002 | Cheesecake Slice | 32000 | Active | Potongan cheesecake |
| DST-003 | Pancake Honey Butter | 30000 | Active | Pancake dengan madu dan butter |

### 29.7 Paket Hemat

| Kode Menu | Nama Menu | Harga | Status | Deskripsi Singkat |
|---|---|---:|---|---|
| PKG-001 | Paket Ngopi Hemat | 40000 | Active | Kopi susu gula aren dan french fries |
| PKG-002 | Paket Lunch Cafe A | 55000 | Active | Rice bowl dan lemon tea |
| PKG-003 | Paket Nongkrong Berdua | 85000 | Active | Dua minuman dan satu snack |

---

## 30. Data Menu yang Wajib Disimpan

Setiap menu Cafe A minimal memiliki data berikut.

| Data | Keterangan |
|---|---|
| Nama menu | Nama yang tampil pada halaman pelanggan |
| Kode menu | Kode unik untuk kebutuhan internal |
| Kategori | Relasi ke kategori menu |
| Harga | Harga jual menu |
| Deskripsi | Penjelasan singkat menu |
| Foto menu | Gambar menu untuk halaman pelanggan |
| Status aktif | Active atau inactive |
| Stok tampil | Available atau sold out |
| Estimasi proses | Estimasi waktu pembuatan |
| Catatan alergi | Informasi bahan tertentu jika diperlukan |
| Created at | Waktu menu dibuat |
| Updated at | Waktu menu diperbarui |

---

## 31. Pengaturan Tampilan Halaman Pelanggan

| Bagian | Isi |
|---|---|
| Header | Logo Cafe A, nama cafe, dan nomor meja |
| Banner | Promo atau foto utama Cafe A |
| Kategori menu | Coffee, Non-Coffee, Tea, Main Course, Snack, Dessert, Paket Hemat |
| Card menu | Foto, nama, deskripsi, harga, tombol tambah |
| Keranjang | Daftar item, qty, subtotal, total |
| Checkout | Nama, nomor telepon, email, catatan pesanan |
| Pembayaran | QRIS Midtrans |
| Status order | Pending, paid, processing, completed |
| Nota digital | Link nota setelah pembayaran berhasil |

---

## 32. Pengaturan Nota Digital Cafe A

Nota digital harus menampilkan informasi berikut.

| Data | Keterangan |
|---|---|
| Logo Cafe A | Logo brand |
| Nama outlet | Cafe A Main Outlet |
| Alamat outlet | Alamat Cafe A |
| Nomor nota | Nomor invoice |
| Kode order | Kode pesanan |
| Nama pelanggan | Data dari guest checkout |
| Nomor meja | Meja dari QR |
| Waktu transaksi | Tanggal dan jam order |
| Daftar menu | Item, qty, harga, subtotal |
| Total pembayaran | Total order |
| Metode pembayaran | QRIS Midtrans |
| Status pembayaran | Paid, pending, failed, atau expired |
| Link nota | URL nota dengan token acak |

---

## 33. Pengaturan Admin Cafe A

Admin Cafe A dapat mengatur data berikut.

| Modul | Data yang Dikelola |
|---|---|
| Profil cafe | Nama brand, logo, slogan, deskripsi |
| Outlet | Nama outlet, alamat, nomor telepon, email, jam operasional |
| Area meja | Indoor, outdoor, VIP, dan area lain |
| Meja | Nomor meja, kode meja, kapasitas, status |
| QR meja | Generate, cetak, regenerate, nonaktifkan |
| Kategori menu | Nama kategori, urutan tampil, status |
| Menu | Nama, harga, deskripsi, foto, status aktif |
| User | Kasir, dapur, admin, owner |
| Laporan | Filter transaksi dan ringkasan penjualan |

---

## 34. Pengaturan Kontak dan Media Sosial Cafe A

| Bagian | Isi |
|---|---|
| WhatsApp | 08xxxxxxxxxx |
| Instagram | @cafea.id |
| TikTok | @cafea.id |
| Email | cafea@example.com |
| Website | cafea.domainkamu.com |
| Google Maps | Link lokasi Cafe A |
| Link kritik dan saran | Form feedback pelanggan |

---

## 35. Pengaturan Operasional Cafe A

| Bagian | Isi |
|---|---|
| Jam buka | 10.00 |
| Jam tutup | 22.00 |
| Last order | 21.30 |
| Estimasi proses minuman | 5 sampai 10 menit |
| Estimasi proses makanan | 10 sampai 20 menit |
| Metode pembayaran | QRIS Midtrans |
| Tipe order | Dine-in |
| Pajak | Dapat diatur admin |
| Service charge | Dapat diatur admin |
| Status operasional | Open atau closed |

### 35.1 Business Rules Operasional

1. Pelanggan hanya dapat checkout saat status cafe open.
2. Sistem menampilkan pesan closed jika pelanggan scan QR di luar jam operasional.
3. Menu sold out tidak dapat dipesan.
4. Admin dapat mengubah status menu menjadi active, inactive, atau sold out.
5. Last order dapat digunakan untuk membatasi checkout sebelum cafe tutup.
6. Pajak dan service charge dapat diaktifkan atau dinonaktifkan melalui pengaturan outlet.

---

## 36. Tambahan Tabel Database untuk Profil Cafe A

### 36.1 cafe_profiles

| Kolom | Keterangan |
|---|---|
| id | Primary key |
| brand_name | Nama brand |
| legal_name | Nama legal usaha |
| slogan | Slogan cafe |
| description | Deskripsi singkat cafe |
| logo_path | Path file logo |
| primary_color | Warna utama |
| secondary_color | Warna sekunder |
| accent_color | Warna aksen |
| instagram_url | Link Instagram |
| tiktok_url | Link TikTok |
| whatsapp_number | Nomor WhatsApp |
| google_maps_url | Link Google Maps |
| created_at | Waktu dibuat |
| updated_at | Waktu diperbarui |

### 36.2 cafe_operational_settings

| Kolom | Keterangan |
|---|---|
| id | Primary key |
| outlet_id | Relasi ke outlets |
| open_time | Jam buka |
| close_time | Jam tutup |
| last_order_time | Jam terakhir order |
| is_open | Status buka |
| tax_rate | Persentase pajak jika digunakan |
| service_charge_rate | Persentase service charge jika digunakan |
| created_at | Waktu dibuat |
| updated_at | Waktu diperbarui |

### 36.3 menu_images

| Kolom | Keterangan |
|---|---|
| id | Primary key |
| menu_id | Relasi ke menus |
| image_path | Path gambar menu |
| is_primary | Penanda gambar utama |
| created_at | Waktu dibuat |
| updated_at | Waktu diperbarui |

---

## 37. Tambahan Kolom pada Tabel menus

Tabel `menus` sebaiknya memiliki kolom berikut.

| Kolom | Keterangan |
|---|---|
| id | Primary key |
| category_id | Relasi ke categories |
| menu_code | Kode menu |
| menu_name | Nama menu |
| description | Deskripsi menu |
| price | Harga jual |
| image_path | Foto utama menu |
| preparation_time | Estimasi waktu proses |
| stock_status | available atau sold_out |
| is_active | Status tampil |
| created_at | Waktu dibuat |
| updated_at | Waktu diperbarui |

---

## 38. Tambahan Kriteria Berhasil

Tambahan ini melengkapi kriteria berhasil Cafe A.

1. Admin dapat mengisi profil brand Cafe A.
2. Logo dan warna brand Cafe A tampil pada halaman pelanggan.
3. Admin dapat mengatur alamat dan jam operasional Cafe A.
4. Sistem dapat menolak checkout saat cafe closed.
5. Admin dapat mengelola kategori menu Cafe A.
6. Admin dapat mengelola daftar menu Cafe A.
7. Admin dapat mengatur status menu active, inactive, atau sold out.
8. Pelanggan hanya melihat menu yang aktif dan tersedia.
9. Nota digital menampilkan identitas Cafe A.
10. Halaman pelanggan menampilkan nomor meja Cafe A dengan jelas.

---

## 39. Ringkasan Data Milik Cafe A

```text
Brand:
Cafe A

Subdomain:
cafea.domainkamu.com

Database:
tenant_cafea

Outlet:
Cafe A Main Outlet

Alamat:
Jl. Contoh Raya No. 10, Kota Contoh

Area:
Indoor, Outdoor, VIP Room

Menu:
Coffee, Non-Coffee, Tea Series, Main Course, Snack, Dessert, Paket Hemat

Payment:
QRIS Midtrans

Order type:
Dine-in berbasis QR meja
```
