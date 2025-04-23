<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Laporan Proses Testing - {{ $summary['persentase'] }}%</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        h1, h2 { margin: 0; padding: 0; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #000; padding: 6px; text-align: left; }
        th { background-color: #f3f3f3; }
    </style>
</head>
<body>
    <h1>Laporan Proses Testing ({{ $summary['persentase'] }}%)</h1>
    <p><strong>Tanggal:</strong> {{ now()->format('d M Y H:i') }}</p>

    <h2>Ringkasan</h2>
    <ul>
        <li>Jumlah Penerima: {{ $summary['jumlah_penerima'] }}</li>
        <li>Jumlah Bukan Penerima: {{ $summary['jumlah_bukan'] }}</li>
        <li>Total Data Testing: {{ $summary['total_testing'] }}</li>
        <li>Akurasi: {{ $summary['akurasi'] }}%</li>
    </ul>

    <h2>Data Proses Testing</h2>
    <table>
        <thead>
            <tr>
                @foreach (array_keys($predictions[0]['data']->getAttributes()) as $key)
                    @if (!in_array($key, ['id', 'user_id', 'file_name', 'file_size', 'created_at', 'updated_at']))
                        <th>{{ strtoupper(str_replace('_', ' ', $key)) }}</th>
                    @endif
                @endforeach
                <th>HASIL TESTING</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($predictions as $row)
                <tr>
                    @foreach ($row['data']->getAttributes() as $key => $val)
                        @if (!in_array($key, ['id', 'user_id', 'file_name', 'file_size', 'created_at', 'updated_at']))
                            <td style="@if (is_string($val) && str_contains($val, 'RW')) white-space: nowrap; @endif">
                                {{ $val }}
                            </td>
                        @endif
                    @endforeach
                    <td style="font-weight: bold; color: {{ $row['correct'] ? '#16a34a' : '#dc2626' }}">
                        {{ $row['predicted'] }}
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
