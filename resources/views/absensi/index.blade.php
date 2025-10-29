@extends('layouts.app')

@section('content')
<div class="max-w-6xl mx-auto mt-8">
    <h2 class="text-xl font-bold mb-4">Rekap Absensi Siswa</h2>

    <!-- Card Rekap Status (clickable) -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-4">
        <button type="button" onclick="toggleCardDetails('belum')" class="bg-blue-100 rounded-lg shadow p-4 text-center hover:shadow-md transition focus:outline-none" aria-controls="card-details" aria-expanded="false">
            <div class="text-xl sm:text-2xl font-bold text-blue-600" id="card-belum">0</div>
            <div class="text-xs sm:text-sm font-semibold text-blue-700">Belum Hadir</div>
        </button>
        <button type="button" onclick="toggleCardDetails('hadir')" class="bg-green-100 rounded-lg shadow p-4 text-center hover:shadow-md transition focus:outline-none" aria-controls="card-details" aria-expanded="false">
            <div class="text-xl sm:text-2xl font-bold text-green-600" id="card-hadir">0</div>
            <div class="text-xs sm:text-sm font-semibold text-green-700">Hadir</div>
        </button>
        <button type="button" onclick="toggleCardDetails('izin')" class="bg-yellow-100 rounded-lg shadow p-4 text-center hover:shadow-md transition focus:outline-none" aria-controls="card-details" aria-expanded="false">
            <div class="text-xl sm:text-2xl font-bold text-yellow-600" id="card-izin">0</div>
            <div class="text-xs sm:text-sm font-semibold text-yellow-700">Izin</div>
        </button>
        <button type="button" onclick="toggleCardDetails('sakit')" class="bg-red-100 rounded-lg shadow p-4 text-center hover:shadow-md transition focus:outline-none" aria-controls="card-details" aria-expanded="false">
            <div class="text-xl sm:text-2xl font-bold text-red-600" id="card-sakit">0</div>
            <div class="text-xs sm:text-sm font-semibold text-red-700">Sakit</div>
        </button>
        <button type="button" onclick="toggleCardDetails('alpha')" class="bg-pink-100 rounded-lg shadow p-4 text-center hover:shadow-md transition focus:outline-none" aria-controls="card-details" aria-expanded="false">
            <div class="text-xl sm:text-2xl font-bold text-pink-600" id="card-alpha">0</div>
            <div class="text-xs sm:text-sm font-semibold text-pink-700">Alpha</div>
        </button>
    </div>

    <!-- Card details container (hidden until a card is clicked) -->
    <div id="card-details" class="hidden mb-6">
        <!-- details will be injected here by JS -->
    </div>

    <!-- Filter untuk View Tabel -->
    <div class="mb-6 grid grid-cols-2 md:grid-cols-4 gap-4 items-center">
        <!-- Search Box -->
        <div class="relative col-span-2 md:col-span-1">
            <input type="text" id="search" placeholder="Masukkan nama atau nis"
                class="border-2 border-gray-300 rounded-lg pl-10 pr-4 py-2 w-full
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
        <div class="relative col-span-2 md:col-span-1">
            <input type="date" id="tanggal"
                class="border-2 border-gray-300 rounded-lg px-4 py-2 w-full
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
        <div class="ml-auto w-full sm:w-auto">
            <button type="button" class="w-full sm:w-auto text-center bg-pink-400 hover:bg-pink-500 text-white font-semibold px-4 py-2 rounded-lg shadow transition duration-200 focus:outline-none"
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
                <label class="block text-sm font-medium text-gray-700 mb-1">Pilih Kelas</label>
                <select id="kelas_export"
                    class="border-2 border-gray-300 rounded-lg pl-3 pr-4 py-2 w-full
                           focus:outline-none focus:ring-2 focus:ring-orange-400 focus:border-orange-400
                           transition duration-200 shadow-sm bg-white text-gray-700 font-semibold appearance-none">
                    <option value="">-- Pilih Kelas --</option>
                    @foreach(\App\Models\Kelas::all() as $kelas)
                        <option value="{{ $kelas->id }}">{{ $kelas->nama }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Periode (bulan) -->
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">Periode (Bulan)</label>
                <input type="month" id="periode" class="border-2 border-gray-300 rounded-lg pl-3 pr-4 py-2 w-full focus:outline-none focus:ring-2 focus:ring-orange-400">
            </div>

            <!-- Tahun Ajaran -->
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">Tahun Ajaran</label>
                <select id="tahun_ajaran" class="border-2 border-gray-300 rounded-lg pl-3 pr-4 py-2 w-full focus:outline-none focus:ring-2 focus:ring-orange-400">
                    <option value="">-- (Semua / Default Aktif) --</option>
                    @foreach(\App\Models\TahunAjaran::orderBy('nama','desc')->get() as $ta)
                        <option value="{{ $ta->id }}" {{ $ta->aktif ? 'selected' : '' }}>{{ $ta->nama }} {{ $ta->aktif ? '(Aktif)' : '' }}</option>
                    @endforeach
                </select>
            </div>

            <div class="flex items-center justify-end gap-3">
                <button type="button" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition"
                    onclick="document.getElementById('exportModal').classList.add('hidden')">Batal</button>
                <button type="button" class="px-4 py-2 bg-pink-500 text-white rounded-lg hover:bg-pink-600 transition"
                    onclick="exportAbsensi()">Download PDF</button>
            </div>
        </div>
    </div>

    <!-- Tabel Absensi -->
    <div class="overflow-x-auto">
    <table class="min-w-full border-2 border-orange-400 rounded-lg overflow-hidden shadow table-auto text-sm">
        <thead>
            <tr class="bg-orange-500 text-white">
                <th class="px-2 sm:px-4 py-1 sm:py-2 border-orange-400 text-center">No</th>
                <th class="px-2 sm:px-4 py-1 sm:py-2 border-orange-400">Nama</th>
                <th class="px-2 sm:px-4 py-1 sm:py-2 border-orange-400 text-center">NIS</th>
                <th class="hidden sm:table-cell px-2 sm:px-4 py-1 sm:py-2 border-orange-400 text-center">Kelas</th>
                <th class="hidden sm:table-cell px-2 sm:px-4 py-1 sm:py-2 border-orange-400 text-center">No Absen</th>
                <th class="px-2 sm:px-4 py-1 sm:py-2 border-orange-400 text-center">Tanggal</th>
                <th class="px-2 sm:px-4 py-1 sm:py-2 border-orange-400 text-center">Jam Masuk</th>
                <th class="px-2 sm:px-4 py-1 sm:py-2 border-orange-400 text-center">Jam Pulang</th>
                <th class="px-2 sm:px-4 py-1 sm:py-2 border-orange-400 text-center">Status</th>
                <th class="hidden sm:table-cell px-2 sm:px-4 py-1 sm:py-2 border-orange-400 text-center">Keterangan</th>
                <th class="px-2 sm:px-4 py-1 sm:py-2 border-orange-400 text-center">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($absensi as $i => $row)
            <tr class="bg-white border-b border-orange-200 hover:bg-orange-50">
                <td class="px-2 sm:px-4 py-1 sm:py-2 border-orange-200 text-center">{{ $i+1 }}</td>
                <td class="px-2 sm:px-4 py-1 sm:py-2 border-orange-200">{{ $row->siswa->nama ?? '-' }}</td>
                <td class="px-2 sm:px-4 py-1 sm:py-2 border-orange-200 text-center">{{ $row->rombel && $row->rombel->siswa ? $row->rombel->siswa->nis : '-' }}</td>
                <td class="hidden sm:table-cell px-2 sm:px-4 py-1 sm:py-2 border-orange-200 text-center">{{ $row->rombel && $row->rombel->kelas ? $row->rombel->kelas->nama : '-' }}</td>
                <td class="hidden sm:table-cell px-2 sm:px-4 py-1 sm:py-2 border-orange-200 text-center">{{ $row->rombel->nomor_absen ?? ($row->siswa->rombel->nomor_absen ?? '-') }}</td>
                <td class="px-2 sm:px-4 py-1 sm:py-2 border-orange-200 text-center">{{ $row->tanggal ? \Carbon\Carbon::parse($row->tanggal)->toDateString() : '-' }}</td>
                <td class="px-2 sm:px-4 py-1 sm:py-2 border-orange-200 text-center">{{ $row->jam_masuk ?? '-' }}</td>
                <td class="px-2 sm:px-4 py-1 sm:py-2 border-orange-200 text-center">{{ $row->jam_pulang ?? '-' }}</td>
                <td class="px-2 sm:px-4 py-1 sm:py-2 border-orange-200 text-center">{{ $row->status }}</td>
                <td class="hidden sm:table-cell px-2 sm:px-4 py-1 sm:py-2 border-orange-200 text-center">{{ $row->keterangan ?? '-' }}</td>
                <td class="px-2 sm:px-4 py-1 sm:py-2 border-orange-200 text-center">
                    <a href="{{ route('absensi.edit', $row) }}" class="text-pink-600 text-sm">Edit</a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    </div>
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

                    // store latest fetched data for card details
                    window.latestAbsensiData = data;
                    window.latestSiswaList = siswaList;

                    // Update tbody
                    data.forEach((row, i) => {
                        tbody += `<tr class="bg-white border-b border-orange-200 hover:bg-orange-50">
                            <td class="px-2 sm:px-4 py-1 sm:py-2 border-orange-200 text-center">${i+1}</td>
                            <td class="px-2 sm:px-4 py-1 sm:py-2 border-orange-200">${row.siswa_nama ?? '-'}</td>
                            <td class="px-2 sm:px-4 py-1 sm:py-2 border-orange-200 text-center">${row.siswa_nis ?? '-'}</td>
                            <td class="hidden sm:table-cell px-2 sm:px-4 py-1 sm:py-2 border-orange-200 text-center">${row.kelas_nama ?? '-'}</td>
                            <td class="hidden sm:table-cell px-2 sm:px-4 py-1 sm:py-2 border-orange-200 text-center">${row.nomor_absen ?? '-'}</td>
                            <td class="px-2 sm:px-4 py-1 sm:py-2 border-orange-200 text-center">${row.tanggal ?? '-'}</td>
                            <td class="px-2 sm:px-4 py-1 sm:py-2 border-orange-200 text-center">${row.jam_masuk ?? '-'}</td>
                            <td class="px-2 sm:px-4 py-1 sm:py-2 border-orange-200 text-center">${row.jam_pulang ?? '-'}</td>
                            <td class="px-2 sm:px-4 py-1 sm:py-2 border-orange-200 text-center">${row.status ?? '-'}</td>
                            <td class="hidden sm:table-cell px-2 sm:px-4 py-1 sm:py-2 border-orange-200 text-center">${row.keterangan ?? '-'}</td>
                            <td class="px-2 sm:px-4 py-1 sm:py-2 border-orange-200 text-center">
                                <a href="/absensi/${row.id}/edit" class="text-pink-600 text-sm">Edit</a>
                            </td>
                        </tr>`;
                    });
                    document.querySelector('tbody').innerHTML = tbody;

                    // if details panel is open, refresh it
                    if (window.currentCardOpen) {
                        renderCardDetails(window.currentCardOpen);
                    }
                });
        });
}
document.getElementById('search').addEventListener('input', fetchAbsensi);
document.getElementById('tanggal').addEventListener('change', fetchAbsensi);
document.getElementById('kelas_id').addEventListener('change', fetchAbsensi);
setInterval(fetchAbsensi, 3000);
window.addEventListener('DOMContentLoaded', fetchAbsensi);
// Card detail functionality
window.currentCardOpen = null;
function toggleCardDetails(status) {
    const container = document.getElementById('card-details');
    if (window.currentCardOpen === status) {
        // close
        container.classList.add('hidden');
        window.currentCardOpen = null;
        return;
    }
    window.currentCardOpen = status;
    renderCardDetails(status);
    container.classList.remove('hidden');
    // scroll to details
    container.scrollIntoView({ behavior: 'smooth', block: 'start' });
}

function renderCardDetails(status) {
    const container = document.getElementById('card-details');
    const kelasId = document.getElementById('kelas_id').value;
    const tanggal = document.getElementById('tanggal').value || new Date().toISOString().slice(0,10);

    const absensi = window.latestAbsensiData || [];
    const siswaList = window.latestSiswaList || [];

    // helper to render table rows
    const renderRows = (rows) => {
        if (!rows.length) return `<tr><td colspan="4" class="px-4 py-2 text-center text-gray-500">Tidak ada data</td></tr>`;
        return rows.map((r, i) => `
            <tr class="bg-white border-b border-orange-200 hover:bg-orange-50">
                <td class="px-2 sm:px-4 py-1 sm:py-2 text-center">${i+1}</td>
                <td class="px-2 sm:px-4 py-1 sm:py-2">${r.nama ?? r.siswa_nama ?? '-'}</td>
                <td class="px-2 sm:px-4 py-1 sm:py-2 text-center">${r.nis ?? r.siswa_nis ?? '-'}</td>
                <td class="px-2 sm:px-4 py-1 sm:py-2 text-center">${r.kelas ?? r.kelas_nama ?? '-'}</td>
            </tr>
        `).join('');
    };

    let title = '';
    let rowsHtml = '';

    if (status === 'belum') {
        title = 'Daftar Belum Hadir';
        const absenNis = new Set(absensi.map(a => a.siswa_nis));
        const missing = siswaList
            .filter(s => !absenNis.has(s.nis))
            .map(s => ({ nama: s.nama, nis: s.nis, kelas: s.kelas_nama || '-' }));
        rowsHtml = renderRows(missing);
    } else {
        title = 'Daftar ' + status.charAt(0).toUpperCase() + status.slice(1);
        const filtered = absensi
            .filter(a => (a.status || '').toString().toLowerCase() === status)
            .map(a => ({ nama: a.siswa_nama, nis: a.siswa_nis, kelas: a.kelas_nama }));
        rowsHtml = renderRows(filtered);
    }

    container.innerHTML = `
        <div class="bg-white border rounded-lg shadow p-4">
            <div class="flex items-center justify-between mb-3">
                <h3 class="font-semibold text-lg">${title} — ${tanggal} ${kelasId ? '(Kelas filter aktif)' : ''}</h3>
                <button type="button" onclick="toggleCardDetails('${status}')" class="text-sm text-gray-600 hover:text-gray-800">Tutup</button>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full border-2 border-orange-100 rounded-lg overflow-hidden shadow table-auto text-sm">
                    <thead>
                        <tr class="bg-orange-100 text-orange-800">
                            <th class="px-2 sm:px-4 py-1 sm:py-2 text-center">No</th>
                            <th class="px-2 sm:px-4 py-1 sm:py-2">Nama</th>
                            <th class="px-2 sm:px-4 py-1 sm:py-2 text-center">NIS</th>
                            <th class="px-2 sm:px-4 py-1 sm:py-2 text-center">Kelas</th>
                        </tr>
                    </thead>
                    <tbody>
                        ${rowsHtml}
                    </tbody>
                </table>
            </div>
        </div>
    `;
}
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

    const tahunAjaranId = document.getElementById('tahun_ajaran').value;

    document.getElementById('exportModal').classList.add('hidden');
    let url = `/absensi/export/pdf?kelas_id=${kelasId}&periode=${periode}`;
    if (tahunAjaranId) url += `&tahun_ajaran_id=${tahunAjaranId}`;
    window.location.href = url;
}
</script>
@endsection
