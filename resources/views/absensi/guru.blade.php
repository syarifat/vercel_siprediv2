@extends('layouts.app')

@section('content')
<div class="max-w-6xl mx-auto mt-8">
	<h2 class="text-xl font-bold mb-4">Rekap Absensi Guru</h2>

	<!-- Card Rekap Status with inline details -->
	<div class="grid grid-cols-1 gap-4 mb-4">
		<!-- Belum Hadir -->
		<div class="space-y-4">
			<button type="button" onclick="toggleCardDetails('belum')" 
					class="bg-blue-100 rounded-lg shadow p-4 text-center hover:shadow-md transition focus:outline-none w-full sm:w-48" 
					aria-controls="details-belum" aria-expanded="false">
				<div class="text-xl sm:text-2xl font-bold text-blue-600" id="card-belum">0</div>
				<div class="text-xs sm:text-sm font-semibold text-blue-700">Belum Hadir</div>
			</button>
			<div id="details-belum" class="hidden transition-all"></div>
		</div>

		<!-- Hadir -->
		<div class="space-y-4">
			<button type="button" onclick="toggleCardDetails('hadir')" 
					class="bg-green-100 rounded-lg shadow p-4 text-center hover:shadow-md transition focus:outline-none w-full sm:w-48" 
					aria-controls="details-hadir" aria-expanded="false">
				<div class="text-xl sm:text-2xl font-bold text-green-600" id="card-hadir">0</div>
				<div class="text-xs sm:text-sm font-semibold text-green-700">Hadir</div>
			</button>
			<div id="details-hadir" class="hidden transition-all"></div>
		</div>

		<!-- Izin -->
		<div class="space-y-4">
			<button type="button" onclick="toggleCardDetails('izin')" 
					class="bg-yellow-100 rounded-lg shadow p-4 text-center hover:shadow-md transition focus:outline-none w-full sm:w-48" 
					aria-controls="details-izin" aria-expanded="false">
				<div class="text-xl sm:text-2xl font-bold text-yellow-600" id="card-izin">0</div>
				<div class="text-xs sm:text-sm font-semibold text-yellow-700">Izin</div>
			</button>
			<div id="details-izin" class="hidden transition-all"></div>
		</div>

		<!-- Sakit -->
		<div class="space-y-4">
			<button type="button" onclick="toggleCardDetails('sakit')" 
					class="bg-red-100 rounded-lg shadow p-4 text-center hover:shadow-md transition focus:outline-none w-full sm:w-48" 
					aria-controls="details-sakit" aria-expanded="false">
				<div class="text-xl sm:text-2xl font-bold text-red-600" id="card-sakit">0</div>
				<div class="text-xs sm:text-sm font-semibold text-red-700">Sakit</div>
			</button>
			<div id="details-sakit" class="hidden transition-all"></div>
		</div>

		<!-- Alpha -->
		<div class="space-y-4">
			<button type="button" onclick="toggleCardDetails('alpha')" 
					class="bg-pink-100 rounded-lg shadow p-4 text-center hover:shadow-md transition focus:outline-none w-full sm:w-48" 
					aria-controls="details-alpha" aria-expanded="false">
				<div class="text-xl sm:text-2xl font-bold text-pink-600" id="card-alpha">0</div>
				<div class="text-xs sm:text-sm font-semibold text-pink-700">Alpha</div>
			</button>
			<div id="details-alpha" class="hidden transition-all"></div>
		</div>
	</div>

	<!-- Filter untuk View Tabel -->
	<div class="mb-6 grid grid-cols-2 md:grid-cols-3 gap-4 items-center">
		<!-- Search Box -->
		<div class="relative col-span-2 md:col-span-1">
			<input type="text" id="search" placeholder="Masukkan nama guru"
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
					   transition duration-200 shadow-sm text-transparent sm:text-gray-700 sm:focus:text-gray-700">
		</div>

		<!-- (kelas dropdown removed for guru view) -->

		<div class="flex items-center justify-end col-span-1">
			<button type="button" class="w-full sm:w-auto text-center bg-pink-400 hover:bg-pink-500 text-white font-semibold px-4 py-2 rounded-lg shadow transition duration-200 focus:outline-none"
				onclick="document.getElementById('exportModal').classList.remove('hidden')">
				Export PDF
			</button>
		</div>
	</div>

	<!-- Modal Export -->
	<div id="exportModal" class="hidden fixed inset-0 bg-black bg-opacity-40 flex items-center justify-center z-50">
		<div class="bg-white rounded-lg shadow-lg p-6 w-96">
			<h3 class="text-lg font-bold mb-4">Export Rekap Absensi Guru</h3>
			<!-- Filter Bulan -->
			<div class="mb-4">
				<label for="periode_guru" class="block text-sm font-semibold text-gray-600 mb-1">Periode (Bulan)</label>
				<input type="month" id="periode_guru"
					class="border-2 border-gray-300 rounded-lg px-3 py-2 w-full
						   focus:outline-none focus:ring-2 focus:ring-pink-400 focus:border-pink-400
						   transition duration-200 shadow-sm text-gray-700">
			</div>
			<!-- Tombol Aksi -->
			<div class="flex justify-end gap-2">
				<button type="button" class="px-4 py-2 bg-gray-300 rounded-lg hover:bg-gray-400 transition"
					onclick="document.getElementById('exportModal').classList.add('hidden')">Batal</button>
				<button type="button" class="px-4 py-2 bg-pink-500 text-white rounded-lg hover:bg-pink-600 transition"
					onclick="exportAbsensiGuru('pdf')">Download PDF</button>
			</div>
		</div>
	</div>

	<!-- Tabel Absensi -->
	<div class="overflow-x-auto">
	<table class="min-w-full border-2 border-orange-400 rounded-lg overflow-hidden shadow table-auto text-sm">
		<thead>
			<tr class="bg-orange-500 text-white">
				<th class="px-2 sm:px-4 py-1 sm:py-2 border-orange-400 text-center">No</th>
				<th class="px-2 sm:px-4 py-1 sm:py-2 border-orange-400">Nama Guru</th>
				<th class="px-2 sm:px-4 py-1 sm:py-2 border-orange-400 text-center">Tanggal</th>
				<th class="px-2 sm:px-4 py-1 sm:py-2 border-orange-400 text-center">Jam Masuk</th>
				<th class="px-2 sm:px-4 py-1 sm:py-2 border-orange-400 text-center">Jam Pulang</th>
				<th class="px-2 sm:px-4 py-1 sm:py-2 border-orange-400 text-center">Status</th>
				<th class="hidden sm:table-cell px-2 sm:px-4 py-1 sm:py-2 border-orange-400 text-center">Keterangan</th>
				<th class="px-2 sm:px-4 py-1 sm:py-2 border-orange-400 text-center">Aksi</th>
			</tr>
		</thead>
		<tbody id="tbody-absensi">
			@foreach($absensi as $i => $row)
			<tr class="bg-white border-b border-orange-200 hover:bg-orange-50">
				<td class="px-2 sm:px-4 py-1 sm:py-2 border-orange-200 text-center">{{ $i+1 }}</td>
				<td class="px-2 sm:px-4 py-1 sm:py-2 border-orange-200">{{ $row->guru->nama ?? '-' }}</td>
				<td class="px-2 sm:px-4 py-1 sm:py-2 border-orange-200 text-center">{{ $row->tanggal ? \Carbon\Carbon::parse($row->tanggal)->toDateString() : '-' }}</td>
				<td class="px-2 sm:px-4 py-1 sm:py-2 border-orange-200 text-center">{{ $row->jam_masuk ?? '-' }}</td>
				<td class="px-2 sm:px-4 py-1 sm:py-2 border-orange-200 text-center">{{ $row->jam_pulang ?? '-' }}</td>
				<td class="px-2 sm:px-4 py-1 sm:py-2 border-orange-200 text-center">{{ $row->status }}</td>
				<td class="hidden sm:table-cell px-2 sm:px-4 py-1 sm:py-2 border-orange-200 text-center">{{ $row->keterangan ?? '-' }}</td>
				<td class="px-2 sm:px-4 py-1 sm:py-2 border-orange-200 text-center">
					<a href="{{ route('absensi_guru.edit', $row) }}" class="text-blue-600">Edit</a>
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
// Full guru master list (used to compute "Belum Hadir")
const initialGuruList = @json(\App\Models\Guru::all(['id','nama'])->toArray());

// client-side holders for latest fetched data and currently opened card
window.currentCardOpen = null;
window.latestGuruFilteredData = [];
window.latestGuruAllData = [];

// Live fetch-based filtering: will call /api/absensi-guru-terbaru

function renderTable(data) {
	let tbody = '';
	data.forEach((row, i) => {
		tbody += `<tr class="bg-white border-b border-orange-200 hover:bg-orange-50">
			<td class="px-2 sm:px-4 py-1 sm:py-2 border-orange-200 text-center">${i+1}</td>
			<td class="px-2 sm:px-4 py-1 sm:py-2 border-orange-200">${row.guru_nama ?? '-'}</td>
			<td class="px-2 sm:px-4 py-1 sm:py-2 border-orange-200 text-center">${row.tanggal ?? '-'}</td>
			<td class="px-2 sm:px-4 py-1 sm:py-2 border-orange-200 text-center">${row.jam_masuk ?? '-'}</td>
			<td class="px-2 sm:px-4 py-1 sm:py-2 border-orange-200 text-center">${row.jam_pulang ?? '-'}</td>
			<td class="px-2 sm:px-4 py-1 sm:py-2 border-orange-200 text-center">${row.status ?? '-'}</td>
			<td class="hidden sm:table-cell px-2 sm:px-4 py-1 sm:py-2 border-orange-200 text-center">${row.keterangan ?? '-'}</td>
			<td class="px-2 sm:px-4 py-1 sm:py-2 border-orange-200 text-center"><a href="/absensi_guru/${row.id}/edit" class="text-blue-600">Edit</a></td>
		</tr>`;
	});
	document.getElementById('tbody-absensi').innerHTML = tbody;
}

function updateCards(data, filteredGuruCountMissing) {
	let countHadir = 0, countIzin = 0, countSakit = 0, countAlpha = 0;
	data.forEach(row => {
		const status = (row.status || '').toString().toLowerCase();
		if (status === 'hadir') countHadir++;
		else if (status === 'izin') countIzin++;
		else if (status === 'sakit') countSakit++;
		else if (status === 'alpha') countAlpha++;
	});

	document.getElementById('card-belum').textContent = filteredGuruCountMissing;
	document.getElementById('card-hadir').textContent = countHadir;
	document.getElementById('card-izin').textContent = countIzin;
	document.getElementById('card-sakit').textContent = countSakit;
	document.getElementById('card-alpha').textContent = countAlpha;
}

function filterAbsensi() {
	const search = document.getElementById('search').value.trim();
	const tanggal = document.getElementById('tanggal').value || new Date().toISOString().slice(0,10);

	// Build params for filtered fetch
	const params = new URLSearchParams();
	if (search) params.append('search', search);
	if (tanggal) params.append('tanggal', tanggal);

	// Fetch filtered data (for table)
	const filteredUrl = `/api/absensi-guru-terbaru?${params.toString()}`;
	const fetchFiltered = fetch(filteredUrl).then(r => r.json());

	// Fetch all attendance for the date (no search) to compute 'belum hadir'
	const allUrl = `/api/absensi-guru-terbaru?tanggal=${encodeURIComponent(tanggal)}`;
	const fetchAllForDate = fetch(allUrl).then(r => r.json());

	Promise.all([fetchFiltered, fetchAllForDate]).then(([filteredData, allData]) => {
		// store latest data so card-detail panel can use it
		window.latestGuruFilteredData = filteredData || [];
		window.latestGuruAllData = allData || [];

		// filteredData -> data to render in table
		renderTable(window.latestGuruFilteredData);

		// compute counts based on filteredData (for status cards)
		updateCards(window.latestGuruFilteredData, Math.max(0, initialGuruList.length - [...new Set(window.latestGuruAllData.map(d => d.guru_nama))].length));

		// if a card details panel is open, refresh its content
		if (window.currentCardOpen) {
			renderCardDetails(window.currentCardOpen);
		}
	}).catch(err => {
		console.error('Failed to fetch absensi guru data', err);
	});
}

document.getElementById('search').addEventListener('input', filterAbsensi);
document.getElementById('tanggal').addEventListener('change', filterAbsensi);
setInterval(filterAbsensi, 3000);
window.addEventListener('DOMContentLoaded', filterAbsensi);

// Card detail functionality with color themes
function toggleCardDetails(status) {
    const container = document.getElementById(`details-${status}`);
    const allContainers = document.querySelectorAll('[id^="details-"]');
    
    // Close other containers first
    allContainers.forEach(cont => {
        if (cont.id !== `details-${status}`) {
            cont.classList.add('hidden');
        }
    });

    if (window.currentCardOpen === status) {
        // close current
        container.classList.add('hidden');
        window.currentCardOpen = null;
        return;
    }
    
    window.currentCardOpen = status;
    renderCardDetails(status);
    container.classList.remove('hidden');
    // scroll to details smoothly
    container.scrollIntoView({ behavior: 'smooth', block: 'start' });
}

function renderCardDetails(status) {
    const container = document.getElementById(`details-${status}`);
    const tanggal = document.getElementById('tanggal').value || new Date().toISOString().slice(0,10);

    const filtered = window.latestGuruFilteredData || [];
    const allData = window.latestGuruAllData || [];

    // Get color scheme based on status
    const getColorScheme = (status) => {
        switch(status) {
            case 'belum':
                return {
                    border: 'border-blue-200',
                    bg: 'bg-blue-100',
                    text: 'text-blue-800',
                    hover: 'hover:bg-blue-50'
                };
            case 'hadir':
                return {
                    border: 'border-green-200',
                    bg: 'bg-green-100',
                    text: 'text-green-800',
                    hover: 'hover:bg-green-50'
                };
            case 'izin':
                return {
                    border: 'border-yellow-200',
                    bg: 'bg-yellow-100',
                    text: 'text-yellow-800',
                    hover: 'hover:bg-yellow-50'
                };
            case 'sakit':
                return {
                    border: 'border-red-200',
                    bg: 'bg-red-100',
                    text: 'text-red-800',
                    hover: 'hover:bg-red-50'
                };
            case 'alpha':
                return {
                    border: 'border-pink-200',
                    bg: 'bg-pink-100',
                    text: 'text-pink-800',
                    hover: 'hover:bg-pink-50'
                };
            default:
                return {
                    border: 'border-gray-200',
                    bg: 'bg-gray-100',
                    text: 'text-gray-800',
                    hover: 'hover:bg-gray-50'
                };
        }
    };

    const colors = getColorScheme(status);

    const renderRows = (rows) => {
        if (!rows.length) return `<tr><td colspan="4" class="px-4 py-2 text-center text-gray-500">Tidak ada data</td></tr>`;
        return rows.map((r, i) => `
            <tr class="bg-white border-b ${colors.border} ${colors.hover}">
                <td class="px-2 sm:px-4 py-1 sm:py-2 text-center">${i+1}</td>
                <td class="px-2 sm:px-4 py-1 sm:py-2">${r.guru_nama ?? r.nama ?? '-'}</td>
                <td class="px-2 sm:px-4 py-1 sm:py-2 text-center">${r.jam_masuk ?? '-'}</td>
                <td class="px-2 sm:px-4 py-1 sm:py-2 text-center">${r.keterangan ?? '-'}</td>
            </tr>
        `).join('');
    };

    let title = '';
    let rowsHtml = '';

    if (status === 'belum') {
        title = 'Daftar Belum Hadir';
        const presentSet = new Set(allData.map(a => a.guru_nama));
        const missing = initialGuruList
            .filter(g => !presentSet.has(g.nama))
            .map(g => ({ nama: g.nama }));
        // adapt rows to table shape
        const rows = missing.map(m => ({ guru_nama: m.nama }));
        rowsHtml = renderRows(rows);
    } else {
        title = 'Daftar ' + status.charAt(0).toUpperCase() + status.slice(1);
        const filteredRows = filtered
            .filter(a => (a.status || '').toString().toLowerCase() === status)
            .map(a => ({ 
                guru_nama: a.guru_nama, 
                jam_masuk: a.jam_masuk,
                keterangan: a.keterangan
            }));
        rowsHtml = renderRows(filteredRows);
    }

    container.innerHTML = `
        <div class="bg-white border rounded-lg shadow p-4">
            <div class="flex items-center justify-between mb-3">
                <h3 class="font-semibold text-lg ${colors.text}">${title} — ${tanggal}</h3>
                <button type="button" onclick="toggleCardDetails('${status}')" class="text-sm text-gray-600 hover:text-gray-800">Tutup</button>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full border-2 ${colors.border} rounded-lg overflow-hidden shadow table-auto text-sm">
                    <thead>
                        <tr class="${colors.bg} ${colors.text}">
                            <th class="px-2 sm:px-4 py-1 sm:py-2 text-center">No</th>
                            <th class="px-2 sm:px-4 py-1 sm:py-2">Nama Guru</th>
                            <th class="px-2 sm:px-4 py-1 sm:py-2 text-center">Jam Masuk</th>
                            <th class="px-2 sm:px-4 py-1 sm:py-2 text-center">Keterangan</th>
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

function exportAbsensiGuru(type) {
	const periode = document.getElementById('periode_guru').value;
	if (!periode) {
		alert('Silakan pilih periode (bulan) terlebih dahulu');
		return;
	}
	document.getElementById('exportModal').classList.add('hidden');
	let url = `/rekap/absensi-guru/export/${type}?periode=${periode}`;
	window.location.href = url;
}
</script>
@endsection
