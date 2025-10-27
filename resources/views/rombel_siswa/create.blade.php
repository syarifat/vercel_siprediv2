@extends('layouts.app')

@section('content')
<div class="max-w-lg mx-auto mt-8">
    <h2 class="text-xl font-bold mb-4">Tambah Rombel Siswa</h2>
    <form method="POST" action="{{ route('rombel_siswa.mass_store') }}" class="space-y-4">
        @csrf
        <div>
            <label class="block mb-2">Pilih Siswa</label>
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
        <div>
            <label class="block mb-2">Tahun Ajaran</label>
            <select name="tahun_ajaran_id" class="border rounded px-2 py-1 w-full" required>
                <option value="">- Pilih Tahun Ajaran -</option>
                @foreach(\App\Models\TahunAjaran::all() as $ta)
                    <option value="{{ $ta->id }}" {{ old('tahun_ajaran_id') == $ta->id ? 'selected' : '' }}>{{ $ta->nama }}</option>
                @endforeach
            </select>
        </div>
        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">Masukkan ke Kelas</button>
    </form>
</div>
<script>
function toggleAll(source) {
    document.querySelectorAll('.siswa-check').forEach(cb => cb.checked = source.checked);
}
</script>
<script>
// When tahun_ajaran is selected, fetch existing rombel for that year and mark students already assigned
document.addEventListener('DOMContentLoaded', function() {
    const tahunSelect = document.querySelector('select[name="tahun_ajaran_id"]');
    if (!tahunSelect) return;

    function refreshAssignedNotes() {
        const tahunId = tahunSelect.value;
        // clear notes and enable checkboxes by default
        document.querySelectorAll('.siswa-check').forEach(cb => { cb.disabled = false; cb.checked = false; });
        document.querySelectorAll('.assigned-note').forEach(span => span.textContent = '');
        if (!tahunId) return;
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

    tahunSelect.addEventListener('change', refreshAssignedNotes);
    // initialize if a tahun is preselected
    refreshAssignedNotes();
});
</script>
@endsection
