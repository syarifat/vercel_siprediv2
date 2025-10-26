@extends('layouts.app')

@section('content')
<div class="max-w-6xl mx-auto mt-8">
    <h2 class="text-xl font-bold mb-4">Rekap Absensi Siswa</h2>

    <!-- Card Rekap Status -->
    <div class="grid grid-cols-2 md:grid-cols-5 gap-4 mb-6">
        <div class="bg-blue-100 rounded-lg shadow p-4 text-center">
            <div class="text-2xl font-bold text-blue-600" id="card-belum">0</div>
            <div class="text-sm font-semibold text-blue-700">Belum Hadir</div>
        </div>
        <div class="bg-green-100 rounded-lg shadow p-4 text-center">
            <div class="text-2xl font-bold text-green-600" id="card-hadir">0</div>
            <div class="text-sm font-semibold text-green-700">Hadir</div>
        </div>
        <div class="bg-yellow-100 rounded-lg shadow p-4 text-center">
            <div class="text-2xl font-bold text-yellow-600" id="card-izin">0</div>
            <div class="text-sm font-semibold text-yellow-700">Izin</div>
        </div>
        <div class="bg-red-100 rounded-lg shadow p-4 text-center">
            <div class="text-2xl font-bold text-red-600" id="card-sakit">0</div>
            <div class="text-sm font-semibold text-red-700">Sakit</div>
        </div>
        <div class="bg-pink-100 rounded-lg shadow p-4 text-center">
            <div class="text-2xl font-bold text-pink-600" id="card-alpha">0</div>
            <div class="text-sm font-semibold text-pink-700">Alpha</div>
        </div>
    </div>

    <!-- Filter untuk View Tabel -->
    <div class="mb-6 flex flex-wrap gap-4 items-center">
        <!-- Search Box -->
        <div class="relative">
            <input type="text" id="search" placeholder="Masukkan nama atau nis"
                class="border-2 border-gray-300 rounded-lg pl-10 pr-4 py-2 w-64
                       focus:outline-none focus:ring-2 focus:ring-orange-400 focus:border-orange-400
                       transition duration-200 shadow-sm"
                autofocus>
            <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 pointer-events-none">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35M11 19a8 8 0 100-16 8 8 0 000 16z" />
                </svg>
            </span>
        </div>
        <!-- Filter Tanggal (untuk view tabel harian) -->
        <div class="relative">
            <input type="date" id="tanggal"
                class="border-2 border-gray-300 rounded-lg px-4 py-2 w-48
                       focus:outline-none focus:ring-2 focus:ring-orange-400 focus:border-orange-400
                       transition duration-200 shadow-sm text-gray-700">
        </div>
        <!-- Dropdown Kelas (untuk view tabel) -->
        <div class="relative">
            <select id="kelas_id"
                class="border-2 border-gray-300 rounded-lg pl-10 pr-4 py-2 w-48
                       focus:outline-none focus:ring-2 focus:ring-orange-400 focus:border-orange-400
                       transition duration-200 shadow-sm bg-gray-50 text-gray-700 font-semibold appearance-none">
                <option value="">-- Pilih Kelas --</option>
                @foreach(\App\Models\Kelas::all() as $kelas)
                    <option value="{{ $kelas->id }}">{{ $kelas->nama }}</option>
                @endforeach
            </select>
            <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 pointer-events-none">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                </svg>
            </span>
        </div>

        <!-- Tombol Export -->
        <div class="ml-auto">
            <button type="button" class="bg-pink-400 hover:bg-pink-500 text-white font-semibold px-4 py-2 rounded-lg shadow transition duration-200 focus:outline-none"
                onclick="document.getElementById('exportModal').classList.remove('hidden')">
                Export PDF
            </button>
        </div>
    </div>

    <!-- Modal Export -->
    <div id="exportModal" class="hidden fixed inset-0 bg-black bg-opacity-40 flex items-center justify-center z-50">
        <div class="bg-white rounded-lg shadow-lg p-6 w-96">
            <h3 class="text-lg font-bold mb-4">Export Rekap Absensi</h3>
            <!-- Dropdown Kelas (untuk export) -->
            <div class="mb-4">
                <label for="kelas_export" class="block text-sm font-semibold text-gray-600 mb-1">Kelas</label>
                <select id="kelas_export"
                    class="border-2 border-gray-300 rounded-lg px-3 py-2 w-full
                           focus:outline-none focus:ring-2 focus:ring-pink-400 focus:border-pink-400
                           transition duration-200 bg-gray-50 text-gray-700 font-semibold">
                    <option value="">-- Pilih Kelas --</option>
                    @foreach(\App\Models\Kelas::all() as $kelas)
                        <option value="{{ $kelas->id }}">{{ $kelas->nama }}</option>
                    @endforeach
                </select>
            </div>
            <!-- Filter Bulan -->
            <div class="mb-4">
                <label for="periode" class="block text-sm font-semibold text-gray-600 mb-1">Periode (Bulan)</label>
                <input type="month" id="periode"
                    class="border-2 border-gray-300 rounded-lg px-3 py-2 w-full
                           focus:outline-none focus:ring-2 focus:ring-pink-400 focus:border-pink-400
                           transition duration-200 shadow-sm text-gray-700">
            </div>
            <!-- Tombol Aksi -->
            <div class="flex justify-end gap-2">
                <button type="button" class="px-4 py-2 bg-gray-300 rounded-lg hover:bg-gray-400 transition"
                    onclick="document.getElementById('exportModal').classList.add('hidden')">Batal</button>
                <button type="button" class="px-4 py-2 bg-pink-500 text-white rounded-lg hover:bg-pink-600 transition"
                    onclick="exportAbsensi()">Download PDF</button>
            </div>
        </div>
    </div>

    <!-- Tabel Absensi -->
    <table class="min-w-full border-2 border-orange-400 rounded-lg overflow-hidden shadow">
        <thead>
            <tr class="bg-orange-500 text-white">
                <th class="px-4 py-2 border-orange-400 text-center">No</th>
                <th class="px-4 py-2 border-orange-400">Nama</th>
                <th class="px-4 py-2 border-orange-400 text-center">NIS</th>
                <th class="px-4 py-2 border-orange-400 text-center">Kelas</th>
                <th class="px-4 py-2 border-orange-400 text-center">No Absen</th>
                <th class="px-4 py-2 border-orange-400 text-center">Tanggal</th>
                <th class="px-4 py-2 border-orange-400 text-center">Jam Masuk</th>
                <th class="px-4 py-2 border-orange-400 text-center">Jam Pulang</th> <!-- Tambahkan ini -->
                <th class="px-4 py-2 border-orange-400 text-center">Status</th>
                <th class="px-4 py-2 border-orange-400 text-center">Keterangan</th>
                <th class="px-4 py-2 border-orange-400 text-center">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($absensi as $i => $row)
            <tr class="bg-white border-b border-orange-200 hover:bg-orange-50">
                <td class="px-4 py-2 border-orange-200 text-center">{{ $i+1 }}</td>
                <td class="px-4 py-2 border-orange-200">{{ $row->siswa->nama ?? '-' }}</td>
                <td class="px-4 py-2 border-orange-200 text-center">{{ $row->rombel && $row->rombel->siswa ? $row->rombel->siswa->nis : '-' }}</td>
                <td class="px-4 py-2 border-orange-200 text-center">{{ $row->rombel && $row->rombel->kelas ? $row->rombel->kelas->nama : '-' }}</td>
                <td class="px-4 py-2 border-orange-200 text-center">{{ $row->rombel->nomor_absen ?? ($row->siswa->rombel->nomor_absen ?? '-') }}</td>
                <td class="px-4 py-2 border-orange-200 text-center">{{ $row->tanggal ? \Carbon\Carbon::parse($row->tanggal)->toDateString() : '-' }}</td>
                <td class="px-4 py-2 border-orange-200 text-center">{{ $row->jam_masuk ?? '-' }}</td>
                <td class="px-4 py-2 border-orange-200 text-center">{{ $row->jam_pulang ?? '-' }}</td>
                <td class="px-4 py-2 border-orange-200 text-center">{{ $row->status }}</td>
                <td class="px-4 py-2 border-orange-200 text-center">{{ $row->keterangan ?? '-' }}</td>
                <td class="px-4 py-2 border-orange-200 text-center">
                    <a href="{{ route('absensi.show', $row) }}" class="text-blue-600">Detail</a>
                    <a href="{{ route('absensi.edit', $row) }}" class="text-pink-600 ml-2">Edit</a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection

@section('scripts')
<script>
function fetchAbsensi() {
    const search = document.getElementById('search').value;
    const tanggal = document.getElementById('tanggal').value || new Date().toISOString().slice(0,10); // default hari ini
    const kelas_id = document.getElementById('kelas_id').value;

    let url = `/api/absensi-terbaru?search=${encodeURIComponent(search)}&tanggal=${encodeURIComponent(tanggal)}&kelas_id=${encodeURIComponent(kelas_id)}`;
    fetch(url)
        .then(res => res.json())
        .then(data => {
            let tbody = '';
            let countHadir = 0, countIzin = 0, countSakit = 0, countAlpha = 0;

            // Ambil semua siswa di kelas yang difilter
            fetch(`/api/siswa?kelas_id=${kelas_id}`)
                .then(res => res.json())
                .then(siswaList => {
                    // Buat array NIS siswa yang sudah absen hari itu
                    let absenNis = data.map(row => row.siswa_nis);

                    // Hitung siswa yang belum absen (belum hadir)
                    let countBelum = siswaList.filter(siswa => !absenNis.includes(siswa.nis)).length;

                    // Hitung status dari data absensi (case-insensitive)
                    data.forEach(row => {
                        const status = (row.status || '').toString().toLowerCase();
                        if (status === 'hadir') countHadir++;
                        else if (status === 'izin') countIzin++;
                        else if (status === 'sakit') countSakit++;
                        else if (status === 'alpha') countAlpha++;
                    });

                    // Update card
                    document.getElementById('card-belum').textContent = countBelum;
                    document.getElementById('card-hadir').textContent = countHadir;
                    document.getElementById('card-izin').textContent = countIzin;
                    document.getElementById('card-sakit').textContent = countSakit;
                    document.getElementById('card-alpha').textContent = countAlpha;

                    // Update tbody
                    data.forEach((row, i) => {
                        tbody += `<tr class="bg-white border-b border-orange-200 hover:bg-orange-50">
                            <td class="px-4 py-2 border-orange-200 text-center">${i+1}</td>
                            <td class="px-4 py-2 border-orange-200">${row.siswa_nama ?? '-'}</td>
                            <td class="px-4 py-2 border-orange-200 text-center">${row.siswa_nis ?? '-'}</td>
                            <td class="px-4 py-2 border-orange-200 text-center">${row.kelas_nama ?? '-'}</td>
                            <td class="px-4 py-2 border-orange-200 text-center">${row.nomor_absen ?? '-'}</td>
                            <td class="px-4 py-2 border-orange-200 text-center">${row.tanggal ?? '-'}</td>
                            <td class="px-4 py-2 border-orange-200 text-center">${row.jam_masuk ?? '-'}</td>
                            <td class="px-4 py-2 border-orange-200 text-center">${row.jam_pulang ?? '-'}</td>
                            <td class="px-4 py-2 border-orange-200 text-center">${row.status ?? '-'}</td>
                            <td class="px-4 py-2 border-orange-200 text-center">${row.keterangan ?? '-'}</td>
                            <td class="px-4 py-2 border-orange-200 text-center">
                                <a href="/absensi/${row.id}" class="text-blue-600">Detail</a>
                                <a href="/absensi/${row.id}/edit" class="text-pink-600 ml-2">Edit</a>
                            </td>
                        </tr>`;
                    });
                    document.querySelector('tbody').innerHTML = tbody;
                });
        });
}
document.getElementById('search').addEventListener('input', fetchAbsensi);
document.getElementById('tanggal').addEventListener('change', fetchAbsensi);
document.getElementById('kelas_id').addEventListener('change', fetchAbsensi);
setInterval(fetchAbsensi, 3000);
window.addEventListener('DOMContentLoaded', fetchAbsensi);
</script>
<script>
function exportAbsensi() {
    const kelasId = document.getElementById('kelas_export').value;
    if (!kelasId) {
        alert('Silakan pilih kelas dulu');
        return;
    }

    let periode = document.getElementById('periode').value;
    if (!periode) {
        alert('Silakan pilih bulan periode yang ingin diexport');
        return;
    }

    document.getElementById('exportModal').classList.add('hidden');
    window.location.href = `/absensi/export/pdf?kelas_id=${kelasId}&periode=${periode}`;
}
</script>
@endsection
