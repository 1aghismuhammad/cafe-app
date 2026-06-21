# CHECKPOINT 12 - CETAK NOTA PELANGGAN

## Status

Checkpoint 12 selesai dan sudah diuji melalui browser.

## Gambaran Umum

Checkpoint 12 menambahkan fitur cetak nota pelanggan pada sisi kasir. Setelah kasir membuka detail order, kasir dapat menekan tombol Cetak Nota untuk membuka halaman nota yang siap dicetak menggunakan fitur print browser.

## Fitur yang Dibuat

- Tombol Cetak Nota pada halaman detail order
- Halaman nota pelanggan
- Tombol Print Nota
- Tampilan khusus print agar tombol tidak ikut tercetak
- Data nota diambil dari order, order_items, meja, outlet, dan cafe profile

## File Baru

```text
app/Http/Controllers/Cashier/ReceiptController.php
resources/views/kasir/orders/receipt.blade.php
.progres/checkpoint-12.md