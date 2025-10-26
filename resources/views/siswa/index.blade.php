@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto mt-8">
    <h2 class="text-xl font-bold mb-4">Daftar Siswa</h2>
    <a href="{{ route('siswa.create') }}" class="bg-green-400 hover:bg-green-500 text-white font-semibold px-4 py-2 rounded-lg shadow mb-4 inline-block transition duration-200">Tambah Siswa</a>
    
    <!-- Search Box -->
    <div class="relative mb-4">
        <input type="text" id="search" placeholder="Cari nama atau NIS"
            class="border-2 border-gray-300 rounded-lg pl-10 pr-4 py-2 w-64
                   focus:outline-none focus:ring-2 focus:ring-orange-400 focus:border-orange-400
                   transition duration-200 shadow-sm"
        >
        <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 pointer-events-none">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35M11 19a8 8 0 100-16 8 8 0 000 16z" />
            </svg>
        </span>
    </div>
    
    <table class="min-w-full border-2 border-orange-400 rounded-lg overflow-hidden shadow border-collapse">
        <thead>
            <tr class="bg-orange-500 text-white border-b-2 border-orange-400 rounded-none">
                <th class="px-4 py-2 text-center font-semibold">No</th>
                <th class="px-4 py-2 text-center font-semibold">NIS</th>
                <th class="px-4 py-2 text-left font-semibold">Nama</th>
                <th class="px-4 py-2 text-center font-semibold">Jenis Kelamin</th>
                <th class="px-4 py-2 text-center font-semibold">No HP Ortu</th>
                <th class="px-4 py-2 text-center font-semibold">Status</th>
                <th class="px-4 py-2 text-center font-semibold">Aksi</th>
            </tr>
        </thead>
        <tbody id="siswa-tbody">
            @foreach($siswa as $i => $row)
            <tr class="{{ $i % 2 == 0 ? 'bg-white' : 'bg-gray-100' }} border-b border-orange-200 hover:bg-orange-50">
                <td class="px-4 py-2 text-center">
                    {{ (isset($siswa) && $siswa->firstItem()) ? $siswa->firstItem() + $i : $loop->iteration }}
                </td>
                <td class="px-4 py-2 text-center">{{ $row->nis }}</td>
                <td class="px-4 py-2 text-left">{{ $row->nama }}</td>
                <td class="px-4 py-2 text-center">
                    {{ $row->jenis_kelamin == 'L' ? 'Laki-laki' : ($row->jenis_kelamin == 'P' ? 'Perempuan' : '-') }}
                </td>
                <td class="px-4 py-2 text-center">{{ $row->no_hp_ortu }}</td>
                <td class="px-4 py-2 text-center">{{ $row->status ? ucfirst($row->status) : '-' }}</td>
                <td class="px-4 py-2 text-center">
                    <a href="{{ route('siswa.edit', $row) }}" class="text-blue-600">Edit</a>
                    <form action="{{ route('siswa.destroy', $row) }}" method="POST" class="inline">
                        @csrf @method('DELETE')
                        <button type="submit" class="text-pink-600 ml-2" onclick="return confirm('Hapus siswa ini?')">Hapus</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    <div class="mt-4" id="pagination">
        {{ $siswa->links() }}
    </div>
</div>
@endsection

@section('scripts')
<script>
function fetchSiswa() {
    const search = document.getElementById('search').value;
    fetch(`/api/siswa?search=${encodeURIComponent(search)}`)
        .then(res => res.json())
        .then(data => {
            let tbody = '';
            data.forEach((row, i) => {
                const nomor = i + 1;
                const jenis = row.jenis_kelamin === 'L' ? 'Laki-laki' : (row.jenis_kelamin === 'P' ? 'Perempuan' : '-');
                const status = row.status ? (row.status.charAt(0).toUpperCase() + row.status.slice(1)) : '-';
                tbody += `<tr class="${i % 2 == 0 ? 'bg-white' : 'bg-gray-100'} border-b border-orange-200 hover:bg-orange-50">
                    <td class="px-4 py-2 text-center">${nomor}</td>
                    <td class="px-4 py-2 text-center">${row.nis ?? '-'}</td>
                    <td class="px-4 py-2 text-left">${row.nama ?? '-'}</td>
                    <td class="px-4 py-2 text-center">${jenis}</td>
                    <td class="px-4 py-2 text-center">${status}</td>
                    <td class="px-4 py-2 text-center">${row.no_hp_ortu ?? '-'}</td>
                    <td class="px-4 py-2 text-center">
                        <a href="/siswa/${row.id}/edit" class="text-blue-600">Edit</a>
                        <form action="/siswa/${row.id}" method="POST" class="inline" onsubmit="return confirm('Hapus siswa ini?')">
                            <input type="hidden" name="_method" value="DELETE">
                            <button type="submit" class="text-pink-600 ml-2">Hapus</button>
                        </form>
                    </td>
                </tr>`;
            });
            document.getElementById('siswa-tbody').innerHTML = tbody;
            document.getElementById('pagination').innerHTML = ''; // Hilangkan pagination saat search
        });
}
document.getElementById('search').addEventListener('input', fetchSiswa);
</script>
@endsection