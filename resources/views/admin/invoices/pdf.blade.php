<!DOCTYPE html>
<html>
<head>
    <title>Bukti Pembayaran #{{ $invoice->id }}</title>
    <style>
        body { font-family: sans-serif; line-height: 1.6; }
        .header { text-align: center; margin-bottom: 30px; border-bottom: 2px solid #444; padding-bottom: 10px; }
        .info { width: 100%; margin-bottom: 20px; }
        .table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        .table th, .table td { border: 1px solid #ddd; padding: 12px; text-align: left; }
        .table th { bg-color: #f4f4f4; }
        .footer { margin-top: 50px; text-align: right; font-size: 12px; }
        .status { color: green; font-weight: bold; text-transform: uppercase; }
    </style>
</head>
<body>
    <div class="header">
        <h1>SMARTKOST</h1>
        <p>Bukti Pembayaran Sah</p>
    </div>

    <table class="info">
        <tr>
            <td><strong>Nama Penghuni:</strong> {{ $invoice->tenant->user->name }}</td>
            <td style="text-align: right;"><strong>Tanggal:</strong> {{ $invoice->payment_date->format('d M Y') }}</td>
        </tr>
        <tr>
            <td><strong>Nomor Kamar:</strong> {{ $invoice->tenant->room->room_number }}</td>
            <td style="text-align: right;"><strong>Metode:</strong> {{ strtoupper($invoice->payment_method) }}</td>
        </tr>
    </table>

    <table class="table">
        <thead>
            <tr>
                <th>Deskripsi</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>Sewa Kamar Kost (Periode {{ $invoice->bill_date->format('F Y') }})</td>
                <td>Rp {{ number_format($invoice->amount) }}</td>
            </tr>
        </tbody>
    </table>

    <div class="footer">
        <p>Status: <span class="status">LUNAS</span></p>
        <p>Dicetak pada: {{ now()->format('d/m/Y H:i') }}</p>
        <br><br>
        <p>____________________</p>
        <p>Admin SmartKost</p>
    </div>
</body>
</html>