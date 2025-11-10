<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8">
  <title>Invoice #{{ $order->order_number }}</title>
  <style>
    body {
      font-family: DejaVu Sans, sans-serif;
      font-size: 12px;
      color: #333;
      margin: 0;
      padding: 0;
    }

    .invoice-box {
      max-width: 800px;
      margin: 20px auto;
      padding: 24px;
      border: 1px solid #eee;
      border-radius: 8px;
      box-shadow: 0 0 8px rgba(0, 0, 0, 0.1);
    }

    h1,
    h2,
    h3,
    h4 {
      margin: 0;
      color: #2c3e50;
    }

    .header {
      text-align: center;
      margin-bottom: 24px;
    }

    .company-info {
      text-align: right;
    }

    .table {
      width: 100%;
      border-collapse: collapse;
      margin-top: 16px;
    }

    .table th,
    .table td {
      padding: 8px;
      border-bottom: 1px solid #ddd;
    }

    .table th {
      background: #f5f5f5;
      text-align: left;
    }

    .total {
      text-align: right;
      margin-top: 16px;
    }

    .footer {
      text-align: center;
      font-size: 11px;
      color: #777;
      margin-top: 40px;
    }
  </style>
</head>

<body>
  <div class="invoice-box">
    <div class="header">
      <h2>INVOICE</h2>
      <p>No. Pesanan: <strong>{{ $order->order_number }}</strong></p>
    </div>

    <div style="display:flex; justify-content: space-between;">
      <div>
        <h4>Kepada:</h4>
        <p>
          {{ $order->customer_name }}<br>
          {{ $order->shipping_address }}<br>
          {{ $order->customer_email }}<br>
          {{ $order->customer_phone }}
        </p>
      </div>
      <div class="company-info">
        <h4>Dari:</h4>
        <p>
          Koperasi PSM<br>
          Hutan Register, Kec. Pakuan Ratu, Kabupaten Way Kanan, Lampung<br>
          kpmsiapsedia@gmail.com<br>
          +62 822 8597 8581
        </p>
      </div>
    </div>

    <table class="table">
      <thead>
        <tr>
          <th>Produk</th>
          <th>Qty</th>
          <th>Harga</th>
          <th>Subtotal</th>
        </tr>
      </thead>
      <tbody>
        @foreach ($order->orderItems as $item)
          <tr>
            <td>{{ $item->products->name }}</td>
            <td>{{ $item->quantity }}</td>
            <td>Rp {{ number_format($item->price, 0, ',', '.') }}</td>
            <td>Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
          </tr>
        @endforeach
      </tbody>
    </table>

    <div class="total">
      <h3>Total: Rp {{ number_format($order->total_amount, 0, ',', '.') }}</h3>
    </div>

    <div class="footer">
      <p>Terima kasih telah berbelanja di Koperasi PSM</p>
      <p>Invoice ini dihasilkan otomatis pada {{ now()->format('d M Y, H:i') }}</p>
    </div>
  </div>
</body>

</html>
