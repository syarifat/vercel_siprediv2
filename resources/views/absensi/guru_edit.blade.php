@extends('layouts.app')

@section('content')
<div class="max-w-2xl mx-auto">
    <nav class="flex mb-4" aria-label="Breadcrumb">
        <ol class="inline-flex items-center space-x-1 md:space-x-3">
            <li class="inline-flex items-center">
                <a href="{{ route('absensi_guru.index') }}" class="text-sm font-medium text-gray-500 hover:text-orange-600">Absensi Guru</a>
            </li>
            <li>
                <div class="flex items-center">
                    <svg class="w-3 h-3 text-gray-400 mx-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 9 4-4-4-4"/></svg>
                    <span class="ml-1 text-sm font-medium text-gray-700 md:ml-2">Edit Data</span>
                </div>
            </li>
        </ol>
    </nav>

    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 bg-orange-50">
            <h2 class="text-lg font-bold text-gray-800">Edit Absensi Guru</h2>
            <p class="text-xs text-gray-500">Ubah data kehadiran {{ $absensi->guru->nama ?? 'Guru' }}.</p>
        </div>

        <div class="p-6">
            <form action="{{ route('absensi_guru.update', $absensi) }}" method="POST">
                @csrf
                @method('PUT')
                
                {{-- Hidden Fields --}}
                <input type="hidden" name="guru_id" value="{{ $absensi->guru_id }}">
                <input type="hidden" name="tanggal" value="{{ $absensi->tanggal }}">
                <input type="hidden" name="tahun_ajaran_id" value="{{ $absensi->tahun_ajaran_id }}">

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Jam Masuk</label>
                        <input type="time" name="jam_masuk" value="{{ $absensi->jam_masuk }}" class="w-full rounded-lg border-gray-300 focus:border-orange-500 focus:ring-orange-500 shadow-sm transition">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Jam Pulang</label>
                        <input type="time" name="jam_pulang" value="{{ $absensi->jam_pulang }}" class="w-full rounded-lg border-gray-300 focus:border-orange-500 focus:ring-orange-500 shadow-sm transition">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Status Kehadiran</label>
                        <select name="status" class="w-full rounded-lg border-gray-300 focus:border-orange-500 focus:ring-orange-500 shadow-sm transition" required>
                            <option value="hadir" {{ $absensi->status=='hadir' ? 'selected' : '' }}>Hadir</option>
                            <option value="izin" {{ $absensi->status=='izin' ? 'selected' : '' }}>Izin</option>
                            <option value="sakit" {{ $absensi->status=='sakit' ? 'selected' : '' }}>Sakit</option>
                            <option value="alpha" {{ $absensi->status=='alpha' ? 'selected' : '' }}>Alpha</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Keterangan</label>
                        <input type="text" name="keterangan" value="{{ $absensi->keterangan }}" class="w-full rounded-lg border-gray-300 focus:border-orange-500 focus:ring-orange-500 shadow-sm transition">
                    </div>
                </div>

                <div class="flex items-center justify-end gap-3 pt-4 border-t border-gray-100">
                    <a href="{{ route('absensi_guru.index') }}" class="px-5 py-2.5 rounded-lg border border-gray-300 text-gray-700 text-sm font-medium hover:bg-gray-50 transition">Batal</a>
                    <button type="submit" class="px-5 py-2.5 rounded-lg bg-orange-600 text-white text-sm font-medium hover:bg-orange-700 shadow-md hover:shadow-lg transition duration-200">Update Data</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection