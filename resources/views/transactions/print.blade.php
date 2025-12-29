<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Nota Transaksi #{{ $transaction->id }}</title>
    <style>
        body { font-family: 'Courier New', Courier, monospace; width: 80mm; margin: auto; padding: 10px; color: #000; }
        .text-center { text-align: center; }
        .header { border-bottom: 1px dashed #000; padding-bottom: 10px; margin-bottom: 10px; }
        table { width: 100%; border-collapse: collapse; }
        th { text-align: left; }
        .footer { border-top: 1px dashed #000; margin-top: 20px; padding-top: 10px; font-size: 12px; }
        @media print { .no-print { display: none; } }
    </style>
</head>
<body onload="window.print()">
    <div class="no-print" style="margin-bottom: 20px;">
        <button onclick="window.print()">Cetak</button>
        <a href="{{ route('transactions.index') }}">Kembali</a>
    </div>

    <div class="header text-center">
        <h2 style="margin: 0;">GUDANG APP</h2>
        <p style="margin: 5px 0;">Bukti Transaksi Barang {{ strtoupper($transaction->type == 'in' ? 'Masuk' : 'Keluar') }}</p>
    </div>

    <div>
        <p>ID: #{{ $transaction->id }}</p>
        <p>Tgl: {{ date('d/m/Y H:i', strtotime($transaction->created_at)) }}</p>
        <p>Petugas: {{ $transaction->user->name }}</p>
        <hr style="border: 0; border-top: 1px dashed #000;">
        <table>
            <tr>
                <th colspan="2">{{ $transaction->product->name }}</th>
            </tr>
            <tr>
                <td>{{ $transaction->quantity }} x {{ number_format($transaction->price, 0, ',', '.') }}</td>
                <td style="text-align: right;">{{ number_format($transaction->total_price, 0, ',', '.') }}</td>
            </tr>
        </table>
    </div>

    <div class="footer text-center">
        <p>Terima kasih atas kerja samanya.</p>
        <p>{{ date('Y') }} &copy; GudangApp System</p>
    </div>
</body>
</html>