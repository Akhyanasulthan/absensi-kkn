<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Absensi KKN</title>
    <style>
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            font-size: 11pt;
            color: #1a1a1a;
            margin: 0;
            padding: 0;
        }
        .header {
            text-align: center;
            margin-bottom: 25px;
            border-bottom: 2px double #1a1a1a;
            padding-bottom: 10px;
        }
        .header h1 {
            font-size: 18pt;
            margin: 0;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #0f172a;
        }
        .header p {
            font-size: 10pt;
            color: #4b5563;
            margin: 5px 0 0 0;
        }
        .info-table {
            width: 100%;
            margin-bottom: 20px;
            font-size: 10pt;
        }
        .info-table td {
            padding: 3px 0;
        }
        .content-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
            font-size: 9.5pt;
        }
        .content-table th, .content-table td {
            border: 1px solid #9ca3af;
            padding: 8px 10px;
            text-align: left;
        }
        .content-table th {
            background-color: #f3f4f6;
            font-weight: bold;
            color: #1f2937;
            text-transform: uppercase;
            font-size: 9pt;
        }
        .content-table tr:nth-child(even) {
            background-color: #f9fafb;
        }
        .badge {
            display: inline-block;
            padding: 2px 6px;
            font-size: 8pt;
            font-weight: bold;
            border-radius: 4px;
            text-transform: uppercase;
        }
        .badge-present {
            background-color: #d1fae5;
            color: #065f46;
        }
        .badge-late {
            background-color: #fef3c7;
            color: #92400e;
        }
        .badge-danger {
            background-color: #fee2e2;
            color: #991b1b;
        }
        .footer {
            margin-top: 40px;
            text-align: right;
            font-size: 10pt;
            color: #4b5563;
        }
        .signature-area {
            margin-top: 50px;
            display: inline-block;
            text-align: center;
            width: 200px;
        }
        .signature-line {
            margin-top: 60px;
            border-top: 1px solid #1a1a1a;
            padding-top: 5px;
            font-weight: bold;
        }
    </style>
</head>
<body>

    <div class="header">
        <h1>Laporan Absensi Evaluasi Mingguan</h1>
        <p>Posko: {{ $kknName }}</p>
    </div>

    <table class="info-table">
        <tr>
            <td style="width: 15%; font-weight: bold;">Evaluasi</td>
            <td style="width: 2%;">:</td>
            <td>Minggu Ke-{{ $weekLabel }}</td>
            <td style="width: 15%; font-weight: bold; text-align: right;">Dicetak Pada</td>
            <td style="width: 2%; text-align: center;">:</td>
            <td style="width: 25%; text-align: right;">{{ \Carbon\Carbon::now('Asia/Jakarta')->translatedFormat('d F Y H:i') }} WIB</td>
        </tr>
        <tr>
            <td style="font-weight: bold;">Periode</td>
            <td>:</td>
            <td colspan="4">{{ \Carbon\Carbon::parse($start)->translatedFormat('d M Y') }} s.d. {{ \Carbon\Carbon::parse($end)->translatedFormat('d M Y') }}</td>
        </tr>
    </table>

    <table class="content-table">
        <thead>
            <tr>
                <th style="width: 15%;">Tanggal</th>
                <th style="width: 25%;">Nama Mahasiswa</th>
                <th style="width: 20%;">Divisi</th>
                <th style="width: 13%; text-align: center;">Jam Masuk</th>
                <th style="width: 13%; text-align: center;">Jam Pulang</th>
                <th style="width: 14%; text-align: center;">Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($attendances as $log)
                <tr>
                    <td style="font-weight: bold;">{{ \Carbon\Carbon::parse($log->date)->translatedFormat('d/m/Y') }}</td>
                    <td>{{ $log->name }}</td>
                    <td>{{ $log->division }}</td>
                    <td style="text-align: center;">{{ $log->check_in ? \Carbon\Carbon::parse($log->check_in)->format('H:i:s') : '-' }}</td>
                    <td style="text-align: center;">{{ $log->check_out ? \Carbon\Carbon::parse($log->check_out)->format('H:i:s') : '-' }}</td>
                    <td style="text-align: center;">
                        <span class="badge 
                            @if($log->status === 'Present') badge-present
                            @elseif($log->status === 'Late') badge-late
                            @else badge-danger @endif">
                            {{ $log->status }}
                        </span>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" style="text-align: center; padding: 20px; color: #6b7280;">
                        Tidak ada data kehadiran pada periode evaluasi mingguan ini.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        <div class="signature-area">
            <p>Koordinator Desa / Posko,</p>
            <div class="signature-line">
                ( ........................................ )
            </div>
        </div>
    </div>

</body>
</html>
