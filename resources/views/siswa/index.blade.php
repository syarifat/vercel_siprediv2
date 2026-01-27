@extends('layouts.app')

@section('content')
@php
    $isGuru = auth()->user()->role === 'guru';
@endphp

<div class="space-y-6">
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">Daftar Siswa</h2>
            <p class="text-sm text-gray-500">Kelola data siswa, NIS, dan status akademik.</p>
        </div>
        
        @if(!$isGuru)
        <a href="{{ route('siswa.create') }}" class="inline-flex items-center justify-center px-4 py-2 bg-orange-600 hover:bg-orange-700 text-white text-sm font-medium rounded-lg shadow-sm transition-colors duration-200">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Tambah Siswa
        </a>
        @endif
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        
        <div class="p-4 border-b border-gray-100 bg-gray-50/50">
            <div class="relative max-w-md">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35M11 19a8 8 0 100-16 8 8 0 000 16z" />
                    </svg>
                </div>
                <input type="text" id="search" 
                    class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-lg leading-5 bg-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-orange-500 sm:text-sm transition duration-150 ease-in-out"
                    placeholder="Cari berdasarkan Nama atau NIS..."
                >
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-orange-50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-center text-xs font-bold text-orange-800 uppercase tracking-wider">No</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-orange-800 uppercase tracking-wider">Info Siswa</th>
                        <th scope="col" class="px-6 py-3 text-center text-xs font-bold text-orange-800 uppercase tracking-wider">L/P</th>
                        <th scope="col" class="px-6 py-3 text-center text-xs font-bold text-orange-800 uppercase tracking-wider">No HP Ortu</th>
                        <th scope="col" class="px-6 py-3 text-center text-xs font-bold text-orange-800 uppercase tracking-wider">Status</th>
                        @if(!$isGuru)
                        <th scope="col" class="px-6 py-3 text-center text-xs font-bold text-orange-800 uppercase tracking-wider">Aksi</th>
                        @endif
                    </tr>
                </thead>
                <tbody id="siswa-tbody" class="bg-white divide-y divide-gray-200">
                    @forelse($siswa as $i => $row)
                    <tr class="hover:bg-orange-50/50 transition duration-150">
                        <td class="px-6 py-4 whitespace-nowrap text-center text-sm text-gray-500">
                            {{ (isset($siswa) && $siswa->firstItem()) ? $siswa->firstItem() + $i : $loop->iteration }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex flex-col">
                                <span class="text-sm font-medium text-gray-900">{{ $row->nama }}</span>
                                <span class="text-xs text-gray-500">NIS: {{ $row->nis }}</span>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-center text-sm text-gray-500">
                            <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full {{ $row->jenis_kelamin == 'L' ? 'bg-blue-100 text-blue-800' : 'bg-pink-100 text-pink-800' }}">
                                {{ $row->jenis_kelamin }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-center text-sm text-gray-500">
                            {{ $row->no_hp_ortu ?? '-' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-center">
                            @if($row->status == 'aktif')
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Aktif</span>
                            @elseif($row->status == 'lulus')
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">Lulus</span>
                            @else
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">Keluar</span>
                            @endif
                        </td>
                        @if(!$isGuru)
                        <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                            <div class="flex justify-center gap-2">
                                <a href="{{ route('siswa.edit', $row) }}" class="text-orange-600 hover:text-orange-900 bg-orange-50 hover:bg-orange-100 p-1.5 rounded-md transition">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                </a>
                                <form action="{{ route('siswa.destroy', $row) }}" method="POST" class="inline" onsubmit="return confirm('Yakin ingin menghapus data siswa ini?');">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-900 bg-red-50 hover:bg-red-100 p-1.5 rounded-md transition">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                        @endif
                    </tr>
                    @empty
                    <tr>
                        <td colspan="{{ $isGuru ? 5 : 6 }}" class="px-6 py-10 text-center text-gray-500">
                            Belum ada data siswa.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($siswa->hasPages())
        <div class="px-4 py-3 border-t border-gray-200 bg-gray-50" id="pagination">
            {{ $siswa->links() }}
        </div>
        @endif
    </div>
</div>
@endsection

@section('scripts')
<script>
    const isGuru = @json($isGuru);

    function fetchSiswa() {
        const search = document.getElementById('search').value;
        const url = `/api/siswa?search=${encodeURIComponent(search)}`;

        console.log("Fetching URL:", url); // Debugging

        fetch(url)
            .then(res => {
                // Cek status HTTP
                if (!res.ok) {
                    throw new Error(`HTTP Error! Status: ${res.status}`);
                }
                // Cek content-type apakah JSON
                const contentType = res.headers.get("content-type");
                if (!contentType || !contentType.includes("application/json")) {
                    return res.text().then(text => {
                        console.error("Response bukan JSON:", text);
                        throw new Error("Server merespon dengan HTML, bukan JSON. Cek Route/Controller.");
                    });
                }
                return res.json();
            })
            .then(data => {
                console.log("Data diterima:", data); // Debugging
                renderTable(data);
            })
            .catch(err => {
                console.error("Fetch Error:", err);
                // Jangan hapus tabel jika error, biarkan data lama
            });
    }

    function renderTable(data) {
        let tbody = '';
        if (data.length === 0) {
            tbody = `<tr><td colspan="${isGuru ? 5 : 6}" class="px-6 py-10 text-center text-gray-500">Data tidak ditemukan.</td></tr>`;
        } else {
            data.forEach((row, i) => {
                const nomor = i + 1;
                const jkClass = row.jenis_kelamin == 'L' ? 'bg-blue-100 text-blue-800' : 'bg-pink-100 text-pink-800';
                
                let statusHtml = '';
                if (row.status == 'aktif') statusHtml = '<span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Aktif</span>';
                else if (row.status == 'lulus') statusHtml = '<span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">Lulus</span>';
                else statusHtml = '<span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">Keluar</span>';

                let actionHtml = '';
                if (!isGuru) {
                    actionHtml = `
                    <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                        <div class="flex justify-center gap-2">
                            <a href="/siswa/${row.id}/edit" class="text-orange-600 hover:text-orange-900 bg-orange-50 hover:bg-orange-100 p-1.5 rounded-md transition">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                            </a>
                            <form action="/siswa/${row.id}" method="POST" class="inline" onsubmit="return confirm('Yakin hapus?');">
                                <input type="hidden" name="_token" value="${document.querySelector('meta[name="csrf-token"]').content}">
                                <input type="hidden" name="_method" value="DELETE">
                                <button type="submit" class="text-red-600 hover:text-red-900 bg-red-50 hover:bg-red-100 p-1.5 rounded-md transition">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                </button>
                            </form>
                        </div>
                    </td>`;
                }

                tbody += `
                <tr class="hover:bg-orange-50/50 transition duration-150">
                    <td class="px-6 py-4 whitespace-nowrap text-center text-sm text-gray-500">${nomor}</td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex flex-col">
                            <span class="text-sm font-medium text-gray-900">${row.nama}</span>
                            <span class="text-xs text-gray-500">NIS: ${row.nis ?? '-'}</span>
                            ${row.kelas_nama && row.kelas_nama !== '-' ? `<span class="text-[10px] text-orange-600 bg-orange-50 px-1 rounded w-fit mt-0.5">${row.kelas_nama}</span>` : ''}
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-center text-sm text-gray-500">
                        <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full ${jkClass}">${row.jenis_kelamin}</span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-center text-sm text-gray-500">${row.no_hp_ortu ?? '-'}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-center">${statusHtml}</td>
                    ${actionHtml}
                </tr>`;
            });
        }
        document.getElementById('siswa-tbody').innerHTML = tbody;
        
        // Hide pagination if searching
        const pag = document.getElementById('pagination');
        if (pag) pag.style.display = 'none';
    }

    let timeout = null;
    document.getElementById('search').addEventListener('input', function(e) {
        clearTimeout(timeout);
        // Jika kosong, reload halaman agar kembali ke pagination awal
        if(e.target.value.trim() === "") {
            window.location.reload(); 
            return;
        }
        timeout = setTimeout(fetchSiswa, 500);
    });
</script>
@endsection