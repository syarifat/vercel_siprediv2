<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Daftar Siswa {{ $kelas->nama }}</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        th, td { border: 1px solid #333; padding: 6px; text-align: center; }
        th { background: #f4b083; }
        h2 { text-align: center; margin-bottom: 0; }
    </style>
</head>
<body>
    <h2>Daftar Siswa Kelas {{ $kelas->nama }}</h2>
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>No. Absen</th>
                <th>Nama</th>
                <th>NIS</th>
                <th>Tahun Ajaran</th>
            </tr>
        </thead>
        <tbody>
            @foreach($rombel as $i => $row)
            <tr>
                <td>{{ $i+1 }}</td>
                <td>{{ $row->nomor_absen ?? '-' }}</td>
                <td style="text-align:left;">{{ $row->siswa->nama ?? '-' }}</td>
                <td>{{ $row->siswa->nis ?? '-' }}</td>
                <td>{{ $row->tahunAjaran ? ($row->tahunAjaran->nama . ' - ' . $row->tahunAjaran->semester) : '-' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
