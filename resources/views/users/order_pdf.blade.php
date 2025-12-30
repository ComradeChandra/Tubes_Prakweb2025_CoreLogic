<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        /* Catatan: Gunakan inline/embedded CSS agar DomPDF dapat render dengan baik. */
        body { font-family: DejaVu Sans, Arial, sans-serif; color: #222; font-size: 13px; }
        .brand { background: #0f1724; color: #fff; padding: 12px 16px; border-radius: 6px; }
        .brand h1 { margin: 0; font-size: 18px; letter-spacing: 1px; }
        .brand p { margin: 2px 0 0 0; font-size: 10px; color: #d1d5db; }
        .meta { margin-top: 18px; display: flex; justify-content: space-between; }
        .meta .left, .meta .right { width: 48%; }
        .label { font-weight: bold; color: #374151; display:block; margin-bottom:4px; }
        .value { color: #111827; margin-bottom:8px; }
        .box { border: 1px solid #e5e7eb; padding: 12px; border-radius: 6px; }
        .table { width: 100%; border-collapse: collapse; margin-top: 14px; }
        .table th, .table td { border: 1px solid #e5e7eb; padding: 8px; text-align: left; }
        .table th { background: #f3f4f6; font-size: 12px; }
        .total { margin-top: 12px; text-align: right; font-weight: bold; }
        .footer { margin-top: 30px; font-size: 11px; color: #6b7280; }
        .notes { margin-top: 10px; font-size: 12px; color: #374151; }
        .small { font-size: 11px; color:#6b7280; }
    </style>
</head>
<body>

    <!-- CATATAN PENGEMBANG: Template ini digunakan untuk menghasilkan PDF Order oleh DomPDF. -->

    <div class="brand">
        <h1>CoreLogic Security Solutions</h1>
        <p>Laporan Pesanan / Order Receipt</p>
    </div>

    <div class="meta">
        <div class="left box">
            <span class="label">Order ID</span>
            <div class="value">#{{ $order->id }}</div>

            <span class="label">Service</span>
            <div class="value">{{ $order->service->name }}</div>

            <span class="label">Quantity</span>
            <div class="value">{{ $order->quantity }}</div>

            <span class="label">Contract Dates</span>
            <div class="value">{{ \Carbon\Carbon::parse($order->start_date)->format('d M Y') }} - {{ \Carbon\Carbon::parse($order->end_date)->format('d M Y') }}</div>
        </div>

        <div class="right box">
            <span class="label">Customer</span>
            <div class="value">{{ $order->user->name }} ({{ $order->user->email }})</div>

            <span class="label">Phone / NIK</span>
            <div class="value">{{ $order->user->phone ?? '-' }} / {{ $order->user->nik ?? '-' }}</div>

            <span class="label">Status</span>
            <div class="value">{{ $order->status }}</div>
        </div>
    </div>

    <div class="box" style="margin-top:16px;">
        <span class="label">Deployment Address</span>
        <div class="value">{{ $order->address ?? '-' }}</div>
        @if($order->latitude && $order->longitude)
            <div class="small">Coords: {{ $order->latitude }}, {{ $order->longitude }}</div>
        @endif

        <div class="notes">
            <span class="label">Notes</span>
            <div>{{ $order->notes ?? '-' }}</div>
        </div>

        <table class="table">
            <thead>
                <tr>
                    <th>Item</th>
                    <th>Qty</th>
                    <th>Unit Price</th>
                    <th>Line Total</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>{{ $order->service->name }}</td>
                    <td>{{ $order->quantity }}</td>
                    <td>${{ number_format($order->service->price, 2) }}</td>
                    <td>${{ number_format($order->total_price, 2) }}</td>
                </tr>
            </tbody>
        </table>

        <div class="total">Total: ${{ number_format($order->total_price, 2) }}</div>
    </div>

    <div class="footer">
        <div>Generated on {{ date('d M Y H:i') }}</div>
        <div style="margin-top:8px;">Thank you for using CoreLogic. For support, contact admin@corelogic.sec</div>
    </div>

</body>
</html>
