<div style="font-family: sans-serif; padding: 20px; border: 1px solid #ddd;">
    <h2>Halo, {{ $invoice->tenant->user->name }}!</h2>
    <p>Tagihan kost lu untuk bulan ini sudah terbit.</p>
    <table style="width: 100%; border-collapse: collapse;">
        <tr>
            <td><strong>Nomor Kamar</strong></td>
            <td>: {{ $invoice->tenant->room->room_number }}</td>
        </tr>
        <tr>
            <td><strong>Total Tagihan</strong></td>
            <td>: Rp {{ number_format($invoice->amount) }}</td>
        </tr>
        <tr>
            <td><strong>Jatuh Tempo</strong></td>
            <td>: {{ $invoice->bill_date->format('d M Y') }}</td>
        </tr>
    </table>
    <p>Silakan segera lakukan pembayaran dan konfirmasi ke Pemilik Kost ya!</p>
    <br>
    <p>Salam,<br><strong>Manajemen SmartKost</strong></p>
</div>