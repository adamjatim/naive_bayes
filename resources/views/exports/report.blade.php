<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Laporan Prediksi - {{ $fileName }}</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        h1, h2 { margin: 0; padding: 0; }
        table { width: 90vw; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #000; padding: 6px; text-align: left; }
        th { background-color: #f3f3f3; }
    </style>
</head>
<body>
    <h1>Laporan Prediksi Data</h1>
    <p><strong>File:</strong> {{ $fileName }}</p>
    <p><strong>Tanggal:</strong> {{ now()->format('d M Y H:i') }}</p>

    <h2>Ringkasan</h2>
    <ul>
        <li>Jumlah Penerima: {{ $summary['jumlah_penerima'] }}</li>
        <li>Jumlah Bukan Penerima: {{ $summary['jumlah_bukan'] }}</li>
        <li>Total Data: {{ $summary['jumlah_penerima'] + $summary['jumlah_bukan'] }}</li>
    </ul>

    <h2>Data</h2>
    <table>
        <thead>
            <tr>
                @foreach ($data->first()->getAttributes() as $key => $value)
                    @if (!in_array($key, ['id', 'user_id', 'file_name', 'file_size', 'created_at', 'updated_at']))
                        <th>{{ strtoupper(str_replace('_', ' ', $key)) }}</th>
                    @endif
                @endforeach
            </tr>
        </thead>
        <tbody>
            @foreach ($data as $row)
                <tr>
                    @foreach ($row->getAttributes() as $key => $value)
                        @if (!in_array($key, ['id', 'user_id', 'file_name', 'file_size', 'created_at', 'updated_at']))
                            <td style="@if (strpos($value, 'RW') !== false) white-space: nowrap; @endif">{{ $value }}</td>
                        @endif
                    @endforeach
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
