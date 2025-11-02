@extends('layouts.app')

@section('content')
<div class="max-w-lg mx-auto mt-8">
    <h2 class="text-xl font-bold mb-4">Tambah Rombel Siswa</h2>
    
    <div class="bg-orange-100 border-l-4 border-orange-500 text-orange-700 p-4 mb-4">
        <p class="font-bold">Tahun Ajaran: {{ $tahunAjaran ? ($tahunAjaran->nama . ' - ' . $tahunAjaran->semester) : 'Belum dipilih' }}</p>
        <p class="text-sm mt-1">Hanya menampilkan siswa yang belum memiliki kelas di tahun ajaran ini</p>
    </div>

    @if($siswa->isEmpty())
        <div class="bg-yellow-100 border-l-4 border-yellow-500 text-yellow-700 p-4">
            <p class="font-bold">Tidak ada siswa yang tersedia</p>
            <p class="text-sm mt-1">Semua siswa sudah memiliki kelas di tahun ajaran ini.</p>
            <a href="{{ route('rombel_siswa.index') }}" class="text-blue-600 hover:text-blue-800 mt-2 inline-block">← Kembali ke daftar rombel</a>
        </div>
    @else
    <form method="POST" action="{{ route('rombel_siswa.mass_store') }}" class="space-y-4">
        @csrf
        <div>
            <div class="flex justify-between items-center mb-2">
                <label class="block">Pilih Siswa ({{ $siswa->count() }} siswa tersedia)</label>
                <span id="selectedCount" class="text-sm text-blue-600 font-semibold">0 siswa dipilih</span>
            </div>
            <div class="mb-3">
                <input type="text" 
                    id="searchInput" 
                    placeholder="Cari berdasarkan nama atau NIS..." 
                    class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                    oninput="filterSiswa(this.value)">
            </div>
            <div class="border rounded px-2 py-1 bg-white overflow-x-auto">
                <table class="min-w-full">
                    <thead>
                        <tr class="bg-gray-100">
                            <th class="px-2 py-1 text-center">
                                <input type="checkbox" id="checkAll" onclick="toggleAll(this)">
                            </th>
                            <th class="px-2 py-1 text-center">NIS</th>
                            <th class="px-2 py-1 text-left">Nama</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($siswa as $row)
                        <tr>
                            <td class="px-2 py-1 text-center">
                                <input type="checkbox" name="siswa_id[]" value="{{ $row->id }}" class="siswa-check">
                            </td>
                            <td class="px-2 py-1 text-center">{{ $row->nis }}</td>
                            <td class="px-2 py-1 text-left">{{ $row->nama }} <span class="assigned-note text-sm text-gray-500 ml-2"></span></td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        <div>
            <label class="block mb-2">Kelas</label>
            <select name="kelas_id" class="border rounded px-2 py-1 w-full" required>
                <option value="">- Pilih Kelas -</option>
                @foreach($kelas as $row)
                <option value="{{ $row->id }}">{{ $row->nama }}</option>
                @endforeach
            </select>
        </div>
        <input type="hidden" name="tahun_ajaran_id" value="{{ $tahunAjaran->id }}">
        <div class="flex justify-between items-center">
            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Masukkan ke Kelas</button>
            <a href="{{ route('rombel_siswa.index') }}" class="text-gray-600 hover:text-gray-800">← Kembali ke daftar</a>
        </div>
    </form>
    @endif
</div>
<script>
function updateSelectedCount() {
    const checkedCount = document.querySelectorAll('.siswa-check:checked').length;
    document.getElementById('selectedCount').textContent = `${checkedCount} siswa dipilih`;
}

function toggleAll(source) {
    document.querySelectorAll('.siswa-check:not(:disabled)').forEach(cb => {
        cb.checked = source.checked;
    });
    updateSelectedCount();
}

// Add event listeners when document is ready
document.addEventListener('DOMContentLoaded', function() {
    // Add listeners to checkboxes
    document.querySelectorAll('.siswa-check').forEach(checkbox => {
        checkbox.addEventListener('change', updateSelectedCount);
    });
    
    // Add listener to search input
    document.getElementById('searchInput').addEventListener('input', function(e) {
        filterSiswa(e.target.value);
    });

    // Initialize selected count
    updateSelectedCount();
});

function filterSiswa(query) {
    query = query.toLowerCase().trim();
    const rows = document.querySelectorAll('tbody tr');
    let visibleCount = 0;
    
    rows.forEach(row => {
        const nis = row.querySelector('td:nth-child(2)').textContent.toLowerCase();
        const nama = row.querySelector('td:nth-child(3)').textContent.toLowerCase().split('Sudah:')[0].trim(); // Ambil nama saja, tanpa catatan "Sudah:"
        
        if (nis.includes(query) || nama.includes(query)) {
            row.style.display = '';
            visibleCount++;
        } else {
            row.style.display = 'none';
        }
    });

    // Update "Select All" checkbox based on filtered items
    const checkAllBox = document.getElementById('checkAll');
    if (visibleCount === 0) {
        checkAllBox.disabled = true;
        checkAllBox.checked = false;
    } else {
        checkAllBox.disabled = false;
    }
}
</script>
<script>
// When tahun_ajaran is selected, fetch existing rombel for that year and mark students already assigned
document.addEventListener('DOMContentLoaded', function() {
    function refreshAssignedNotes() {
        const tahunId = '{{ session('tahun_ajaran_id') }}';
        // clear notes and enable checkboxes by default
        document.querySelectorAll('.siswa-check').forEach(cb => { cb.disabled = false; cb.checked = false; });
        document.querySelectorAll('.assigned-note').forEach(span => span.textContent = '');
        if (!tahunId) {
            alert('Pilih tahun ajaran di navigation bar terlebih dahulu');
            return;
        fetch(`/api/rombel-siswa?tahun_ajaran_id=${encodeURIComponent(tahunId)}`)
            .then(r => r.json())
            .then(data => {
                const assignedBySiswa = {};
                data.forEach(r => { assignedBySiswa[r.siswa_id] = r; });
                document.querySelectorAll('.siswa-check').forEach(cb => {
                    const sid = parseInt(cb.value, 10);
                    if (assignedBySiswa[sid]) {
                        cb.disabled = true;
                        const noteEl = cb.closest('tr').querySelector('.assigned-note');
                        if (noteEl) noteEl.textContent = `Sudah: ${assignedBySiswa[sid].kelas_nama} (No ${assignedBySiswa[sid].nomor_absen})`;
                    }
                });
            }).catch(err => console.error('Failed to fetch rombel for tahun', err));
    }

    // Check assigned students when page loads
    refreshAssignedNotes();
});
</script>
@endsection
