@extends('layouts.app')

@section('content')
<div class="max-w-6xl mx-auto space-y-6">
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">Manajemen Rombel Siswa</h2>
            <p class="text-sm text-gray-500">Atur penempatan kelas siswa per tahun ajaran.</p>
        </div>
        
        @php 
            $tahunAjaran = \App\Models\TahunAjaran::find(session('tahun_ajaran_id'));
            $isGuru = auth()->user()->role === 'guru';
        @endphp

        @if(!$isGuru)
        <a href="{{ route('rombel_siswa.create') }}" class="inline-flex items-center justify-center px-4 py-2 bg-orange-600 hover:bg-orange-700 text-white text-sm font-medium rounded-lg shadow-sm transition-colors duration-200">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Tambah Rombel
        </a>
        @endif
    </div>

    <div class="bg-blue-50 border-l-4 border-blue-500 p-4 rounded-r shadow-sm flex justify-between items-center">
        <div>
            <h4 class="text-sm font-bold text-blue-800">Tahun Ajaran Terpilih:</h4>
            <p class="text-lg font-semibold text-blue-900">
                {{ $tahunAjaran ? ($tahunAjaran->nama . ' - ' . $tahunAjaran->semester) : 'Belum dipilih' }}
            </p>
        </div>
        @if(!$tahunAjaran)
            <span class="text-xs text-red-600 font-bold bg-red-100 px-2 py-1 rounded">⚠️ Pilih Tahun Ajaran di Navbar</span>
        @endif
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="p-4 border-b border-gray-100 bg-gray-50 flex flex-col md:flex-row gap-4 items-center justify-between">
            <div class="w-full md:w-auto">
                <select id="kelas_id" class="w-full md:w-64 border-gray-300 rounded-lg text-sm focus:ring-orange-500 focus:border-orange-500 shadow-sm">
                    <option value="">-- Pilih Kelas untuk Menampilkan --</option>
                    @foreach(\App\Models\Kelas::all() as $kelas)
                        <option value="{{ $kelas->id }}">{{ $kelas->nama }}</option>
                    @endforeach
                </select>
            </div>

            <div class="flex gap-2 w-full md:w-auto justify-end">
                @if(!$isGuru)
                <button id="btn-ganti-kelas" class="px-4 py-2 bg-white border border-gray-300 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-50 transition shadow-sm disabled:opacity-50 disabled:cursor-not-allowed" disabled>
                    Ganti Kelas Massal
                </button>
                @endif
                <button id="btn-export-pdf" class="px-4 py-2 bg-pink-500 text-white text-sm font-medium rounded-lg hover:bg-pink-600 transition shadow-sm disabled:opacity-50 disabled:cursor-not-allowed flex items-center gap-2" disabled>
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    Export PDF
                </button>
            </div>
        </div>

        <div id="rombel-table-container" class="overflow-x-auto" style="display:none;">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-orange-500 text-white">
                    <tr>
                        @if(!$isGuru)
                        <th class="px-4 py-3 text-center w-12">
                            <input type="checkbox" id="check-all" class="rounded border-white text-orange-600 focus:ring-0">
                        </th>
                        @endif
                        <th class="px-4 py-3 text-center text-xs font-bold uppercase tracking-wider w-20">No. Absen</th>
                        <th class="px-4 py-3 text-left text-xs font-bold uppercase tracking-wider">Nama Siswa</th>
                        <th class="px-4 py-3 text-center text-xs font-bold uppercase tracking-wider">NIS</th>
                        <th class="px-4 py-3 text-center text-xs font-bold uppercase tracking-wider">Kelas</th>
                        <th class="px-4 py-3 text-center text-xs font-bold uppercase tracking-wider">Tahun Ajaran</th>
                        @if(!$isGuru)
                        <th class="px-4 py-3 text-center text-xs font-bold uppercase tracking-wider">Aksi</th>
                        @endif
                    </tr>
                </thead>
                <tbody id="rombel-tbody" class="bg-white divide-y divide-gray-200">
                    </tbody>
            </table>
        </div>
        
        <div id="empty-state" class="p-10 text-center text-gray-500">
            <svg class="w-12 h-12 mx-auto text-gray-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
            <p>Silakan pilih kelas terlebih dahulu untuk menampilkan data.</p>
        </div>
    </div>
</div>

<div id="modal-ganti-kelas" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 backdrop-blur-sm">
    <div class="bg-white rounded-xl shadow-lg p-6 w-96 transform transition-all scale-100">
        <h3 class="text-lg font-bold text-gray-800 mb-4">Pindah Kelas Massal</h3>
        <p class="text-sm text-gray-600 mb-4">Pindahkan siswa yang dicentang ke kelas baru:</p>
        
        <form id="form-ganti-kelas">
            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-1">Kelas Tujuan</label>
                <select id="kelas_baru_id" name="kelas_baru_id" required class="w-full border-gray-300 rounded-lg text-sm focus:ring-orange-500 focus:border-orange-500">
                    <option value="">-- Pilih Kelas --</option>
                    @foreach(\App\Models\Kelas::all() as $kelas)
                        <option value="{{ $kelas->id }}">{{ $kelas->nama }}</option>
                    @endforeach
                </select>
            </div>
            <div class="flex justify-end gap-2">
                <button type="button" id="btn-batal-modal" class="px-4 py-2 text-gray-600 bg-gray-100 hover:bg-gray-200 rounded-lg text-sm font-medium transition">Batal</button>
                <button type="submit" class="px-4 py-2 bg-orange-600 text-white rounded-lg text-sm font-medium hover:bg-orange-700 transition shadow">Simpan</button>
            </div>
        </form>
    </div>
</div>

@endsection

@section('scripts')
<script>
    const isGuru = @json($isGuru);

    function fetchRombel() {
        const kelas_id = document.getElementById('kelas_id').value;
        const tableContainer = document.getElementById('rombel-table-container');
        const emptyState = document.getElementById('empty-state');
        const btnExport = document.getElementById('btn-export-pdf');
        const btnMass = document.getElementById('btn-ganti-kelas');

        if (!kelas_id) {
            tableContainer.style.display = 'none';
            emptyState.style.display = 'block';
            if(btnExport) btnExport.disabled = true;
            if(btnMass) btnMass.disabled = true;
            return;
        }

        if(btnExport) btnExport.disabled = false;
        if(btnMass) btnMass.disabled = false;

        let url = `/api/rombel-siswa?kelas_id=${encodeURIComponent(kelas_id)}`;
        
        fetch(url)
            .then(res => res.json())
            .then(data => {
                let tbody = '';
                if(data.length === 0) {
                    tbody = `<tr><td colspan="${isGuru ? 5 : 6}" class="px-4 py-8 text-center text-gray-500">Belum ada siswa di kelas ini.</td></tr>`;
                } else {
                    data.forEach((row, i) => {
                        let taName = row.tahun_ajaran_nama ? `${row.tahun_ajaran_nama} - ${row.tahun_ajaran_semester}` : '-';
                        
                        tbody += `
                        <tr class="hover:bg-orange-50 transition border-b border-gray-100">
                            ${!isGuru ? `
                            <td class="px-4 py-3 text-center">
                                <input type="checkbox" class="check-siswa rounded border-gray-300 text-orange-600 focus:ring-orange-500" value="${row.id}">
                            </td>` : ''}
                            <td class="px-4 py-3 text-center font-mono text-sm text-gray-600">${row.nomor_absen ?? '-'}</td>
                            <td class="px-4 py-3 text-sm font-medium text-gray-900">${row.siswa_nama ?? '-'}</td>
                            <td class="px-4 py-3 text-center text-sm text-gray-500">${row.siswa_nis ?? '-'}</td>
                            <td class="px-4 py-3 text-center text-sm text-gray-800 bg-orange-50/50">${row.kelas_nama ?? '-'}</td>
                            <td class="px-4 py-3 text-center text-xs text-gray-500">${taName}</td>
                            ${!isGuru ? `
                            <td class="px-4 py-3 text-center">
                                <a href="/rombel_siswa/${row.id}/edit" class="text-blue-600 hover:text-blue-800 text-xs font-semibold bg-blue-50 px-2 py-1 rounded">Pindah</a>
                            </td>` : ''}
                        </tr>`;
                    });
                }
                
                document.getElementById('rombel-tbody').innerHTML = tbody;
                tableContainer.style.display = 'block';
                emptyState.style.display = 'none';
            })
            .catch(err => {
                console.error(err);
                alert('Gagal mengambil data rombel.');
            });
    }

    // Event Listeners
    document.getElementById('kelas_id').addEventListener('change', fetchRombel);

    // Check All logic
    const checkAllBox = document.getElementById('check-all');
    if(checkAllBox) {
        checkAllBox.addEventListener('change', function(e) {
            document.querySelectorAll('.check-siswa').forEach(cb => cb.checked = e.target.checked);
        });
    }

    // Modal Logic
    const modal = document.getElementById('modal-ganti-kelas');
    const btnMass = document.getElementById('btn-ganti-kelas');
    const btnCancel = document.getElementById('btn-batal-modal');

    if(btnMass) {
        btnMass.addEventListener('click', () => {
            const selected = document.querySelectorAll('.check-siswa:checked').length;
            if(selected === 0) {
                alert('Pilih minimal satu siswa untuk dipindahkan.');
                return;
            }
            modal.classList.remove('hidden');
        });
    }

    if(btnCancel) {
        btnCancel.addEventListener('click', () => {
            modal.classList.add('hidden');
        });
    }

    // Submit Massal
    document.getElementById('form-ganti-kelas').addEventListener('submit', function(e) {
        e.preventDefault();
        const ids = Array.from(document.querySelectorAll('.check-siswa:checked')).map(cb => cb.value);
        const kelas_baru_id = document.getElementById('kelas_baru_id').value;

        if(!kelas_baru_id) {
            alert('Pilih kelas tujuan!');
            return;
        }

        fetch('/rombel_siswa/ganti-kelas-massal', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            },
            body: JSON.stringify({ ids, kelas_baru_id })
        })
        .then(res => res.json())
        .then(json => {
            if(json.success) {
                modal.classList.add('hidden');
                fetchRombel();
                // Reset check all
                if(checkAllBox) checkAllBox.checked = false;
                alert('Berhasil memindahkan siswa.');
            } else {
                alert('Gagal: ' + json.message);
            }
        })
        .catch(err => {
            console.error(err);
            alert('Terjadi kesalahan sistem.');
        });
    });

    // Export PDF
    const btnExport = document.getElementById('btn-export-pdf');
    if(btnExport) {
        btnExport.addEventListener('click', function() {
            const kelasId = document.getElementById('kelas_id').value;
            if (!kelasId) return alert('Pilih kelas terlebih dahulu');
            window.location.href = `/rombel_siswa/export/pdf?kelas_id=${kelasId}`;
        });
    }
</script>
@endsection