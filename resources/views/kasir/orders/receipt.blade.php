<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nota {{ $order->order_code }}</title>

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            padding: 16px;
            background: #f3f4f6;
            font-family: "Courier New", Courier, monospace;
            color: #111;
        }

        .receipt-page {
            width: 80mm;
            margin: 0 auto;
        }

        .receipt {
            background: #fff;
            padding: 10px 12px 16px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.08);
        }

        .center {
            text-align: center;
        }

        .title {
            font-size: 20px;
            font-weight: bold;
            line-height: 1.2;
        }

        .small {
            font-size: 12px;
            line-height: 1.35;
        }

        .line {
            border-top: 1px dashed #000;
            margin: 8px 0;
        }

        .row {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            gap: 8px;
            font-size: 12px;
            line-height: 1.4;
            margin-bottom: 2px;
        }

        .row .left {
            flex: 1;
            text-align: left;
        }

        .row .right {
            flex-shrink: 0;
            text-align: right;
            white-space: nowrap;
        }

        .item {
            margin-bottom: 8px;
            font-size: 12px;
            line-height: 1.35;
        }

        .item-name {
            font-weight: bold;
            word-break: break-word;
        }

        .item-sub {
            display: flex;
            justify-content: space-between;
            gap: 8px;
        }

        .item-note {
            font-size: 11px;
            margin-top: 2px;
            color: #333;
        }

        .section-title {
            font-size: 12px;
            font-weight: bold;
            margin-bottom: 4px;
        }

        .total-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 16px;
            font-weight: bold;
            margin: 6px 0;
        }

        .footer-text {
            text-align: center;
            font-size: 12px;
            line-height: 1.4;
            margin-top: 8px;
        }

        .actions {
            display: flex;
            gap: 8px;
            margin-top: 12px;
        }

        .btn {
            flex: 1;
            border: none;
            padding: 10px 12px;
            font-size: 12px;
            font-weight: bold;
            text-decoration: none;
            text-align: center;
            cursor: pointer;
        }

        .btn-print {
            background: #111827;
            color: white;
        }

        .btn-back {
            background: #e5e7eb;
            color: #111827;
        }

        @media print {
            body {
                background: #fff;
                padding: 0;
            }

            .receipt-page {
                width: 80mm;
                margin: 0 auto;
            }

            .receipt {
                box-shadow: none;
                padding: 0;
            }

            .no-print {
                display: none !important;
            }

            @page {
                size: 80mm auto;
                margin: 5mm;
            }
        }
    </style>
</head>
<body>
    @php
        $outlet = $order->outlet ?? $order->restaurantTable?->outlet;
        $cafeName = $cafeProfile->brand_name ?? 'Cafe A';
        $outletName = $outlet->outlet_name ?? '-';
        $outletAddress = $outlet->address ?? null;
        $tableNumber = $order->restaurantTable->table_number ?? '-';
    @endphp

    <div class="receipt-page">
        <div class="receipt">
            <div class="center">
                <div class="title">{{ strtoupper($cafeName) }}</div>
                <div class="small">{{ $outletName }}</div>

                @if ($outletAddress)
                    <div class="small">{{ $outletAddress }}</div>
                @endif
            </div>

            <div class="line"></div>

            <div class="row">
                <div class="left">{{ $order->created_at->format('d/m/Y H:i') }}</div>
                <div class="right">{{ $order->order_code }}</div>
            </div>

            <div class="row">
                <div class="left">Pelanggan</div>
                <div class="right">{{ $order->customer_name }}</div>
            </div>

            <div class="row">
                <div class="left">No. HP</div>
                <div class="right">{{ $order->customer_phone ?? '-' }}</div>
            </div>

            <div class="row">
                <div class="left">Meja</div>
                <div class="right">{{ $tableNumber }}</div>
            </div>

            <div class="line"></div>

            @foreach ($order->items as $item)
                <div class="item">
                    <div class="item-name">{{ strtoupper($item->menu_name) }}</div>

                    <div class="item-sub">
                        <div>{{ $item->quantity }} x {{ number_format($item->menu_price, 0, ',', '.') }}</div>
                        <div>{{ number_format($item->subtotal, 0, ',', '.') }}</div>
                    </div>

                    @if ($item->item_note)
                        <div class="item-note">* {{ $item->item_note }}</div>
                    @endif
                </div>
            @endforeach

            <div class="line"></div>

            <div class="row">
                <div class="left">Status Order</div>
                <div class="right">{{ strtoupper($order->status) }}</div>
            </div>

            <div class="row">
                <div class="left">Pembayaran</div>
                <div class="right">{{ strtoupper($order->payment_status) }}</div>
            </div>

            <div class="line"></div>

            <div class="total-row">
                <div>TOTAL</div>
                <div>{{ number_format($order->total_amount, 0, ',', '.') }}</div>
            </div>

            @if ($order->customer_note)
                <div class="line"></div>

                <div class="section-title">CATATAN PELANGGAN</div>
                <div class="small">{{ $order->customer_note }}</div>
            @endif

            <div class="line"></div>

            <div class="footer-text">
                TERIMA KASIH<br>
                SELAMAT MENIKMATI PESANAN ANDA
            </div>
        </div>

        <div class="actions no-print">
            <button type="button" onclick="window.print()" class="btn btn-print">
                Print Nota
            </button>

            <a href="{{ route('kasir.orders.show', $order) }}" class="btn btn-back">
                Kembali
            </a>
        </div>
    </div>
</body>
</html>