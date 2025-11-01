@extends('layouts.app')

@section('content')
<div class="max-w-lg mx-auto mt-8">
    <h2 class="text-xl font-bold mb-4">Edit Rombel Siswa</h2>
    <form method="POST" action="{{ route('rombel_siswa.update', $rombel) }}" class="space-y-4">
        @csrf
        @method('PUT')
        <div>
            <label class="block">Siswa</label>
            <input type="hidden" name="siswa_id" value="{{ $rombel->siswa_id }}">
            <div class="border rounded px-2 py-1 w-full bg-gray-100 text-gray-700">
                {{ $rombel->siswa->nama ?? '-' }} ({{ $rombel->siswa->nis ?? '-' }})
            </div>
        </div>
        <div>
            <label class="block">Pindahkan ke Kelas</label>
            <select name="kelas_id" class="border rounded px-2 py-1 w-full" required>
                <option value="">- Pilih Kelas -</option>
                @foreach($kelas as $row)
                <option value="{{ $row->id }}" @if($rombel->kelas_id==$row->id) selected @endif>{{ $row->nama }}</option>
                @endforeach
            </select>
        </div>
        <input type="hidden" name="tahun_ajaran_id" value="{{ session('tahun_ajaran_id') }}">
        <div class="bg-orange-100 border-l-4 border-orange-500 text-orange-700 p-4 mb-4">
            @php $ta = \App\Models\TahunAjaran::find(session('tahun_ajaran_id')); @endphp
            <p>Tahun Ajaran: {{ $ta ? ($ta->nama . ' - ' . $ta->semester) : 'Belum dipilih' }}</p>
            <p class="text-sm">Gunakan selector tahun ajaran di navigation bar untuk mengubah tahun ajaran</p>
        </div>
        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">Update</button>
    </form>
</div>
@endsection
