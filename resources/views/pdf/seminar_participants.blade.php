<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Data Kehadiran Seminar INNOVENTURE</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            color: #333;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 2px solid #5c20c0;
            padding-bottom: 10px;
        }
        .header h1 {
            margin: 0;
            color: #5c20c0;
            font-size: 24px;
        }
        .header p {
            margin: 5px 0 0 0;
            color: #666;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f3f4f6;
            color: #111;
            font-weight: bold;
        }
        tr:nth-child(even) {
            background-color: #fafafa;
        }
        .text-center {
            text-align: center;
        }
        .badge-yes {
            color: #059669;
            font-weight: bold;
        }
        .badge-no {
            color: #dc2626;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Data Kehadiran Seminar INNOVENTURE</h1>
        <p>Dicetak pada: {{ now()->format('d M Y H:i:s') }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th style="width: 5%">No</th>
                <th style="width: 25%">Nama Lengkap</th>
                <th style="width: 20%">Instansi</th>
                <th style="width: 25%">Email & No. HP</th>
                <th style="width: 15%" class="text-center">No. Undian</th>
                <th style="width: 10%" class="text-center">Hadir?</th>
            </tr>
        </thead>
        <tbody>
            @foreach($records as $index => $record)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $record->nama }}</td>
                    <td>{{ $record->instansi }}</td>
                    <td>
                        {{ $record->email }}<br>
                        <small>{{ $record->no_hp }}</small>
                    </td>
                    <td class="text-center">{{ $record->no_undian }}</td>
                    <td class="text-center">
                        @if($record->absensis()->exists())
                            <span class="badge-yes">HADIR</span>
                        @else
                            <span class="badge-no">TIDAK</span>
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
