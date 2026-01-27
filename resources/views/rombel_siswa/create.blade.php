@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto">
    <nav class="flex mb-4" aria-label="Breadcrumb">
        <ol class="inline-flex items-center space-x-1 md:space-x-3">
            <li class="inline-flex items-center">
                <a href="{{ route('rombel_siswa.index') }}" class="text-sm font-medium text-gray-500 hover:text-orange-600">Rombel Siswa</a>
            </li>
            <li>
                <div class="flex items-center">
                    <svg class="w-3 h-3 text-gray-400 mx-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10"><path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 9 4-4-4-4"/></svg>
                    <span class="ml-1 text-sm font-medium text-gray-700 md:ml-2">Tambah Data</span>
                </div>
            </li>
        </ol>
    </nav>

    <div class="mb-6 bg-orange-50 border-l-4 border-orange-500 p-4 rounded-r shadow-sm flex items-start">
        <div class="flex-shrink-0">
            <svg class="h-5 w-5 text-orange-500" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
            </svg>
        </div>
        <div class="ml-3">
            <h3 class="text-sm font-medium text-orange-800">Tahun Ajaran Aktif</h3>
            <div class="mt-1 text-sm text-orange-700">
                {{ $tahunAjaran ? ($tahunAjaran->nama . ' - ' . $tahunAjaran->semester) : 'Belum dipilih' }}
            </div>
        </div>
    </div>

    @if($siswa->isEmpty())
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-8 text-center">
            <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-green-100 mb-4">
                <svg class="h-6 w-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
            </div>
            <h3 class="text-lg font-medium text-gray-900">Semua Siswa Sudah Masuk Kelas</h3>
            <p class="mt-2 text-sm text-gray-500">Tidak ada data siswa yang belum memiliki kelas di tahun ajaran ini.</p>
            <div class="mt-6">
                <a href="{{ route('rombel_siswa.index') }}" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-orange-600 hover:bg-orange-700">
                    Kembali ke Daftar
                </a>
            </div>
        </div>
    @else
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 bg-gray-50">
                <h2 class="text-lg font-bold text-gray-800">Form Plotting Kelas</h2>
            </div>
            
            <div class="p-6">
                <form method="POST" action="{{ route('rombel_siswa.mass_store') }}">
                    @csrf
                    <input type="hidden" name="tahun_ajaran_id" value="{{ $tahunAjaran->id }}">

                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Masukkan ke Kelas</label>
                        <select name="kelas_id" class="w-full rounded-lg border-gray-300 focus:border-orange-500 focus:ring-orange-500 shadow-sm transition" required>
                            <option value="">- Pilih Kelas Tujuan -</option>
                            @foreach($kelas as $row)
                                <option value="{{ $row->id }}">{{ $row->nama }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-4">
                        <div class="flex justify-between items-center mb-2">
                            <label class="block text-sm font-medium text-gray-700">Pilih Siswa ({{ $siswa->count() }} tersedia)</label>
                            <span id="selectedCount" class="text-xs font-bold text-orange-600 bg-orange-100 px-2 py-1 rounded-full">0 dipilih</span>
                        </div>
                        
                        <div class="relative mb-3">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                            </div>
                            <input type="text" id="searchInput" placeholder="Cari nama atau NIS..." class="pl-9 w-full rounded-lg border-gray-300 text-sm focus:ring-orange-500 focus:border-orange-500">
                        </div>

                        <div class="border border-gray-200 rounded-lg overflow-hidden max-h-96 overflow-y-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50 sticky top-0 z-10">
                                    <tr>
                                        <th class="px-4 py-3 text-center w-12">
                                            <input type="checkbox" id="checkAll" onclick="toggleAll(this)" class="rounded border-gray-300 text-orange-600 focus:ring-orange-500">
                                        </th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">NIS</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Siswa</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($siswa as $row)
                                    <tr class="hover:bg-orange-50 transition">
                                        <td class="px-4 py-2 text-center">
                                            <input type="checkbox" name="siswa_id[]" value="{{ $row->id }}" class="siswa-check rounded border-gray-300 text-orange-600 focus:ring-orange-500">
                                        </td>
                                        <td class="px-4 py-2 text-sm text-gray-500 font-mono">{{ $row->nis }}</td>
                                        <td class="px-4 py-2 text-sm text-gray-900 font-medium">
                                            {{ $row->nama }} 
                                            <span class="assigned-note text-xs text-red-500 ml-2 italic"></span>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="flex items-center justify-end gap-3 pt-4 border-t border-gray-100">
                        <a href="{{ route('rombel_siswa.index') }}" class="px-5 py-2.5 rounded-lg border border-gray-300 text-gray-700 text-sm font-medium hover:bg-gray-50 transition">
                            Batal
                        </a>
                        <button type="submit" class="px-5 py-2.5 rounded-lg bg-orange-600 text-white text-sm font-medium hover:bg-orange-700 shadow-md hover:shadow-lg transition duration-200">
                            Simpan Data
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif
</div>

<script>
function updateSelectedCount() {
    const checkedCount = document.querySelectorAll('.siswa-check:checked').length;
    document.getElementById('selectedCount').textContent = `${checkedCount} siswa dipilih`;
}

function toggleAll(source) {
    document.querySelectorAll('.siswa-check:not(:disabled)').forEach(cb => {
        // Only check visible rows (filtered rows)
        if(cb.closest('tr').style.display !== 'none') {
            cb.checked = source.checked;
        }
    });
    updateSelectedCount();
}

function filterSiswa(query) {
    query = query.toLowerCase().trim();
    const rows = document.querySelectorAll('tbody tr');
    let visibleCount = 0;
    
    rows.forEach(row => {
        const nis = row.querySelector('td:nth-child(2)').textContent.toLowerCase();
        const nama = row.querySelector('td:nth-child(3)').textContent.toLowerCase().split('Sudah:')[0].trim();
        
        if (nis.includes(query) || nama.includes(query)) {
            row.style.display = '';
            visibleCount++;
        } else {
            row.style.display = 'none';
        }
    });

    // Update CheckAll status
    const checkAllBox = document.getElementById('checkAll');
    if (visibleCount === 0) {
        checkAllBox.disabled = true;
        checkAllBox.checked = false;
    } else {
        checkAllBox.disabled = false;
    }
}

document.addEventListener('DOMContentLoaded', function() {
    // Checkbox listeners
    document.querySelectorAll('.siswa-check').forEach(checkbox => {
        checkbox.addEventListener('change', updateSelectedCount);
    });
    
    // Search listener
    document.getElementById('searchInput').addEventListener('input', function(e) {
        filterSiswa(e.target.value);
    });

    // Fetch existing assignments check
    const tahunId = '{{ session('tahun_ajaran_id') }}';
    if(tahunId) {
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
                        if (noteEl) noteEl.textContent = `(Sudah di: ${assignedBySiswa[sid].kelas_nama})`;
                        // Add visual indication row is disabled
                        cb.closest('tr').classList.add('bg-gray-100', 'opacity-75');
                    }
                });
            })
            .catch(err => console.error('Failed checking assignments', err));
    }
});
</script>
@endsection