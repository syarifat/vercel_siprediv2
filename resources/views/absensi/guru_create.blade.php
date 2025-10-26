@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto mt-8">
	<h2 class="text-xl font-bold mb-4">Tambah Absensi Guru</h2>

	<form action="{{ route('absensi_guru.store') }}" method="POST" class="bg-white p-6 rounded shadow">
		@csrf
		<div class="mb-4">
			<label class="block">Guru</label>
			<select name="guru_id" class="w-full border p-2">
				@foreach($gurus as $g)
					<option value="{{ $g->id }}">{{ $g->nama }}</option>
				@endforeach
			</select>
		</div>
		<div class="mb-4">
			<label class="block">Tanggal</label>
			<input type="date" name="tanggal" class="w-full border p-2" value="{{ date('Y-m-d') }}">
		</div>
		<div class="mb-4">
			<label class="block">Jam Masuk</label>
			<input type="time" name="jam_masuk" class="w-full border p-2">
		</div>
		<div class="mb-4">
			<label class="block">Jam Pulang</label>
			<input type="time" name="jam_pulang" class="w-full border p-2">
		</div>
		<div class="mb-4">
			<label class="block">Status</label>
			<select name="status" class="w-full border p-2">
				<option value="hadir">Hadir</option>
				<option value="izin">Izin</option>
				<option value="sakit">Sakit</option>
				<option value="alpha">Alpha</option>
			</select>
		</div>
		<div class="mb-4">
			<label class="block">Keterangan</label>
			<input type="text" name="keterangan" class="w-full border p-2">
		</div>
		<div class="flex justify-end">
			<button class="px-4 py-2 bg-blue-600 text-white rounded">Simpan</button>
		</div>
	</form>
</div>
@endsection
