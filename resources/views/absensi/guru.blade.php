@extends('layouts.app')

@section('content')
<div class="max-w-6xl mx-auto mt-8">
	<h2 class="text-xl font-bold mb-4">Rekap Absensi Guru</h2>

	<!-- Card Rekap Status -->
	<div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
		<div class="bg-blue-100 rounded-lg shadow p-4 text-center">
			<div class="text-xl sm:text-2xl font-bold text-blue-600" id="card-belum">0</div>
			<div class="text-xs sm:text-sm font-semibold text-blue-700">Belum Hadir</div>
		</div>
		<div class="bg-green-100 rounded-lg shadow p-4 text-center">
			<div class="text-xl sm:text-2xl font-bold text-green-600" id="card-hadir">0</div>
			<div class="text-xs sm:text-sm font-semibold text-green-700">Hadir</div>
		</div>
		<div class="bg-yellow-100 rounded-lg shadow p-4 text-center">
			<div class="text-xl sm:text-2xl font-bold text-yellow-600" id="card-izin">0</div>
			<div class="text-xs sm:text-sm font-semibold text-yellow-700">Izin</div>
		</div>
		<div class="bg-red-100 rounded-lg shadow p-4 text-center">
			<div class="text-xl sm:text-2xl font-bold text-red-600" id="card-sakit">0</div>
			<div class="text-xs sm:text-sm font-semibold text-red-700">Sakit</div>
		</div>
		<div class="bg-pink-100 rounded-lg shadow p-4 text-center">
			<div class="text-xl sm:text-2xl font-bold text-pink-600" id="card-alpha">0</div>
			<div class="text-xs sm:text-sm font-semibold text-pink-700">Alpha</div>
		</div>
	</div>

	<!-- Filter untuk View Tabel -->
	<div class="mb-6 grid grid-cols-2 md:grid-cols-4 gap-4 items-center">
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

		<!-- Dropdown Kelas (kosong — untuk konsistensi layout) -->
		<div class="relative col-span-1">
			<select id="kelas_id"
				class="border-2 border-gray-300 rounded-lg pl-10 pr-4 py-2 w-full sm:w-48
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

// Live fetch-based filtering: will call /api/absensi-guru-terbaru

function renderTable(data) {
	let tbody = '';
	data.forEach((row, i) => {
		tbody += `<tr class="bg-white border-b border-orange-200 hover:bg-orange-50">
			<td class="px-4 py-2 border-orange-200 text-center">${i+1}</td>
			<td class="px-4 py-2 border-orange-200">${row.guru_nama ?? '-'}</td>
			<td class="px-4 py-2 border-orange-200 text-center">${row.tanggal ?? '-'}</td>
			<td class="px-4 py-2 border-orange-200 text-center">${row.jam_masuk ?? '-'}</td>
			<td class="px-4 py-2 border-orange-200 text-center">${row.jam_pulang ?? '-'}</td>
			<td class="px-4 py-2 border-orange-200 text-center">${row.status ?? '-'}</td>
			<td class="px-4 py-2 border-orange-200 text-center">${row.keterangan ?? '-'}</td>
			<td class="px-4 py-2 border-orange-200 text-center"><a href="/absensi_guru/${row.id}/edit" class="text-blue-600">Edit</a></td>
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
		// filteredData -> data to render in table
		renderTable(filteredData);

		// compute counts based on filteredData (for status cards)
		updateCards(filteredData, Math.max(0, initialGuruList.length - [...new Set(allData.map(d => d.guru_nama))].length));
	}).catch(err => {
		console.error('Failed to fetch absensi guru data', err);
	});
}

document.getElementById('search').addEventListener('input', filterAbsensi);
document.getElementById('tanggal').addEventListener('change', filterAbsensi);
setInterval(filterAbsensi, 3000);
window.addEventListener('DOMContentLoaded', filterAbsensi);

function exportAbsensiGuru(type) {
	const periode = document.getElementById('periode_guru').value;
	if (!periode) {
		alert('Silakan pilih periode (bulan) terlebih dahulu');
		return;
	}
	document.getElementById('exportModal').classList.add('hidden');
	window.location.href = `/absensi_guru/export/${type}?periode=${periode}`;
}
</script>
@endsection
