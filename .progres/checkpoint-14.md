# Checkpoint 14 - Integrasi Midtrans QRIS

## Status

**Selesai untuk MVP lokal.**

Integrasi Midtrans QRIS sudah berhasil berjalan pada alur utama: pelanggan checkout, sistem membuat transaksi QRIS, QRIS tampil di halaman pelanggan, transaksi diuji melalui Midtrans Sandbox Simulator, status pembayaran dicek dari aplikasi, lalu database otomatis berubah menjadi `paid` dan order masuk ke antrean kasir sebagai `pending`.

Catatan: webhook otomatis melalui `/midtrans/notification` sudah disiapkan secara route dan controller, tetapi pengujian webhook penuh ditunda sampai tahap deploy atau sampai aplikasi memiliki URL publik. Saat ini pengujian berhasil menggunakan fallback **Cek Status Pembayaran** yang memanggil Midtrans Status API.

---

## Tujuan Checkpoint

Checkpoint ini bertujuan mengubah pembayaran manual menjadi pembayaran digital berbasis QRIS untuk MVP. Fokus utama bukan payment system production-ready, tetapi memastikan alur bisnis inti berjalan:

1. Pelanggan checkout dari QR meja.
2. Sistem membuat order dengan status awal menunggu pembayaran.
3. Sistem membuat transaksi QRIS ke Midtrans Sandbox.
4. Pelanggan melihat QRIS dan melakukan simulasi pembayaran.
5. Sistem mengecek status pembayaran ke Midtrans.
6. Jika transaksi sukses, status order otomatis berubah menjadi lunas dan masuk antrean kasir.

---

## Ringkasan Alur yang Berhasil

```text
Pelanggan checkout
→ Order dibuat
→ Payment QRIS dibuat di Midtrans
→ QRIS tampil di halaman pelanggan
→ Status awal: awaiting_payment / unpaid
→ QRIS dibayar melalui Midtrans Sandbox Simulator
→ Pelanggan klik Cek Status Pembayaran
→ Sistem memanggil Midtrans Status API
→ Payment berubah menjadi settlement
→ Order berubah menjadi pending / paid
→ Kasir melihat order sebagai lunas dan siap diproses
```

---

## Hasil Pengujian

Pengujian dilakukan menggunakan Midtrans Sandbox QRIS Simulator.

### Data Order Uji

| Item | Nilai |
|---|---|
| Order Code | `ORD-20260703070350-116` |
| Customer | `ilyas` |
| Meja | `3` |
| Total | `Rp64.000` |
| Payment Type | `qris` |
| Transaction ID | `f6e910fe-e9a2-4c13-a5a1-1079d293dc57` |

### Kondisi Sebelum Pembayaran

| Tabel | Field | Nilai |
|---|---|---|
| `payments` | `transaction_status` | `pending` |
| `payments` | `fraud_status` | `accept` |
| `payments` | `qr_url` | tersedia |
| `payments` | `paid_at` | `null` |
| `orders` | `status` | `awaiting_payment` |
| `orders` | `payment_status` | `unpaid` |

### Kondisi Setelah Simulasi Pembayaran dan Cek Status

| Tabel | Field | Nilai |
|---|---|---|
| `payments` | `transaction_status` | `settlement` |
| `payments` | `fraud_status` | `accept` |
| `payments` | `paid_at` | terisi |
| `payments` | `raw_notification` | terisi dari Midtrans Status API |
| `orders` | `status` | `pending` |
| `orders` | `payment_status` | `paid` |

### Validasi Tampilan

| Halaman | Hasil |
|---|---|
| Halaman pelanggan | Menampilkan halaman “Pesanan Berhasil” setelah status pembayaran dikonfirmasi |
| Halaman kasir | Order terbaru tampil sebagai `Pending` dan pembayaran `Paid` |
| Midtrans Sandbox | Transaksi QRIS tampil sebagai `PAID` |

---

## File yang Ditambahkan

| File | Fungsi |
|---|---|
| `config/midtrans.php` | Menyimpan konfigurasi Midtrans dari `.env` |
| `app/Models/Payment.php` | Model untuk data pembayaran Midtrans |
| `app/Services/MidtransQrisService.php` | Service utama untuk create QRIS, cek status, webhook, signature, dan mapping status |
| `app/Http/Controllers/Customer/PaymentController.php` | Controller halaman QRIS pelanggan dan cek status pembayaran |
| `app/Http/Controllers/Webhook/MidtransWebhookController.php` | Controller endpoint webhook Midtrans |
| `database/migrations/2026_07_03_045227_create_payments_table.php` | Migration tabel `payments` |
| `resources/views/customer/payment-qris.blade.php` | Halaman QRIS pelanggan |

---

## File yang Diubah

| File | Perubahan |
|---|---|
| `.env` | Menambahkan konfigurasi Midtrans Sandbox |
| `.env.example` | Disiapkan untuk konfigurasi Midtrans |
| `app/Models/Order.php` | Menambahkan relasi `payment()` |
| `app/Http/Controllers/Customer/CheckoutController.php` | Mengubah flow checkout agar membuat QRIS dan redirect ke halaman pembayaran |
| `app/Http/Controllers/Cashier/OrderController.php` | Menambahkan status `awaiting_payment` dan load relasi `payment` |
| `app/Http/Controllers/Cashier/PaymentController.php` | Menambahkan method cek status Midtrans dari sisi kasir |
| `routes/web.php` | Menambahkan route QRIS customer, route webhook Midtrans, dan route cek status Midtrans kasir |
| `bootstrap/app.php` | Mengecualikan `/midtrans/notification` dari CSRF validation |
| `resources/views/kasir/orders/show.blade.php` | Menambahkan card informasi pembayaran QRIS dan tombol cek status Midtrans |

---

## Konfigurasi Environment

Konfigurasi berikut ditambahkan pada `.env`:

```env
MIDTRANS_SERVER_KEY=SB-Mid-server-xxxxxxxxxxxxxxxx
MIDTRANS_CLIENT_KEY=SB-Mid-client-xxxxxxxxxxxxxxxx
MIDTRANS_IS_PRODUCTION=false
MIDTRANS_SANDBOX_BASE_URL=https://api.sandbox.midtrans.com
MIDTRANS_PRODUCTION_BASE_URL=https://api.midtrans.com
```

Catatan:
- Key yang digunakan adalah **Sandbox Key**.
- `MIDTRANS_IS_PRODUCTION=false` karena pengujian masih lokal.
- `APP_URL=http://127.0.0.1:8000` masih cukup untuk pengujian halaman lokal dan fallback cek status.
- Webhook penuh membutuhkan URL publik, sehingga akan diuji setelah deploy atau menggunakan tunnel.

---

## Struktur Database Baru

Tabel baru: `payments`

| Kolom | Fungsi |
|---|---|
| `id` | Primary key |
| `order_id` | Relasi ke tabel `orders` |
| `midtrans_order_id` | Order ID unik yang dikirim ke Midtrans |
| `transaction_id` | ID transaksi dari Midtrans |
| `payment_type` | Metode pembayaran, saat ini `qris` |
| `gross_amount` | Nominal pembayaran |
| `currency` | Mata uang, default `IDR` |
| `transaction_status` | Status transaksi dari Midtrans |
| `fraud_status` | Status fraud dari Midtrans |
| `qr_url` | URL QRIS dari Midtrans |
| `raw_response` | Response awal saat transaksi dibuat |
| `raw_notification` | Response status/webhook terakhir |
| `paid_at` | Waktu pembayaran berhasil |
| `expired_at` | Waktu expired jika nanti digunakan |
| `created_at`, `updated_at` | Timestamp Laravel |

---

## Mapping Status

| Status Midtrans | Status Payment Internal | Status Order | Status Bayar Order |
|---|---|---|---|
| `pending` | `pending` | `awaiting_payment` | `unpaid` |
| `settlement` | `settlement` | `pending` | `paid` |
| `capture` + fraud `accept` | `capture` | `pending` | `paid` |
| `expire` | `expire` | `cancelled` | `cancelled` |
| `cancel` | `cancel` | `cancelled` | `cancelled` |
| `deny` | `deny` | `cancelled` | `cancelled` |
| `failure` | `failure` | `awaiting_payment` | `unpaid` |

---

## Route yang Ditambahkan

### Customer Payment

```php
Route::get('/order/table/{token}/payment/qris/{order}', [CustomerPaymentController::class, 'showQris'])
    ->name('customer.payment.qris.show');

Route::post('/order/table/{token}/payment/qris/{order}/check', [CustomerPaymentController::class, 'checkStatus'])
    ->name('customer.payment.qris.check');
```

### Midtrans Notification

```php
Route::post('/midtrans/notification', [MidtransWebhookController::class, 'handle'])
    ->name('midtrans.notification');
```

### Cashier Payment Check

```php
Route::post('/orders/{order}/payment/check-midtrans', [CashierPaymentController::class, 'checkMidtransStatus'])
    ->name('orders.check-midtrans-payment');
```

---

## Validasi Teknis yang Sudah Dilakukan

| Pemeriksaan | Hasil |
|---|---|
| `php artisan migrate` | Berhasil |
| `Schema::hasTable('payments')` | `true` |
| `php -l app/Services/MidtransQrisService.php` | No syntax errors |
| `php -l app/Http/Controllers/Customer/CheckoutController.php` | No syntax errors |
| `php -l app/Http/Controllers/Customer/PaymentController.php` | No syntax errors |
| `php -l app/Http/Controllers/Webhook/MidtransWebhookController.php` | No syntax errors |
| `php -l app/Http/Controllers/Cashier/OrderController.php` | No syntax errors |
| `php -l app/Http/Controllers/Cashier/PaymentController.php` | No syntax errors |
| `php -l bootstrap/app.php` | No syntax errors |
| `php artisan route:list --name=customer.payment` | Route customer payment tersedia |
| `php artisan route:list --name=midtrans` | Route webhook tersedia |
| `php artisan route:list --name=kasir.orders.check-midtrans-payment` | Route cek status kasir tersedia |

---

## Batasan Checkpoint

Checkpoint ini selesai pada level MVP lokal. Beberapa hal berikut belum menjadi target checkpoint ini dan akan dikerjakan pada fase perbaikan/deploy:

1. Pengujian webhook otomatis penuh dari Midtrans ke `/midtrans/notification`.
2. Production activation Midtrans.
3. Refund.
4. Split bill.
5. Partial payment.
6. Settlement report.
7. Rekonsiliasi harian otomatis.
8. Audit log pembayaran.
9. Validasi state machine order yang lebih ketat.
10. Pemisahan final antara pembayaran manual dan QRIS production.

---

## Catatan untuk Checkpoint 15 - Deploy

Pada saat deploy nanti, hal yang wajib dilanjutkan untuk payment QRIS adalah:

1. Gunakan domain HTTPS publik.
2. Ubah `APP_URL` menjadi domain production/staging.
3. Set **Payment Notification URL** di dashboard Midtrans ke:

```text
https://domain-kamu.com/midtrans/notification
```

4. Pastikan route `/midtrans/notification` dapat diakses tanpa login.
5. Pastikan CSRF exclude untuk `midtrans/notification` tetap aktif.
6. Uji webhook otomatis dari Midtrans.
7. Jika sudah production, ganti key sandbox menjadi production key dan set:

```env
MIDTRANS_IS_PRODUCTION=true
```

---

## Kesimpulan

Checkpoint 14 berhasil diselesaikan untuk kebutuhan MVP lokal. Sistem sudah mampu membuat QRIS dinamis dari checkout pelanggan, menyimpan transaksi ke tabel `payments`, membaca status pembayaran dari Midtrans, mengubah pembayaran menjadi `paid`, dan memasukkan order ke antrean kasir sebagai `pending`.

Webhook otomatis belum diuji karena aplikasi masih berjalan di local environment tanpa URL publik. Pengujian webhook akan dilanjutkan pada Checkpoint 15 saat aplikasi sudah dideploy atau tersedia melalui public tunnel.
