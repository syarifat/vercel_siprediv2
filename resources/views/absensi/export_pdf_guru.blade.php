<!DOCTYPE html>
<html>
<head>
    <title>Export Absensi Guru PDF</title>
    <style>
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #888; padding: 6px; text-align: center; }
        th { background: #dbeafe; }
        tr:nth-child(even) { background: #f8fafc; }
    </style>
</head>
<body>
    <h2>Rekap Absensi Guru</h2>
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Guru</th>
                <th>Tanggal</th>
                <th>Jam Masuk</th>
                <th>Jam Pulang</th>
                <th>Status</th>
                <th>Keterangan</th>
            </tr>
        </thead>
        <tbody>
            @foreach($absensi as $i => $row)
            <tr>
                <td>{{ $i+1 }}</td>
                <td>{{ $row->guru->nama ?? '-' }}</td>
                <td>{{ $row->tanggal ? \Carbon\Carbon::parse($row->tanggal)->toDateString() : '-' }}</td>
                <td>{{ $row->jam_masuk ?? '-' }}</td>
                <td>{{ $row->jam_pulang ?? '-' }}</td>
                <td>{{ $row->status }}</td>
                <td>{{ $row->keterangan ?? '-' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
