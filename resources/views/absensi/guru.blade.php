@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto space-y-6">
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">Rekap Absensi Guru</h2>
            <p class="text-sm text-gray-500">Monitor kehadiran guru dan staff pengajar.</p>
        </div>
        
        <a href="{{ route('absensi_guru.create') }}" class="inline-flex items-center justify-center px-4 py-2 bg-orange-600 hover:bg-orange-700 text-white text-sm font-medium rounded-lg shadow-sm transition-colors duration-200">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Tambah Absensi
        </a>
    </div>

    <div class="grid grid-cols-2 md:grid-cols-5 gap-4">
        <button onclick="toggleGuruCard('belum')" class="bg-blue-50 border border-blue-100 rounded-xl p-4 text-center hover:shadow-md hover:border-blue-300 transition group">
            <div class="text-3xl font-bold text-blue-600 mb-1" id="g-belum">0</div>
            <div class="text-xs font-semibold text-blue-800 uppercase tracking-wide group-hover:text-blue-600">Belum Hadir</div>
        </button>
        <button onclick="toggleGuruCard('hadir')" class="bg-green-50 border border-green-100 rounded-xl p-4 text-center hover:shadow-md hover:border-green-300 transition group">
            <div class="text-3xl font-bold text-green-600 mb-1" id="g-hadir">0</div>
            <div class="text-xs font-semibold text-green-800 uppercase tracking-wide group-hover:text-green-600">Hadir</div>
        </button>
        <button onclick="toggleGuruCard('sakit')" class="bg-red-50 border border-red-100 rounded-xl p-4 text-center hover:shadow-md hover:border-red-300 transition group">
            <div class="text-3xl font-bold text-red-600 mb-1" id="g-sakit">0</div>
            <div class="text-xs font-semibold text-red-800 uppercase tracking-wide group-hover:text-red-600">Sakit</div>
        </button>
        <button onclick="toggleGuruCard('izin')" class="bg-yellow-50 border border-yellow-100 rounded-xl p-4 text-center hover:shadow-md hover:border-yellow-300 transition group">
            <div class="text-3xl font-bold text-yellow-600 mb-1" id="g-izin">0</div>
            <div class="text-xs font-semibold text-yellow-800 uppercase tracking-wide group-hover:text-yellow-600">Izin</div>
        </button>
        <button onclick="toggleGuruCard('alpha')" class="bg-pink-50 border border-pink-100 rounded-xl p-4 text-center hover:shadow-md hover:border-pink-300 transition group">
            <div class="text-3xl font-bold text-pink-600 mb-1" id="g-alpha">0</div>
            <div class="text-xs font-semibold text-pink-800 uppercase tracking-wide group-hover:text-pink-600">Alpha</div>
        </button>
    </div>

    <div id="guru-details-container" class="hidden transition-all duration-300"></div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="p-4 border-b border-gray-100 bg-gray-50 flex flex-col md:flex-row gap-4 items-center justify-between">
            <div class="flex gap-3 w-full md:w-auto">
                <div class="relative w-full">
                    <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-400"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35M11 19a8 8 0 100-16 8 8 0 000 16z"></path></svg></span>
                    <input type="text" id="search" placeholder="Cari Nama Guru" class="pl-9 pr-4 py-2 border border-gray-300 rounded-lg text-sm focus:ring-orange-500 focus:border-orange-500 w-full md:w-64">
                </div>
                <input type="date" id="tanggal" class="py-2 px-3 border border-gray-300 rounded-lg text-sm focus:ring-orange-500 focus:border-orange-500 text-gray-600">
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
                        <th class="px-4 py-3 text-left text-xs font-bold text-orange-800 uppercase">Nama Guru</th>
                        <th class="px-4 py-3 text-center text-xs font-bold text-orange-800 uppercase">Tanggal</th>
                        <th class="px-4 py-3 text-center text-xs font-bold text-orange-800 uppercase">Jam Masuk</th>
                        <th class="px-4 py-3 text-center text-xs font-bold text-orange-800 uppercase">Jam Pulang</th>
                        <th class="px-4 py-3 text-center text-xs font-bold text-orange-800 uppercase">Status</th>
                        <th class="px-4 py-3 text-center text-xs font-bold text-orange-800 uppercase">Keterangan</th>
                        @if(Auth::user()->role !== 'guru')
                        <th class="px-4 py-3 text-center text-xs font-bold text-orange-800 uppercase">Aksi</th>
                        @endif
                    </tr>
                </thead>
                <tbody id="tbody-guru" class="bg-white divide-y divide-gray-200">
                    </tbody>
            </table>
        </div>
    </div>
</div>

<div id="exportModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 backdrop-blur-sm">
    <div class="bg-white rounded-xl shadow-lg p-6 w-96">
        <h3 class="text-lg font-bold text-gray-800 mb-4">Export Absensi Guru</h3>
        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700 mb-1">Periode (Bulan)</label>
            <input type="month" id="periode_guru" class="w-full border-gray-300 rounded-lg text-sm focus:ring-orange-500 focus:border-orange-500">
        </div>
        <div class="flex justify-end gap-2">
            <button onclick="document.getElementById('exportModal').classList.add('hidden')" class="px-4 py-2 text-gray-600 bg-gray-100 hover:bg-gray-200 rounded-lg text-sm font-medium">Batal</button>
            <button onclick="exportPdf()" class="px-4 py-2 bg-orange-600 text-white rounded-lg text-sm font-medium hover:bg-orange-700 shadow">Download</button>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
// Data master guru untuk hitung "Belum Hadir"
const allGuruList = @json(\App\Models\Guru::where('status', 'aktif')->get(['id','nama'])); 
window.guruData = [];
window.currentGuruCard = null;

// Init Dates
document.getElementById('tanggal').value = new Date().toISOString().slice(0,10);

function fetchGuru() {
    const search = document.getElementById('search').value;
    const tanggal = document.getElementById('tanggal').value;
    
    // PERBAIKAN URL: Sesuaikan dengan routes/web.php (/ajax/absensi-guru-data)
    const url = `/ajax/absensi-guru-data?search=${encodeURIComponent(search)}&tanggal=${encodeURIComponent(tanggal)}`;
    
    fetch(url, {
        headers: {
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(r => {
        if (!r.ok) throw new Error("HTTP Error " + r.status);
        return r.json();
    })
    .then(data => {
        window.guruData = data;
        renderGuruTable(data);
        updateGuruCards(data);
        
        if(window.currentGuruCard) {
            renderGuruDetails(window.currentGuruCard);
        }
    })
    .catch(err => console.error("Error Fetching Guru:", err));
}

function renderGuruTable(data) {
    let html = '';
    const userRole = '{{ Auth::user()->role }}'; // Ambil role dari PHP

    if(data.length === 0) {
        html = '<tr><td colspan="8" class="px-4 py-8 text-center text-gray-500">Tidak ada data absensi guru.</td></tr>';
    } else {
        data.forEach((row, i) => {
            html += `
            <tr class="hover:bg-orange-50/50 transition duration-150">
                <td class="px-4 py-3 text-center text-sm text-gray-500">${i+1}</td>
                <td class="px-4 py-3 text-sm font-medium text-gray-900">${row.guru_nama ?? '-'}</td>
                <td class="px-4 py-3 text-center text-sm text-gray-500">${row.tanggal ?? '-'}</td>
                <td class="px-4 py-3 text-center text-sm font-mono text-gray-600">${row.jam_masuk ?? '-'}</td>
                <td class="px-4 py-3 text-center text-sm font-mono text-gray-600">${row.jam_pulang ?? '-'}</td>
                <td class="px-4 py-3 text-center text-sm font-bold uppercase">
                    <span class="${getGuruColor(row.status)} bg-opacity-20 px-2 py-1 rounded bg-gray-100">${row.status ?? '-'}</span>
                </td>
                <td class="px-4 py-3 text-center text-sm text-gray-500">${row.keterangan ?? '-'}</td>
                ${ userRole !== 'guru' ? `
                <td class="px-4 py-3 text-center">
                    <div class="flex justify-center gap-1">
                        <a href="/absensi_guru/${row.id}/edit" class="text-orange-600 hover:text-orange-900 bg-orange-50 p-1.5 rounded hover:bg-orange-100 transition text-xs font-medium">Edit</a>
                    </div>
                </td>` : '' }
            </tr>`;
        });
    }
    document.getElementById('tbody-guru').innerHTML = html;
}

function getGuruColor(status) {
    switch(status) {
        case 'hadir': return 'text-green-600';
        case 'izin': return 'text-yellow-600';
        case 'sakit': return 'text-red-600';
        case 'alpha': return 'text-pink-600';
        default: return 'text-gray-600';
    }
}

function updateGuruCards(data) {
    let counts = { hadir: 0, izin: 0, sakit: 0, alpha: 0 };
    
    // Hitung kehadiran
    data.forEach(row => {
        if(counts[row.status] !== undefined) counts[row.status]++;
    });

    // Hitung Belum Hadir (Guru Aktif - Guru yang sudah absen hari ini)
    // Bandingkan nama (atau idealnya ID jika API mengirim ID guru)
    let presentNames = data.map(d => d.guru_nama);
    let belumCount = allGuruList.filter(g => !presentNames.includes(g.nama)).length;

    document.getElementById('g-belum').innerText = belumCount;
    document.getElementById('g-hadir').innerText = counts.hadir;
    document.getElementById('g-izin').innerText = counts.izin;
    document.getElementById('g-sakit').innerText = counts.sakit;
    document.getElementById('g-alpha').innerText = counts.alpha;
}

function toggleGuruCard(status) {
    const container = document.getElementById('guru-details-container');
    if (window.currentGuruCard === status) {
        container.classList.add('hidden');
        window.currentGuruCard = null;
    } else {
        window.currentGuruCard = status;
        renderGuruDetails(status);
        container.classList.remove('hidden');
    }
}

function renderGuruDetails(status) {
    const container = document.getElementById('guru-details-container');
    let rows = [];
    let title = '';
    let colorClass = '';

    if (status === 'belum') {
        title = 'Guru Belum Hadir';
        colorClass = 'border-blue-200 bg-blue-50';
        const presentNames = window.guruData.map(d => d.guru_nama);
        rows = allGuruList.filter(g => !presentNames.includes(g.nama))
            .map(g => ({ nama: g.nama, jam: '<span class="italic text-gray-400">Belum Scan</span>', ket: '-' }));
    } else {
        title = `Guru ${status.charAt(0).toUpperCase() + status.slice(1)}`;
        const map = { hadir: 'green', sakit: 'red', izin: 'yellow', alpha: 'pink' };
        const c = map[status] || 'gray';
        colorClass = `border-${c}-200 bg-${c}-50`;
        
        rows = window.guruData.filter(d => d.status === status)
            .map(d => ({ nama: d.guru_nama, jam: d.jam_masuk || '-', ket: d.keterangan || '-' }));
    }

    let html = `
        <div class="mb-6 rounded-xl border ${colorClass} p-4 animate-fade-in-down">
            <div class="flex justify-between items-center mb-3">
                <h3 class="font-bold text-gray-800 flex items-center gap-2">
                    <span class="w-2 h-6 rounded-full bg-gray-400"></span> ${title}
                </h3>
                <button onclick="toggleGuruCard('${status}')" class="text-gray-500 hover:text-gray-700 font-bold text-xl">&times;</button>
            </div>
            <div class="max-h-60 overflow-y-auto bg-white rounded-lg border border-gray-200">
                <table class="min-w-full text-sm">
                    <thead class="bg-gray-50 sticky top-0">
                        <tr>
                            <th class="px-4 py-2 text-left text-xs font-semibold text-gray-600 uppercase">Nama Guru</th>
                            <th class="px-4 py-2 text-center text-xs font-semibold text-gray-600 uppercase">Jam</th>
                            <th class="px-4 py-2 text-center text-xs font-semibold text-gray-600 uppercase">Keterangan</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        ${rows.length ? rows.map(r => `
                            <tr class="hover:bg-gray-50 transition">
                                <td class="px-4 py-2 font-medium text-gray-700">${r.nama}</td>
                                <td class="px-4 py-2 text-center font-mono text-gray-600">${r.jam}</td>
                                <td class="px-4 py-2 text-center text-gray-500">${r.ket}</td>
                            </tr>
                        `).join('') : '<tr><td colspan="3" class="p-4 text-center text-gray-400">Tidak ada data</td></tr>'}
                    </tbody>
                </table>
            </div>
        </div>
    `;
    container.innerHTML = html;
}

function exportPdf() {
    const periode = document.getElementById('periode_guru').value;
    if(!periode) {
        alert('Mohon pilih periode (bulan & tahun) terlebih dahulu.');
        return;
    }
    // Redirect ke route export PDF
    window.location.href = `/rekap/absensi-guru/export/pdf?periode=${periode}`;
    document.getElementById('exportModal').classList.add('hidden');
}

// --- INIT ---
// Debounce search input
let searchTimeout;
document.getElementById('search').addEventListener('input', () => {
    clearTimeout(searchTimeout);
    searchTimeout = setTimeout(fetchGuru, 500);
});

document.getElementById('tanggal').addEventListener('change', fetchGuru);

// Refresh data tiap 5 detik
setInterval(fetchGuru, 5000);

// Load pertama kali
fetchGuru();
</script>
@endsection