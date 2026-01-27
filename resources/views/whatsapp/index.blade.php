@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto">
    <nav class="flex mb-4" aria-label="Breadcrumb">
        <ol class="inline-flex items-center space-x-1 md:space-x-3">
            <li class="inline-flex items-center">
                <a href="{{ route('dashboard') }}" class="text-sm font-medium text-gray-500 hover:text-orange-600">Dashboard</a>
            </li>
            <li>
                <div class="flex items-center">
                    <svg class="w-3 h-3 text-gray-400 mx-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10"><path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 9 4-4-4-4"/></svg>
                    <span class="ml-1 text-sm font-medium text-gray-700 md:ml-2">WhatsApp Gateway</span>
                </div>
            </li>
        </ol>
    </nav>

    {{-- LOGIC UTAMA: CONNECTED VS DISCONNECTED --}}
    
    @if(!$isConnected)
        {{-- TAMPILAN JIKA DISCONNECT (MINTA SCAN QR) --}}
        <div class="bg-white rounded-xl shadow-lg border border-red-200 overflow-hidden text-center p-8">
            <div class="mb-6">
                <div class="mx-auto bg-red-100 w-16 h-16 rounded-full flex items-center justify-center mb-4">
                    <svg class="w-8 h-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                </div>
                <h2 class="text-2xl font-bold text-gray-800">WhatsApp Terputus!</h2>
                <p class="text-gray-500">Silakan scan QR Code di bawah ini menggunakan WhatsApp pada HP Anda untuk menghubungkan ulang.</p>
            </div>

            <div class="flex justify-center mb-6">
                @if($qrCode)
                    <div class="p-4 bg-white border-2 border-dashed border-gray-300 rounded-lg">
                        {{-- Fonnte mengirim QR dalam format Base64 --}}
                        <img src="data:image/png;base64,{{ $qrCode }}" alt="Scan Me" class="w-64 h-64 mx-auto">
                    </div>
                @else
                    <div class="p-8 bg-gray-50 rounded-lg border border-gray-200">
                        <p class="text-gray-400 italic">Gagal memuat QR Code. Pastikan Token Benar / API Aktif.</p>
                    </div>
                @endif
            </div>

            <div class="space-y-3">
                <p class="text-sm text-gray-400">Setelah scan berhasil, klik tombol refresh di bawah ini.</p>
                <a href="{{ route('whatsapp.index') }}" class="inline-flex items-center justify-center px-6 py-3 bg-blue-600 text-white font-bold rounded-lg hover:bg-blue-700 transition shadow-md">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                    Refresh Status
                </a>
            </div>
        </div>

    @else
        {{-- TAMPILAN JIKA CONNECTED (FORM BROADCAST) --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            {{-- Header Status Connect --}}
            <div class="px-6 py-4 border-b border-gray-100 bg-green-50 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="bg-green-100 p-2 rounded-full border border-green-200">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-green-600" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.462-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.372-.272.297-1.04 1.017-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z"/>
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-lg font-bold text-gray-800">WhatsApp Terhubung</h2>
                        <p class="text-xs text-green-700 font-semibold">
                            Device: {{ $deviceInfo['name'] ?? 'Unknown' }} | Exp: {{ $deviceInfo['expired'] ?? '-' }}
                        </p>
                    </div>
                </div>
                {{-- Tombol Refresh kecil untuk cek status --}}
                <a href="{{ route('whatsapp.index') }}" class="text-xs text-gray-500 hover:text-orange-600 flex items-center">
                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                    Cek Status
                </a>
            </div>

            <div class="p-6">
                {{-- Notifikasi --}}
                @if(session('error'))
                    <div class="mb-4 bg-red-50 border-l-4 border-red-500 p-4 text-sm text-red-700 rounded-r shadow-sm flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        {{ session('error') }}
                    </div>
                @endif
                @if(session('success'))
                    <div class="mb-4 bg-green-50 border-l-4 border-green-500 p-4 text-sm text-green-700 rounded-r shadow-sm flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        {{ session('success') }}
                    </div>
                @endif

                {{-- Form Broadcast --}}
                <form method="POST" action="{{ route('whatsapp.send') }}">
                    @csrf
                    
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Tujuan Pengiriman</label>
                        <select name="tipe" id="tipe" onchange="toggleForm()" class="w-full rounded-lg border-gray-300 focus:border-orange-500 focus:ring-orange-500 shadow-sm transition">
                            <option value="semua">Semua Wali Siswa (Aktif)</option>
                            <option value="kelas">Per Kelas</option>
                            <option value="individu">Siswa Individu</option>
                        </select>
                    </div>

                    <div id="kelasForm" style="display:none;" class="mb-6 p-4 bg-gray-50 rounded-lg border border-gray-100">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Pilih Kelas</label>
                        <select name="kelas_id" class="w-full rounded-lg border-gray-300 focus:border-orange-500 focus:ring-orange-500 shadow-sm transition">
                            @foreach($kelas as $k)
                                <option value="{{ $k->id }}">{{ $k->nama }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div id="individuForm" style="display:none;" class="mb-6 p-4 bg-gray-50 rounded-lg border border-gray-100">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Pilih Siswa</label>
                        <select name="no_hp_ortu" class="w-full rounded-lg border-gray-300 focus:border-orange-500 focus:ring-orange-500 shadow-sm transition">
                            @foreach($siswa as $s)
                                <option value="{{ $s->no_hp_ortu }}">{{ $s->nama }} ({{ $s->nis }}) - {{ $s->no_hp_ortu }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Isi Pesan</label>
                        <textarea name="pesan" rows="5" required
                            class="w-full rounded-lg border-gray-300 focus:border-orange-500 focus:ring-orange-500 shadow-sm transition placeholder-gray-400"
                            placeholder="Tulis pesan broadcast di sini..."></textarea>
                        <p class="text-xs text-gray-500 mt-1">Gunakan *teks* untuk tebal, _teks_ untuk miring.</p>
                    </div>

                    <div class="flex items-center justify-end gap-3 pt-4 border-t border-gray-100">
                        <button type="submit" class="flex items-center gap-2 px-6 py-2.5 rounded-lg bg-green-600 text-white text-sm font-bold hover:bg-green-700 shadow-md hover:shadow-lg transition duration-200">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                <path d="M10.894 2.553a1 1 0 00-1.788 0l-7 14a1 1 0 001.169 1.409l5-1.429A1 1 0 009 15.571V11a1 1 0 112 0v4.571a1 1 0 00.725.962l5 1.428a1 1 0 001.17-1.408l-7-14z" />
                            </svg>
                            Kirim Pesan
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <script>
        function toggleForm() {
            var tipe = document.getElementById('tipe').value;
            document.getElementById('kelasForm').style.display = tipe === 'kelas' ? 'block' : 'none';
            document.getElementById('individuForm').style.display = tipe === 'individu' ? 'block' : 'none';
        }
        document.addEventListener('DOMContentLoaded', toggleForm);
        </script>
    @endif
</div>
@endsection