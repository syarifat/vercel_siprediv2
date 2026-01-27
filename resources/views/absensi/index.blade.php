@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto space-y-6">
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">Rekap Absensi Siswa</h2>
            <p class="text-sm text-gray-500">Monitor kehadiran siswa secara real-time.</p>
        </div>
    </div>

    <div class="grid grid-cols-2 md:grid-cols-5 gap-4">
        <button onclick="toggleCardDetails('belum')" class="bg-blue-50 border border-blue-100 rounded-xl p-4 text-center hover:shadow-md hover:border-blue-300 transition group focus:outline-none focus:ring-2 focus:ring-blue-500">
            <div class="text-3xl font-bold text-blue-600 mb-1" id="card-belum">0</div>
            <div class="text-xs font-semibold text-blue-800 uppercase tracking-wide group-hover:text-blue-600">Belum Hadir</div>
        </button>
        <button onclick="toggleCardDetails('hadir')" class="bg-green-50 border border-green-100 rounded-xl p-4 text-center hover:shadow-md hover:border-green-300 transition group focus:outline-none focus:ring-2 focus:ring-green-500">
            <div class="text-3xl font-bold text-green-600 mb-1" id="card-hadir">0</div>
            <div class="text-xs font-semibold text-green-800 uppercase tracking-wide group-hover:text-green-600">Hadir</div>
        </button>
        <button onclick="toggleCardDetails('sakit')" class="bg-red-50 border border-red-100 rounded-xl p-4 text-center hover:shadow-md hover:border-red-300 transition group focus:outline-none focus:ring-2 focus:ring-red-500">
            <div class="text-3xl font-bold text-red-600 mb-1" id="card-sakit">0</div>
            <div class="text-xs font-semibold text-red-800 uppercase tracking-wide group-hover:text-red-600">Sakit</div>
        </button>
        <button onclick="toggleCardDetails('izin')" class="bg-yellow-50 border border-yellow-100 rounded-xl p-4 text-center hover:shadow-md hover:border-yellow-300 transition group focus:outline-none focus:ring-2 focus:ring-yellow-500">
            <div class="text-3xl font-bold text-yellow-600 mb-1" id="card-izin">0</div>
            <div class="text-xs font-semibold text-yellow-800 uppercase tracking-wide group-hover:text-yellow-600">Izin</div>
        </button>
        <button onclick="toggleCardDetails('alpha')" class="bg-pink-50 border border-pink-100 rounded-xl p-4 text-center hover:shadow-md hover:border-pink-300 transition group focus:outline-none focus:ring-2 focus:ring-pink-500">
            <div class="text-3xl font-bold text-pink-600 mb-1" id="card-alpha">0</div>
            <div class="text-xs font-semibold text-pink-800 uppercase tracking-wide group-hover:text-pink-600">Alpha</div>
        </button>
    </div>

    <div id="details-container" class="hidden transition-all duration-300">
        </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="p-4 border-b border-gray-100 bg-gray-50 flex flex-col md:flex-row gap-4 items-center justify-between">
            <div class="flex flex-col md:flex-row gap-3 w-full md:w-auto">
                <div class="relative w-full md:w-48">
                    <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-400">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35M11 19a8 8 0 100-16 8 8 0 000 16z"></path></svg>
                    </span>
                    <input type="text" id="search" placeholder="Cari Nama / NIS" class="pl-9 pr-4 py-2 border border-gray-300 rounded-lg text-sm focus:ring-orange-500 focus:border-orange-500 w-full">
                </div>
                <input type="date" id="tanggal" class="py-2 px-3 border border-gray-300 rounded-lg text-sm focus:ring-orange-500 focus:border-orange-500 text-gray-600 w-full md:w-auto">
                <select id="kelas_id" class="py-2 px-3 border border-gray-300 rounded-lg text-sm focus:ring-orange-500 focus:border-orange-500 text-gray-600 w-full md:w-auto">
                    <option value="">-- Semua Kelas --</option>
                    @foreach(\App\Models\Kelas::all() as $k)
                        <option value="{{ $k->id }}">{{ $k->nama }}</option>
                    @endforeach
                </select>
            </div>
            
            <button onclick="document.getElementById('exportModal').classList.remove('hidden')" class="px-4 py-2 bg-white border border-gray-300 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-50 transition shadow-sm flex items-center gap-2">
                <svg class="w-4 h-4 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                Export PDF
            </button>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-orange-50">
                    <tr>
                        <th class="px-4 py-3 text-center text-xs font-bold text-orange-800 uppercase">No</th>
                        <th class="px-4 py-3 text-left text-xs font-bold text-orange-800 uppercase">Nama Siswa</th>
                        <th class="px-4 py-3 text-center text-xs font-bold text-orange-800 uppercase">NIS</th>
                        <th class="px-4 py-3 text-center text-xs font-bold text-orange-800 uppercase">Kelas</th>
                        <th class="px-4 py-3 text-center text-xs font-bold text-orange-800 uppercase">Jam Masuk</th>
                        <th class="px-4 py-3 text-center text-xs font-bold text-orange-800 uppercase">Status</th>
                        <th class="px-4 py-3 text-center text-xs font-bold text-orange-800 uppercase">Aksi</th>
                    </tr>
                </thead>
                <tbody id="tbody-absensi" class="bg-white divide-y divide-gray-200">
                    </tbody>
            </table>
        </div>
    </div>
</div>

<div id="exportModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 backdrop-blur-sm transition-opacity duration-300">
    <div class="bg-white rounded-xl shadow-lg p-6 w-96 transform transition-all scale-100">
        <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center gap-2">
            <svg class="w-5 h-5 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
            Export Rekap Absensi
        </h3>
        <div class="space-y-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Pilih Kelas</label>
                <select id="kelas_export" class="w-full border-gray-300 rounded-lg text-sm focus:ring-orange-500 focus:border-orange-500">
                    <option value="">-- Pilih Kelas --</option>
                    @foreach(\App\Models\Kelas::all() as $k)
                        <option value="{{ $k->id }}">{{ $k->nama }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Periode (Bulan)</label>
                <input type="month" id="periode" class="w-full border-gray-300 rounded-lg text-sm focus:ring-orange-500 focus:border-orange-500">
            </div>
        </div>
        <div class="flex justify-end gap-2 mt-6">
            <button onclick="document.getElementById('exportModal').classList.add('hidden')" class="px-4 py-2 text-gray-600 bg-gray-100 hover:bg-gray-200 rounded-lg text-sm font-medium transition">Batal</button>
            <button onclick="exportAbsensi()" class="px-4 py-2 bg-orange-600 text-white rounded-lg text-sm font-medium hover:bg-orange-700 transition shadow">Download PDF</button>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
// State Management
window.currentCardOpen = null;
window.latestData = [];
window.latestSiswaList = [];

// Init Dates
document.getElementById('tanggal').value = new Date().toISOString().slice(0,10);

// --- FETCH DATA LOGIC ---
function fetchAbsensi() {
    const search = document.getElementById('search').value;
    const tanggal = document.getElementById('tanggal').value;
    const kelas_id = document.getElementById('kelas_id').value;

    // URL Ajax (Pastikan route /ajax/... sudah benar di web.php)
    let urlAbsensi = `/ajax/absensi-data?search=${encodeURIComponent(search)}&tanggal=${encodeURIComponent(tanggal)}&kelas_id=${encodeURIComponent(kelas_id)}`;
    let urlSiswa = `/ajax/siswa-data?kelas_id=${encodeURIComponent(kelas_id)}`; // Ambil data master siswa untuk hitung "Belum Hadir"

    // Gunakan Promise.all agar data sinkron
    Promise.all([
        fetch(urlAbsensi, { headers: { 'Accept': 'application/json' } }).then(r => r.json()),
        fetch(urlSiswa, { headers: { 'Accept': 'application/json' } }).then(r => r.json())
    ])
    .then(([absensiData, siswaList]) => {
        window.latestData = absensiData;
        window.latestSiswaList = siswaList;
        
        renderTable(absensiData);
        updateCards(absensiData, siswaList);
        
        // Jika sedang membuka detail kartu, update juga isinya agar realtime
        if (window.currentCardOpen) {
            renderDetails(window.currentCardOpen);
        }
    })
    .catch(err => console.error("Error Fetching Data:", err));
}

// --- RENDER TABLE UTAMA ---
function renderTable(data) {
    let html = '';
    if(data.length === 0) {
        html = '<tr><td colspan="7" class="px-4 py-8 text-center text-gray-500">Tidak ada data absensi ditemukan.</td></tr>';
    } else {
        data.forEach((row, i) => {
            html += `
            <tr class="hover:bg-orange-50/50 transition duration-150">
                <td class="px-4 py-3 text-center text-sm text-gray-500">${i+1}</td>
                <td class="px-4 py-3 text-sm font-medium text-gray-900">${row.siswa_nama ?? '-'}</td>
                <td class="px-4 py-3 text-center text-sm text-gray-500">${row.siswa_nis ?? '-'}</td>
                <td class="px-4 py-3 text-center text-sm text-gray-500">
                    <span class="bg-gray-100 text-gray-600 py-0.5 px-2 rounded text-xs">${row.kelas_nama ?? '-'}</span>
                </td>
                <td class="px-4 py-3 text-center text-sm font-mono text-gray-600">${row.jam_masuk ?? '-'}</td>
                <td class="px-4 py-3 text-center text-sm font-bold uppercase ${getStatusColor(row.status)}">
                    ${row.status ?? '-'}
                </td>
                <td class="px-4 py-3 text-center">
                    <div class="flex justify-center gap-1">
                        <a href="/absensi/${row.id}/edit" class="text-orange-600 hover:text-orange-900 bg-orange-50 p-1.5 rounded hover:bg-orange-100 transition text-xs font-medium" title="Edit Status">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                        </a>
                    </div>
                </td>
            </tr>`;
        });
    }
    document.getElementById('tbody-absensi').innerHTML = html;
}

// Helper Warna Status
function getStatusColor(status) {
    switch(status) {
        case 'hadir': return 'text-green-600 bg-green-50 px-2 py-1 rounded';
        case 'izin': return 'text-yellow-600 bg-yellow-50 px-2 py-1 rounded';
        case 'sakit': return 'text-red-600 bg-red-50 px-2 py-1 rounded';
        case 'alpha': return 'text-pink-600 bg-pink-50 px-2 py-1 rounded';
        default: return 'text-gray-600';
    }
}

// --- UPDATE KARTU STATISTIK ---
function updateCards(data, siswaList) {
    let counts = { hadir: 0, izin: 0, sakit: 0, alpha: 0 };
    
    // Hitung dari data absensi yang masuk
    data.forEach(row => {
        if(counts[row.status] !== undefined) counts[row.status]++;
    });

    // Hitung Belum Hadir = Total Siswa Aktif - Siswa yg sudah absen
    // Kita cek berdasarkan NIS agar akurat
    let absenNis = data.map(d => d.siswa_nis);
    let belumCount = 0;
    
    // Filter siswa yang belum ada di list absensi
    if (siswaList && siswaList.length > 0) {
        belumCount = siswaList.filter(s => !absenNis.includes(s.nis)).length;
    }

    // Update UI
    document.getElementById('card-belum').innerText = belumCount;
    document.getElementById('card-hadir').innerText = counts.hadir;
    document.getElementById('card-sakit').innerText = counts.sakit;
    document.getElementById('card-izin').innerText = counts.izin;
    document.getElementById('card-alpha').innerText = counts.alpha;
}

// --- LOGIC DETAIL CARD (TOGGLE) ---
function toggleCardDetails(status) {
    const container = document.getElementById('details-container');
    
    if (window.currentCardOpen === status) {
        // Jika klik kartu yang sama, tutup detail
        container.classList.add('hidden');
        window.currentCardOpen = null;
    } else {
        // Buka detail baru
        window.currentCardOpen = status;
        renderDetails(status);
        container.classList.remove('hidden');
    }
}

// --- RENDER ISI DETAIL CARD ---
function renderDetails(status) {
    const container = document.getElementById('details-container');
    let rows = [];
    let title = '';
    let colorClass = '';

    if (status === 'belum') {
        title = 'Daftar Siswa Belum Hadir';
        colorClass = 'border-blue-200 bg-blue-50';
        const absenNis = window.latestData.map(d => d.siswa_nis);
        // Ambil data dari Master Siswa yang tidak ada di Absensi Hari Ini
        rows = window.latestSiswaList
            .filter(s => !absenNis.includes(s.nis))
            .map(s => ({ 
                nama: s.nama, 
                nis: s.nis, 
                kelas: s.kelas_nama, 
                info: '<span class="text-xs text-gray-400 italic">Belum Scan</span>' 
            }));
    } else {
        title = `Daftar Siswa ${status.charAt(0).toUpperCase() + status.slice(1)}`;
        // Color mapping
        const map = { hadir: 'green', sakit: 'red', izin: 'yellow', alpha: 'pink' };
        const c = map[status] || 'gray';
        colorClass = `border-${c}-200 bg-${c}-50`;
        
        // Ambil dari data Absensi
        rows = window.latestData
            .filter(d => d.status === status)
            .map(d => ({ 
                nama: d.siswa_nama, 
                nis: d.siswa_nis, 
                kelas: d.kelas_nama, 
                info: d.jam_masuk || d.keterangan || '-' 
            }));
    }

    // Template HTML Detail
    let html = `
        <div class="mb-6 rounded-xl border ${colorClass} p-4 shadow-sm animate-fade-in-down">
            <div class="flex justify-between items-center mb-3">
                <h3 class="font-bold text-gray-800 flex items-center gap-2">
                    <span class="w-2 h-6 rounded-full bg-gray-400"></span> ${title}
                    <span class="bg-white text-xs px-2 py-0.5 rounded border border-gray-200 font-mono">${rows.length} Siswa</span>
                </h3>
                <button onclick="toggleCardDetails('${status}')" class="text-gray-400 hover:text-gray-600 hover:bg-white rounded-full p-1 transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
            <div class="max-h-60 overflow-y-auto bg-white rounded-lg border border-gray-200 scrollbar-thin">
                <table class="min-w-full text-sm">
                    <thead class="bg-gray-50 sticky top-0 z-10">
                        <tr>
                            <th class="px-4 py-2 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Nama</th>
                            <th class="px-4 py-2 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">NIS</th>
                            <th class="px-4 py-2 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">Kelas</th>
                            <th class="px-4 py-2 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">Info</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        ${rows.length ? rows.map(r => `
                            <tr class="hover:bg-gray-50 transition">
                                <td class="px-4 py-2 font-medium text-gray-700">${r.nama}</td>
                                <td class="px-4 py-2 text-center text-gray-500 font-mono text-xs">${r.nis}</td>
                                <td class="px-4 py-2 text-center text-gray-500 text-xs">${r.kelas || '-'}</td>
                                <td class="px-4 py-2 text-center font-mono text-gray-600 text-xs">${r.info}</td>
                            </tr>
                        `).join('') : '<tr><td colspan="4" class="p-8 text-center text-gray-400 text-sm">Tidak ada data siswa untuk kategori ini.</td></tr>'}
                    </tbody>
                </table>
            </div>
        </div>
    `;
    container.innerHTML = html;
}

// --- EXPORT PDF LOGIC ---
function exportAbsensi() {
    const kelasId = document.getElementById('kelas_export').value;
    const periode = document.getElementById('periode').value;
    
    if(!kelasId || !periode) {
        alert('Mohon pilih Kelas dan Periode terlebih dahulu.');
        return;
    }
    
    // Redirect ke route export
    window.location.href = `/absensi/export/pdf?kelas_id=${kelasId}&periode=${periode}`;
    document.getElementById('exportModal').classList.add('hidden');
}

// --- INITIALIZATION ---
// Debounce search
let searchTimeout;
document.getElementById('search').addEventListener('input', () => {
    clearTimeout(searchTimeout);
    searchTimeout = setTimeout(fetchAbsensi, 500);
});

// Event Listeners
document.getElementById('tanggal').addEventListener('change', fetchAbsensi);
document.getElementById('kelas_id').addEventListener('change', fetchAbsensi);

// Auto Refresh every 5 seconds
setInterval(fetchAbsensi, 5000);

// First Load
fetchAbsensi();
</script>
@endsection