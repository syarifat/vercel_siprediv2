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
        <button onclick="toggleCardDetails('belum')" class="bg-blue-50 border border-blue-100 rounded-xl p-4 text-center hover:shadow-md hover:border-blue-300 transition group">
            <div class="text-3xl font-bold text-blue-600 mb-1" id="card-belum">0</div>
            <div class="text-xs font-semibold text-blue-800 uppercase tracking-wide group-hover:text-blue-600">Belum Hadir</div>
        </button>
        <button onclick="toggleCardDetails('hadir')" class="bg-green-50 border border-green-100 rounded-xl p-4 text-center hover:shadow-md hover:border-green-300 transition group">
            <div class="text-3xl font-bold text-green-600 mb-1" id="card-hadir">0</div>
            <div class="text-xs font-semibold text-green-800 uppercase tracking-wide group-hover:text-green-600">Hadir</div>
        </button>
        <button onclick="toggleCardDetails('sakit')" class="bg-red-50 border border-red-100 rounded-xl p-4 text-center hover:shadow-md hover:border-red-300 transition group">
            <div class="text-3xl font-bold text-red-600 mb-1" id="card-sakit">0</div>
            <div class="text-xs font-semibold text-red-800 uppercase tracking-wide group-hover:text-red-600">Sakit</div>
        </button>
        <button onclick="toggleCardDetails('izin')" class="bg-yellow-50 border border-yellow-100 rounded-xl p-4 text-center hover:shadow-md hover:border-yellow-300 transition group">
            <div class="text-3xl font-bold text-yellow-600 mb-1" id="card-izin">0</div>
            <div class="text-xs font-semibold text-yellow-800 uppercase tracking-wide group-hover:text-yellow-600">Izin</div>
        </button>
        <button onclick="toggleCardDetails('alpha')" class="bg-pink-50 border border-pink-100 rounded-xl p-4 text-center hover:shadow-md hover:border-pink-300 transition group">
            <div class="text-3xl font-bold text-pink-600 mb-1" id="card-alpha">0</div>
            <div class="text-xs font-semibold text-pink-800 uppercase tracking-wide group-hover:text-pink-600">Alpha</div>
        </button>
    </div>

    <div id="details-container" class="hidden transition-all duration-300">
        </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="p-4 border-b border-gray-100 bg-gray-50 flex flex-col md:flex-row gap-4 items-center justify-between">
            <div class="flex flex-col md:flex-row gap-3 w-full md:w-auto">
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-400"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35M11 19a8 8 0 100-16 8 8 0 000 16z"></path></svg></span>
                    <input type="text" id="search" placeholder="Cari Nama / NIS" class="pl-9 pr-4 py-2 border border-gray-300 rounded-lg text-sm focus:ring-orange-500 focus:border-orange-500 w-full md:w-48">
                </div>
                <input type="date" id="tanggal" class="py-2 px-3 border border-gray-300 rounded-lg text-sm focus:ring-orange-500 focus:border-orange-500 text-gray-600">
                <select id="kelas_id" class="py-2 px-3 border border-gray-300 rounded-lg text-sm focus:ring-orange-500 focus:border-orange-500 text-gray-600">
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

<div id="exportModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 backdrop-blur-sm">
    <div class="bg-white rounded-xl shadow-lg p-6 w-96 transform transition-all scale-100">
        <h3 class="text-lg font-bold text-gray-800 mb-4">Export Rekap Absensi</h3>
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
            <button onclick="exportAbsensi()" class="px-4 py-2 bg-orange-600 text-white rounded-lg text-sm font-medium hover:bg-orange-700 transition shadow">Download</button>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
window.currentCardOpen = null;
window.latestData = [];
window.latestSiswaList = [];

// Fetch data logic
function fetchAbsensi() {
    const search = document.getElementById('search').value;
    const tanggal = document.getElementById('tanggal').value || new Date().toISOString().slice(0,10);
    const kelas_id = document.getElementById('kelas_id').value;

    let url = `/api/absensi-terbaru?search=${encodeURIComponent(search)}&tanggal=${encodeURIComponent(tanggal)}&kelas_id=${encodeURIComponent(kelas_id)}`;
    
    Promise.all([
        fetch(url).then(r => r.json()),
        fetch(`/api/siswa?kelas_id=${kelas_id}`).then(r => r.json())
    ]).then(([absensiData, siswaList]) => {
        window.latestData = absensiData;
        window.latestSiswaList = siswaList;
        
        renderTable(absensiData);
        updateCards(absensiData, siswaList);
        
        if (window.currentCardOpen) {
            renderDetails(window.currentCardOpen);
        }
    });
}

function renderTable(data) {
    let html = '';
    if(data.length === 0) {
        html = '<tr><td colspan="7" class="px-4 py-8 text-center text-gray-500">Tidak ada data absensi.</td></tr>';
    } else {
        data.forEach((row, i) => {
            html += `
            <tr class="hover:bg-orange-50/50 transition">
                <td class="px-4 py-3 text-center text-sm text-gray-500">${i+1}</td>
                <td class="px-4 py-3 text-sm font-medium text-gray-900">${row.siswa_nama ?? '-'}</td>
                <td class="px-4 py-3 text-center text-sm text-gray-500">${row.siswa_nis ?? '-'}</td>
                <td class="px-4 py-3 text-center text-sm text-gray-500">${row.kelas_nama ?? '-'}</td>
                <td class="px-4 py-3 text-center text-sm font-mono text-gray-600">${row.jam_masuk ?? '-'}</td>
                <td class="px-4 py-3 text-center text-sm font-bold uppercase ${getStatusColor(row.status)}">${row.status ?? '-'}</td>
                <td class="px-4 py-3 text-center">
                    <a href="/absensi/${row.id}/edit" class="text-orange-600 hover:text-orange-900 bg-orange-50 p-1.5 rounded hover:bg-orange-100 transition text-xs font-medium">Edit</a>
                    <a href="/absensi/${row.id}" class="text-blue-600 hover:text-blue-900 bg-blue-50 p-1.5 rounded hover:bg-blue-100 transition text-xs font-medium ml-1">Detail</a>
                </td>
            </tr>`;
        });
    }
    document.getElementById('tbody-absensi').innerHTML = html;
}

function getStatusColor(status) {
    switch(status) {
        case 'hadir': return 'text-green-600';
        case 'izin': return 'text-yellow-600';
        case 'sakit': return 'text-red-600';
        case 'alpha': return 'text-pink-600';
        default: return 'text-gray-600';
    }
}

function updateCards(data, siswaList) {
    let counts = { hadir: 0, izin: 0, sakit: 0, alpha: 0 };
    let absenNis = data.map(d => d.siswa_nis);
    let belumCount = siswaList.filter(s => !absenNis.includes(s.nis)).length;

    data.forEach(row => {
        if(counts[row.status] !== undefined) counts[row.status]++;
    });

    document.getElementById('card-belum').innerText = belumCount;
    document.getElementById('card-hadir').innerText = counts.hadir;
    document.getElementById('card-sakit').innerText = counts.sakit;
    document.getElementById('card-izin').innerText = counts.izin;
    document.getElementById('card-alpha').innerText = counts.alpha;
}

function toggleCardDetails(status) {
    const container = document.getElementById('details-container');
    
    if (window.currentCardOpen === status) {
        container.classList.add('hidden');
        window.currentCardOpen = null;
    } else {
        window.currentCardOpen = status;
        renderDetails(status);
        container.classList.remove('hidden');
    }
}

function renderDetails(status) {
    const container = document.getElementById('details-container');
    let rows = [];
    let title = '';
    let colorClass = '';

    if (status === 'belum') {
        title = 'Daftar Siswa Belum Hadir';
        colorClass = 'border-blue-200 bg-blue-50';
        const absenNis = window.latestData.map(d => d.siswa_nis);
        rows = window.latestSiswaList
            .filter(s => !absenNis.includes(s.nis))
            .map(s => ({ nama: s.nama, nis: s.nis, kelas: s.kelas_nama, info: 'Belum Scan' }));
    } else {
        title = `Daftar Siswa ${status.charAt(0).toUpperCase() + status.slice(1)}`;
        // Color mapping for header
        const map = { hadir: 'green', sakit: 'red', izin: 'yellow', alpha: 'pink' };
        const c = map[status] || 'gray';
        colorClass = `border-${c}-200 bg-${c}-50`;
        
        rows = window.latestData
            .filter(d => d.status === status)
            .map(d => ({ nama: d.siswa_nama, nis: d.siswa_nis, kelas: d.kelas_nama, info: d.jam_masuk || d.keterangan || '-' }));
    }

    let html = `
        <div class="mb-6 rounded-xl border ${colorClass} p-4">
            <div class="flex justify-between items-center mb-3">
                <h3 class="font-bold text-gray-800">${title}</h3>
                <button onclick="toggleCardDetails('${status}')" class="text-gray-500 hover:text-gray-700">&times;</button>
            </div>
            <div class="max-h-60 overflow-y-auto bg-white rounded-lg border border-gray-200">
                <table class="min-w-full text-sm">
                    <thead class="bg-gray-50 sticky top-0">
                        <tr>
                            <th class="px-4 py-2 text-left">Nama</th>
                            <th class="px-4 py-2 text-center">NIS</th>
                            <th class="px-4 py-2 text-center">Kelas</th>
                            <th class="px-4 py-2 text-center">Info</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        ${rows.length ? rows.map(r => `
                            <tr>
                                <td class="px-4 py-2">${r.nama}</td>
                                <td class="px-4 py-2 text-center text-gray-500">${r.nis}</td>
                                <td class="px-4 py-2 text-center text-gray-500">${r.kelas || '-'}</td>
                                <td class="px-4 py-2 text-center font-mono text-gray-600">${r.info}</td>
                            </tr>
                        `).join('') : '<tr><td colspan="4" class="p-4 text-center text-gray-400">Tidak ada data</td></tr>'}
                    </tbody>
                </table>
            </div>
        </div>
    `;
    container.innerHTML = html;
}

function exportAbsensi() {
    const kelasId = document.getElementById('kelas_export').value;
    const periode = document.getElementById('periode').value;
    
    if(!kelasId || !periode) {
        alert('Mohon pilih Kelas dan Periode.');
        return;
    }
    
    window.location.href = `/absensi/export/pdf?kelas_id=${kelasId}&periode=${periode}`;
    document.getElementById('exportModal').classList.add('hidden');
}

// Init
document.getElementById('search').addEventListener('input', () => setTimeout(fetchAbsensi, 500));
document.getElementById('tanggal').addEventListener('change', fetchAbsensi);
document.getElementById('kelas_id').addEventListener('change', fetchAbsensi);
setInterval(fetchAbsensi, 5000);
fetchAbsensi();
</script>
@endsection