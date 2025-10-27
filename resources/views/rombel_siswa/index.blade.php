@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto mt-8">
    <h2 class="text-xl font-bold mb-4">Data Rombel Siswa</h2>
    <a href="{{ route('rombel_siswa.create') }}" class="bg-green-400 hover:bg-green-500 text-white font-semibold px-4 py-2 rounded-lg shadow transition duration-200 mb-4 inline-block">
        Tambah Rombel
    </a>

    <div class="flex flex-wrap gap-4 mb-4">
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

        <div class="relative">
            <select id="tahun_ajaran_id"
                class="border-2 border-gray-300 rounded-lg pl-4 pr-4 py-2 w-56
                    focus:outline-none focus:ring-2 focus:ring-orange-400 focus:border-orange-400
                    transition duration-200 shadow-sm bg-white text-gray-700 font-semibold appearance-none">
                <option value="">-- Pilih Tahun Ajaran --</option>
                @foreach(\App\Models\TahunAjaran::all() as $ta)
                    <option value="{{ $ta->id }}">{{ $ta->nama }}</option>
                @endforeach
            </select>
        </div>

        <button id="btn-ganti-kelas" class="bg-blue-500 hover:bg-blue-600 text-white font-semibold px-4 py-2 rounded-lg shadow transition duration-200" disabled>
            Ganti Kelas Massal
        </button>
        <!-- Tombol Export PDF -->
        <button id="btn-export-pdf" class="bg-pink-500 hover:bg-pink-600 text-white font-semibold px-4 py-2 rounded-lg shadow transition duration-200" disabled>
            Export PDF
        </button>
    </div>


    <!-- Modal Ganti Kelas Massal -->
    <div id="modal-ganti-kelas" style="display:none;" class="fixed inset-0 bg-black bg-opacity-30 flex items-center justify-center z-50">
        <div class="bg-white rounded-lg shadow-lg p-6 w-96">
            <h3 class="text-lg font-bold mb-4">Ganti Kelas Siswa Terpilih</h3>
            <form id="form-ganti-kelas">
                <input type="hidden" id="modal_tahun_ajaran_id" name="tahun_ajaran_id" value="">
                <div class="mb-4">
                    <label for="kelas_baru_id" class="block font-semibold mb-2">Pilih Kelas Baru</label>
                    <select id="kelas_baru_id" name="kelas_baru_id" required class="w-full border rounded px-3 py-2">
                        <option value="">-- Pilih Kelas --</option>
                        @foreach(\App\Models\Kelas::all() as $kelas)
                            <option value="{{ $kelas->id }}">{{ $kelas->nama }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="flex justify-end gap-2">
                    <button type="button" id="btn-batal-modal" class="px-4 py-2 rounded bg-gray-300 hover:bg-gray-400">Batal</button>
                    <button type="submit" class="px-4 py-2 rounded bg-blue-500 text-white hover:bg-blue-600">Simpan</button>
                </div>
            </form>
        </div>
    </div>

    <div id="rombel-table-container" style="display:none;">
        <table class="min-w-full border-2 border-orange-400 rounded-lg overflow-hidden shadow border-collapse">
            <thead>
                <tr class="bg-orange-500 text-white border-b-2 border-orange-400 rounded-none">
                    <th class="px-4 py-2 text-center font-semibold">
                        <input type="checkbox" id="check-all">
                    </th>
                    <th class="px-4 py-2 text-center font-semibold">No. Absen</th>
                    <th class="px-4 py-2 text-left font-semibold">Nama Siswa</th>
                    <th class="px-4 py-2 text-center font-semibold">NIS</th>
                    <th class="px-4 py-2 text-center font-semibold">Kelas</th>
                    <th class="px-4 py-2 text-center font-semibold">Tahun Ajaran</th>
                    <th class="px-4 py-2 text-center font-semibold">Aksi</th>
                </tr>
            </thead>
            <tbody id="rombel-tbody">
                <!-- Data akan diisi oleh JS -->
            </tbody>
        </table>
    </div>
</div>
<script>
    function fetchRombel() {
    const kelas_id = document.getElementById('kelas_id').value;
    const tahun_ajaran_id = document.getElementById('tahun_ajaran_id').value;
    const tableContainer = document.getElementById('rombel-table-container');
    if (!kelas_id) {
        tableContainer.style.display = 'none';
        document.getElementById('rombel-tbody').innerHTML = '';
        return;
    }
    let url = `/api/rombel-siswa?kelas_id=${encodeURIComponent(kelas_id)}`;
    if (tahun_ajaran_id) url += `&tahun_ajaran_id=${encodeURIComponent(tahun_ajaran_id)}`;
    fetch(url)
        .then(res => res.json())
        .then(data => {
            let tbody = '';
            let csrf = '{{ csrf_token() }}';
            data.forEach((row, i) => {
                tbody += `<tr class="${i % 2 == 0 ? 'bg-white' : 'bg-gray-100'} border-b border-orange-200 hover:bg-orange-50">
                    <td class="px-4 py-2 text-center">
                        <input type="checkbox" class="check-siswa" value="${row.id}">
                    </td>
                    <td class="px-4 py-2 text-center">${row.nomor_absen ?? '-'}</td>
                    <td class="px-4 py-2 text-left">${row.siswa_nama ?? '-'}</td>
                    <td class="px-4 py-2 text-center">${row.siswa_nis ?? '-'}</td>
                    <td class="px-4 py-2 text-center">${row.kelas_nama ?? '-'}</td>
                    <td class="px-4 py-2 text-center">${row.tahun_ajaran_nama ?? '-'}</td>
                    <td class="px-4 py-2 text-center">
                        <a href="/rombel_siswa/${row.id}/edit" class="text-blue-600">Ganti Kelas</a>
                    </td>
                </tr>`;
            });
            document.getElementById('rombel-tbody').innerHTML = tbody;
            tableContainer.style.display = '';
        });
}

// Select All Checkbox
document.addEventListener('change', function(e) {
    if (e.target.id === 'check-all') {
        document.querySelectorAll('.check-siswa').forEach(cb => cb.checked = e.target.checked);
    }
});

// Modal logic
document.getElementById('btn-ganti-kelas').addEventListener('click', function() {
    const tahun = document.getElementById('tahun_ajaran_id').value;
    if (!tahun) {
        alert('Silakan pilih Tahun Ajaran terlebih dahulu untuk mengganti kelas massal.');
        return;
    }
    // set hidden input in modal
    document.getElementById('modal_tahun_ajaran_id').value = tahun;
    document.getElementById('modal-ganti-kelas').style.display = '';
});
document.getElementById('btn-batal-modal').addEventListener('click', function() {
    document.getElementById('modal-ganti-kelas').style.display = 'none';
});

// Submit mass change
document.getElementById('form-ganti-kelas').addEventListener('submit', function(e) {
    e.preventDefault();
    const ids = Array.from(document.querySelectorAll('.check-siswa:checked')).map(cb => cb.value);
    const kelas_baru_id = document.getElementById('kelas_baru_id').value;
    const tahun_ajaran_id = document.getElementById('modal_tahun_ajaran_id').value || document.getElementById('tahun_ajaran_id').value;
    if (ids.length === 0) {
        alert('Pilih siswa terlebih dahulu!');
        return;
    }
    if (!kelas_baru_id) {
        alert('Pilih kelas baru!');
        return;
    }
    if (!tahun_ajaran_id) {
        alert('Tahun Ajaran tidak terdeteksi. Pilih tahun terlebih dahulu.');
        return;
    }
    fetch('/rombel_siswa/ganti-kelas-massal', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Content-Type': 'application/json',
            'Accept': 'application/json'
        },
        body: JSON.stringify({ ids, kelas_baru_id, tahun_ajaran_id })
    })
    .then(res => res.json())
    .then(json => {
        if (json.success) {
            document.getElementById('modal-ganti-kelas').style.display = 'none';
            fetchRombel();
        } else {
            alert('Gagal memindahkan kelas! ' + (json.message || ''));
        }
    }).catch(err => {
        console.error('Ganti kelas massal gagal', err);
        alert('Gagal memindahkan kelas (network/servicer error)');
    });
});

document.getElementById('kelas_id').addEventListener('change', fetchRombel);
</script>
<script>
        // Enable/disable tombol export sesuai kelas (and tahun if selected)
            function updateExportButtonState() {
                const kelas = document.getElementById('kelas_id').value;
                const tahun = document.getElementById('tahun_ajaran_id').value;
                    // Require BOTH kelas and tahun selected to enable export and Ganti Kelas
                    const enabled = (kelas && tahun);
                    document.getElementById('btn-export-pdf').disabled = !enabled;
                    document.getElementById('btn-ganti-kelas').disabled = !enabled;
            }

            document.getElementById('kelas_id').addEventListener('change', function() {
                fetchRombel();
                updateExportButtonState();
            });

            document.getElementById('tahun_ajaran_id').addEventListener('change', function() {
                fetchRombel();
                updateExportButtonState();
            });

            // initialize export button state on load
            updateExportButtonState();

    // Export PDF
    document.getElementById('btn-export-pdf').addEventListener('click', function() {
        const kelasId = document.getElementById('kelas_id').value;
        const tahunId = document.getElementById('tahun_ajaran_id').value;
        if (!kelasId) {
            alert('Pilih kelas terlebih dahulu');
            return;
        }
        let href = `/rombel_siswa/export/pdf?kelas_id=${kelasId}`;
        if (tahunId) href += `&tahun_ajaran_id=${tahunId}`;
        window.location.href = href;
    });
    </script>
@endsection
