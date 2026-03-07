<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Produk Terjual</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            font-size: 11px;
            line-height: 1.4;
            color: #333;
            padding: 20px;
        }

        .header {
            text-align: center;
            margin-bottom: 25px;
            border-bottom: 3px solid #000;
            padding-bottom: 15px;
        }

        .header h1 {
            font-size: 22px;
            color: #000;
            margin-bottom: 5px;
            font-weight: bold;
        }

        .header .company-name {
            font-size: 14px;
            color: #333;
            margin-bottom: 10px;
        }

        .header .period {
            font-size: 12px;
            color: #000;
            font-weight: bold;
            margin-top: 8px;
        }

        .header .generated {
            font-size: 9px;
            color: #666;
            margin-top: 5px;
        }

        .summary-section {
            margin-bottom: 20px;
            background: #f5f5f5;
            padding: 15px;
            border-radius: 6px;
            border: 2px solid #000;
        }

        .summary-grid {
            display: table;
            width: 100%;
            margin-top: 10px;
        }

        .summary-row {
            display: table-row;
        }

        .summary-label {
            display: table-cell;
            padding: 6px 0;
            font-weight: normal;
            color: #555;
            width: 60%;
        }

        .summary-value {
            display: table-cell;
            padding: 6px 0;
            text-align: right;
            font-weight: bold;
            color: #000;
            width: 40%;
        }

        .section {
            margin-bottom: 20px;
        }

        .section h2 {
            font-size: 14px;
            color: #000;
            margin-bottom: 10px;
            border-bottom: 2px solid #000;
            padding-bottom: 5px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 8px;
            background: white;
        }

        table thead {
            background: white;
            color: #000;
            border-bottom: 2px solid #000;
        }

        table th {
            padding: 8px 6px;
            text-align: left;
            font-size: 10px;
            font-weight: bold;
            border: 1px solid #666;
            background: white;
        }

        table td {
            padding: 6px 6px;
            border: 1px solid #ccc;
            font-size: 10px;
        }

        table tbody tr:nth-child(even) {
            background: white;
        }

        .text-right {
            text-align: right;
        }

        .text-center {
            text-align: center;
        }

        .currency {
            font-weight: bold;
            color: #000;
        }

        .footer {
            margin-top: 30px;
            padding-top: 15px;
            border-top: 2px solid #000;
            text-align: center;
            font-size: 9px;
            color: #666;
        }

        .total-row {
            font-weight: bold;
            background: white !important;
            border-top: 2px solid #000;
        }

        .no-data {
            text-align: center;
            padding: 20px;
            color: #666;
            font-style: italic;
        }
    </style>
</head>

<body>
    <!-- Header -->
    <div class="header">
        <h1>KOPERASI PSM</h1>
        <div class="company-name">Laporan Produk Terjual</div>
        <div class="period">Periode: {{ $start_date }} s/d {{ $end_date }}</div>
        <div class="generated">Dicetak pada: {{ $generated_at }}</div>
    </div>

    <!-- Summary Section -->
    <div class="summary-section">
        <div class="summary-grid">
            <div class="summary-row">
                <div class="summary-label" style="font-weight: bold; font-size: 12px;">Total Produk Terjual:</div>
                <div class="summary-value" style="font-size: 14px;">{{ $total_quantity }} Item</div>
            </div>
            <div class="summary-row">
                <div class="summary-label" style="font-weight: bold; font-size: 12px;">Total Pendapatan:</div>
                <div class="summary-value" style="font-size: 14px;">Rp {{ number_format($total_revenue, 0, ',', '.') }}</div>
            </div>
        </div>
    </div>

    <!-- Products Table -->
    @if(count($products) > 0)
    <div class="section">
        <h2>Detail Produk</h2>
        <table>
            <thead>
                <tr>
                    <th style="width: 8%">NO</th>
                    <th style="width: 40%">NAMA PRODUK</th>
                    <th style="width: 15%" class="text-center">TOTAL TERJUAL</th>
                    <th style="width: 17%" class="text-right">HARGA SATUAN</th>
                    <th style="width: 20%" class="text-right">PENDAPATAN</th>
                </tr>
            </thead>
            <tbody>
                @foreach($products as $index => $product)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td>{{ $product['product_name'] }}</td>
                    <td class="text-center">{{ $product['quantity'] }} item</td>
                    <td class="text-right">Rp {{ number_format($product['unit_price'], 0, ',', '.') }}</td>
                    <td class="text-right currency">Rp {{ number_format($product['revenue'], 0, ',', '.') }}</td>
                </tr>
                @endforeach
                <tr class="total-row">
                    <td colspan="2" class="text-right" style="font-weight: bold;">TOTAL:</td>
                    <td class="text-center">{{ $total_quantity }} item</td>
                    <td class="text-right">-</td>
                    <td class="text-right currency">Rp {{ number_format($total_revenue, 0, ',', '.') }}</td>
                </tr>
            </tbody>
        </table>
    </div>
    @else
    <div class="section">
        <h2>Detail Produk</h2>
        <div class="no-data">Tidak ada produk terjual pada periode ini</div>
    </div>
    @endif    <!-- Footer -->
    <div class="footer">
        <p>Laporan ini digenerate secara otomatis oleh sistem Koperasi PSM</p>
        <p>Untuk informasi lebih lanjut, hubungi administrator</p>
    </div>
</body>

</html>
