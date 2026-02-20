<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Struk #{{ $order->order_number }}</title>
    <style>
        /* Ukuran kertas thermal 80mm (302px) */
        @page {
            size: 80mm auto;
            margin: 0;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Courier New', monospace;
            font-size: 10px;
            line-height: 1.3;
            color: #000;
            width: 80mm;
            margin: 0 auto;
            padding: 8px;
            background: #fff;
        }

        .header {
            text-align: center;
            margin-bottom: 12px;
            border-bottom: 2px dashed #000;
            padding-bottom: 8px;
        }

        .header h1 {
            font-size: 14px;
            font-weight: bold;
            margin-bottom: 2px;
        }

        .header p {
            font-size: 9px;
            margin: 1px 0;
        }

        .section {
            margin-bottom: 8px;
        }

        .section-title {
            font-weight: bold;
            font-size: 11px;
            margin-bottom: 4px;
            border-bottom: 1px solid #000;
        }

        .info-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 2px;
            font-size: 9px;
        }

        .info-label {
            font-weight: bold;
            width: 40%;
        }

        .info-value {
            width: 60%;
            text-align: right;
            word-wrap: break-word;
        }

        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin: 8px 0;
            table-layout: fixed;
        }

        .items-table th {
            text-align: left;
            padding: 3px 0;
            border-bottom: 1px solid #000;
            font-size: 9px;
            font-weight: bold;
        }

        .items-table td {
            padding: 3px 0;
            font-size: 8px;
            vertical-align: top;
            overflow: hidden;
            word-wrap: break-word;
            white-space: normal;
        }

        .items-table .item-name {
            font-weight: bold;
            word-wrap: break-word;
            font-size: 9px;
            line-height: 1.2;
            overflow: hidden;
        }

        .divider {
            border-top: 1px dashed #000;
            margin: 8px 0;
        }

        .total-section {
            margin-top: 4px;
            border-top: 1px solid #000;
            padding-top: 4px;
        }

        .total-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 4px;
            font-size: 10px;
        }

        .total-row.grand-total {
            font-size: 12px;
            font-weight: bold;
            border-top: 1px solid #000;
            padding-top: 4px;
            margin-top: 4px;
        }

        .footer {
            text-align: center;
            margin-top: 12px;
            border-top: 2px dashed #000;
            padding-top: 8px;
            font-size: 9px;
        }

        .footer p {
            margin: 2px 0;
        }

        @media print {
            body {
                width: 80mm;
            }
        }
    </style>
</head>
<body>
    <!-- Header -->
    <div class="header">
        <h1>KOPERASI PSM</h1>
        <p>Jl. Raya Pakuan Ratu</p>
        <p>Way Kanan, Lampung 34762</p>
        <p>Telp: +62 823 1684 3922</p>
    </div>

    <!-- Order Info -->
    <div class="section">
        <div class="info-row">
            <span class="info-label">No. Pesanan:</span>
            <span class="info-value">{{ $order->order_number }}</span>
        </div>
        <div class="info-row">
            <span class="info-label">Tanggal:</span>
            <span class="info-value">{{ \Carbon\Carbon::parse($order->created_at)->format('d/m/Y H:i') }}</span>
        </div>
        <div class="info-row">
            <span class="info-label">Kasir:</span>
            <span class="info-value">{{ $order->histories->where('action', 'verified')->first()?->processedBy?->name ?? 'Admin' }}</span>
        </div>
    </div>

    <div class="divider"></div>

    <!-- Customer Info -->
    <div class="section">
        <div class="section-title">PELANGGAN</div>
        <div class="info-row">
            <span class="info-label">Nama:</span>
            <span class="info-value">{{ $order->customer_name }}</span>
        </div>
        <div class="info-row">
            <span class="info-label">Email:</span>
            <span class="info-value">{{ $order->customer_email }}</span>
        </div>
        <div class="info-row">
            <span class="info-label">Telp:</span>
            <span class="info-value">{{ $order->customer_phone }}</span>
        </div>
    </div>

    <div class="divider"></div>

    <!-- Items -->
    <div class="section">
        <div class="section-title">DETAIL PESANAN</div>
        <table class="items-table">
            <thead>
                <tr>
                    <th style="width: 50%">Produk</th>
                    <th style="width: 12%; text-align: center">Qty</th>
                    <th style="width: 38%; text-align: right">Subtotal</th>
                </tr>
            </thead>
            <tbody>
                @foreach($order->orderItems as $item)
                <tr>
                    <td style="width: 50%">
                        <div class="item-name">{{ $item->products->name ?? 'Produk' }}</div>
                    </td>
                    <td style="width: 12%; text-align: center">{{ $item->quantity }}</td>
                    <td style="width: 38%; text-align: right; font-size: 7px; padding-right: 2px;">Rp {{ number_format($item->price * $item->quantity, 0, ',', '.') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <!-- Total -->
        <div class="total-section">
            <div class="total-row grand-total">
                <span>TOTAL:</span>
                <span>Rp {{ number_format($order->total_amount, 0, ',', '.') }}</span>
            </div>
        </div>
    </div>

    <div class="divider"></div>

    <!-- Payment Info -->
    <div class="section">
        <div class="section-title">PEMBAYARAN</div>
        <div class="info-row">
            <span class="info-label">Metode:</span>
            <span class="info-value">Transfer Bank</span>
        </div>
        @if($order->payment)
        <div class="info-row">
            <span class="info-label">Pengirim:</span>
            <span class="info-value">{{ $order->payment->sender_name }}</span>
        </div>
        <div class="info-row">
            <span class="info-label">Tgl Transfer:</span>
            <span class="info-value">{{ \Carbon\Carbon::parse($order->payment->transfer_date)->format('d/m/Y H:i') }}</span>
        </div>
        @endif
    </div>

    <div class="divider"></div>

    <!-- Shipping Address -->
    <div class="section">
        <div class="section-title">ALAMAT PENGIRIMAN</div>
        <p style="font-size: 9px; line-height: 1.4; word-wrap: break-word;">
            {{ $order->shipping_address }}
        </p>
    </div>

    <!-- Footer -->
    <div class="footer">
        <p>*** TERIMA KASIH ***</p>
        <p>Barang yang sudah dibeli</p>
        <p>tidak dapat dikembalikan</p>
        <p style="margin-top: 8px; font-size: 9px;">
            Dicetak: {{ \Carbon\Carbon::now()->format('d/m/Y H:i') }}
        </p>
    </div>
</body>
</html>
