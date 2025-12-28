<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Laporan Penjualan Bulanan</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            color: #333;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 3px solid #991b1b;
            padding-bottom: 10px;
        }
        .header h1 {
            margin: 0;
            color: #991b1b;
            font-size: 24px;
        }
        .header p {
            margin: 5px 0;
            color: #666;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th {
            background-color: #991b1b;
            color: white;
            padding: 10px;
            text-align: left;
        }
        td {
            padding: 8px;
            border-bottom: 1px solid #ddd;
        }
        .footer {
            margin-top: 30px;
            text-align: right;
            font-weight: bold;
            font-size: 14px;
        }
    </style>
</head>
<body>
    <!-- HEADER LAPORAN -->
    <div class="header">
        <h1>CORELOGIC SECURITY SOLUTIONS</h1>
        <p>Laporan Penjualan Bulanan</p>
        <p>Periode:
            {{ DateTime::createFromFormat('!m', $month)->format('F') }} {{ $year }}
        </p>
    </div>

    <!-- TABEL DATA ORDERS -->
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Tanggal</th>
                <th>Client</th>
                <th>Service</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            @forelse($orders as $index => $order)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $order->created_at->format('d/m/Y') }}</td>
                <td>{{ $order->user->name }}</td>
                <td>{{ $order->service->name }}</td>
                <td>${{ number_format($order->total_price, 2) }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="5" style="text-align: center; color: #999;">
                    Tidak ada data penjualan di bulan ini
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <!-- FOOTER TOTAL -->
    <div class="footer">
        Total Revenue: ${{ number_format($monthlyRevenue, 2) }}
    </div>
</body>
</html>
