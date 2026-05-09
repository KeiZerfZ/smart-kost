<!DOCTYPE html>
<html>
<head>
    <title>Invoice #{{ $invoice->id }}</title>
    <style>
        body { font-family: sans-serif; color: #333; line-height: 1.6; }
        .header { text-align: center; margin-bottom: 30px; border-bottom: 2px solid #3b82f6; padding-bottom: 10px; }
        .invoice-box { max-width: 800px; margin: auto; }
        .info { margin-bottom: 20px; }
        .table { w-full; border-collapse: collapse; margin-top: 20px; width: 100%; }
        .table th, .table td { border: 1px solid #eee; padding: 12px; text-align: left; }
        .table th { bg-color: #f8fafc; font-weight: bold; }
        .status { color: green; font-weight: bold; text-transform: uppercase; }
        .footer { margin-top: 50px; text-align: center; font-size: 12px; color: #94a3b8; }
    </style>
</head>
<body>
    <div class="invoice-box">
        <div class="header">
            <h1 style="margin:0; color: #1d4ed8;">SmartKost.</h1>
            <p style="margin:5px 0;">Bukti Pembayaran Resmi</p>
        </div>

        <div class="info">
            <table style="width: 100%">
                <tr>
                    <td>
                        <strong>Penghuni:</strong><br>
                        {{ $invoice->tenant->user->name }}<br>
                        Kamar: {{ $invoice->tenant->room->room_number }}
                    </td>
                    <td style="text-align: right">
                        <strong>No. Invoice:</strong> #INV-{{ $invoice->id }}<br>
                        <strong>Tanggal Bayar:</strong> {{ $invoice->payment_date ? $invoice->payment_date->format('d M Y') : '-' }}
                    </td>
                </tr>
            </table>
        </div>

        <table class="table">
            <thead>
                <tr>
                    <th>Deskripsi</th>
                    <th>Periode</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Sewa Kamar ({{ $invoice->tenant->room->type }})</td>
                    <td>{{ $invoice->bill_date->format('F Y') }}</td>
                    <td><strong>Rp {{ number_format($invoice->amount, 0, ',', '.') }}</strong></td>
                </tr>
            </tbody>
        </table>

        <div style="margin-top: 30px; text-align: right">
            <p>Status Pembayaran: <span class="status">LUNAS</span></p>
            <p>Metode: {{ strtoupper($invoice->payment_method) }}</p>
        </div>

        <div class="footer">
            <p>Terima kasih telah melakukan pembayaran tepat waktu.</p>
            <p>&copy; 2026 SmartKost Management System</p>
        </div>
    </div>
</body>
</html>