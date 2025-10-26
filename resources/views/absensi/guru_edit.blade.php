@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto mt-8">
	<h2 class="text-xl font-bold mb-4">Edit Absensi Guru</h2>

	<form action="{{ route('absensi_guru.update', $absensi) }}" method="POST" class="bg-white p-6 rounded shadow">
		@csrf
		@method('PUT')
		{{-- Hidden fields: keep values but hide from UI --}}
		<input type="hidden" name="guru_id" value="{{ $absensi->guru_id }}">
		<input type="hidden" name="tanggal" value="{{ $absensi->tanggal }}">
		<input type="hidden" name="jam_masuk" value="{{ $absensi->jam_masuk }}">
		<input type="hidden" name="jam_pulang" value="{{ $absensi->jam_pulang }}">

		<div class="mb-4">
			<label class="block">Status</label>
			<select name="status" class="w-full border p-2">
				<option value="hadir" {{ $absensi->status=='hadir' ? 'selected' : '' }}>Hadir</option>
				<option value="izin" {{ $absensi->status=='izin' ? 'selected' : '' }}>Izin</option>
				<option value="sakit" {{ $absensi->status=='sakit' ? 'selected' : '' }}>Sakit</option>
				<option value="alpha" {{ $absensi->status=='alpha' ? 'selected' : '' }}>Alpha</option>
			</select>
		</div>
		<div class="mb-4">
			<label class="block">Keterangan</label>
			<input type="text" name="keterangan" class="w-full border p-2" value="{{ $absensi->keterangan }}">
		</div>
		<div class="flex justify-end">
			<button class="px-4 py-2 bg-blue-600 text-white rounded">Update</button>
		</div>
	</form>
</div>
@endsection

