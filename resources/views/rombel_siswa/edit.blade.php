@extends('layouts.app')

@section('content')
<div class="max-w-xl mx-auto">
    <nav class="flex mb-4" aria-label="Breadcrumb">
        <ol class="inline-flex items-center space-x-1 md:space-x-3">
            <li class="inline-flex items-center">
                <a href="{{ route('rombel_siswa.index') }}" class="text-sm font-medium text-gray-500 hover:text-orange-600">Rombel Siswa</a>
            </li>
            <li>
                <div class="flex items-center">
                    <svg class="w-3 h-3 text-gray-400 mx-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 9 4-4-4-4"/></svg>
                    <span class="ml-1 text-sm font-medium text-gray-700 md:ml-2">Pindah Kelas</span>
                </div>
            </li>
        </ol>
    </nav>

    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 bg-orange-50">
            <h2 class="text-lg font-bold text-gray-800">Edit Rombel Siswa</h2>
            <p class="text-xs text-gray-500">Pindahkan siswa ke kelas lain dalam tahun ajaran yang sama.</p>
        </div>

        <div class="p-6">
            <form method="POST" action="{{ route('rombel_siswa.update', $rombel) }}">
                @csrf
                @method('PUT')
                <input type="hidden" name="siswa_id" value="{{ $rombel->siswa_id }}">
                <input type="hidden" name="tahun_ajaran_id" value="{{ session('tahun_ajaran_id') }}">

                <div class="mb-6 p-4 bg-gray-50 rounded-lg border border-gray-200 flex items-center gap-4">
                    <div class="bg-blue-100 p-2 rounded-full text-blue-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500 uppercase font-bold">Identitas Siswa</p>
                        <p class="text-sm font-bold text-gray-900">{{ $rombel->siswa->nama ?? '-' }}</p>
                        <p class="text-xs text-gray-600">NIS: {{ $rombel->siswa->nis ?? '-' }}</p>
                    </div>
                </div>

                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Pindah ke Kelas</label>
                    <select name="kelas_id" class="w-full rounded-lg border-gray-300 focus:border-orange-500 focus:ring-orange-500 shadow-sm transition" required>
                        <option value="">- Pilih Kelas Baru -</option>
                        @foreach($kelas as $row)
                            <option value="{{ $row->id }}" @if($rombel->kelas_id==$row->id) selected @endif>{{ $row->nama }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-6 bg-orange-50 p-3 rounded text-xs text-orange-700 border border-orange-100">
                    <strong>Note:</strong> Perubahan ini hanya berlaku untuk Tahun Ajaran: 
                    {{ \App\Models\TahunAjaran::find(session('tahun_ajaran_id'))->nama ?? 'Aktif' }}.
                </div>

                <div class="flex items-center justify-end gap-3 pt-4 border-t border-gray-100">
                    <a href="{{ route('rombel_siswa.index') }}" class="px-5 py-2.5 rounded-lg border border-gray-300 text-gray-700 text-sm font-medium hover:bg-gray-50 transition">
                        Batal
                    </a>
                    <button type="submit" class="px-5 py-2.5 rounded-lg bg-orange-600 text-white text-sm font-medium hover:bg-orange-700 shadow-md hover:shadow-lg transition duration-200">
                        Update Kelas
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection