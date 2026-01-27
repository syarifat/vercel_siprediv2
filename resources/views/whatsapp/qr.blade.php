@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto">
    <nav class="flex mb-4" aria-label="Breadcrumb">
        <ol class="inline-flex items-center space-x-1 md:space-x-3">
            <li class="inline-flex items-center">
                <a href="{{ route('whatsapp.index') }}" class="text-sm font-medium text-gray-500 hover:text-orange-600">WhatsApp</a>
            </li>
            <li>
                <div class="flex items-center">
                    <svg class="w-3 h-3 text-gray-400 mx-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10"><path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 9 4-4-4-4"/></svg>
                    <span class="ml-1 text-sm font-medium text-gray-700 md:ml-2">Scan QR</span>
                </div>
            </li>
        </ol>
    </nav>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden flex flex-col h-full">
            <div class="px-6 py-4 border-b border-gray-100 bg-orange-50">
                <h2 class="text-lg font-bold text-gray-800">Hubungkan Perangkat</h2>
            </div>
            <div class="p-8 flex-1 flex flex-col items-center justify-center bg-gray-50">
                @if($qr)
                    <div class="p-2 bg-white rounded-lg shadow-md border border-gray-200">
                        <img src="data:image/png;base64,{{ $qr }}" alt="QR Code WhatsApp" class="w-64 h-64">
                    </div>
                    <p class="mt-4 text-sm font-medium text-green-600 animate-pulse">● Menunggu Scan...</p>
                @elseif($reason)
                    <div class="text-center p-6 bg-red-50 rounded-lg border border-red-100">
                        <svg class="w-12 h-12 text-red-400 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                        <h3 class="text-lg font-bold text-red-700">Gagal Memuat QR</h3>
                        <p class="text-sm text-red-600 mt-1">{{ $reason }}</p>
                    </div>
                @else
                    <div class="text-center p-6 bg-red-50 rounded-lg border border-red-100">
                        <p class="text-red-600 font-bold">QR Code tidak tersedia saat ini.</p>
                        <p class="text-sm text-gray-500 mt-1">Coba refresh halaman beberapa saat lagi.</p>
                    </div>
                @endif
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden h-full">
            <div class="px-6 py-4 border-b border-gray-100 bg-gray-50">
                <h2 class="text-lg font-bold text-gray-800">Cara Menghubungkan</h2>
            </div>
            <div class="p-6">
                <ol class="relative border-l border-gray-200 ml-3">                  
                    <li class="mb-10 ml-6">            
                        <span class="absolute flex items-center justify-center w-8 h-8 bg-orange-100 rounded-full -left-4 ring-4 ring-white">
                            <svg class="w-4 h-4 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                        </span>
                        <h3 class="font-medium leading-tight text-gray-900">Buka WhatsApp</h3>
                        <p class="text-sm text-gray-500">Buka aplikasi WhatsApp di HP Anda.</p>
                    </li>
                    <li class="mb-10 ml-6">
                        <span class="absolute flex items-center justify-center w-8 h-8 bg-orange-100 rounded-full -left-4 ring-4 ring-white">
                            <svg class="w-4 h-4 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                        </span>
                        <h3 class="font-medium leading-tight text-gray-900">Buka Menu</h3>
                        <p class="text-sm text-gray-500">Ketuk menu titik tiga (Android) atau Pengaturan (iPhone).</p>
                    </li>
                    <li class="mb-10 ml-6">
                        <span class="absolute flex items-center justify-center w-8 h-8 bg-orange-100 rounded-full -left-4 ring-4 ring-white">
                            <svg class="w-4 h-4 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/></svg>
                        </span>
                        <h3 class="font-medium leading-tight text-gray-900">Perangkat Tertaut</h3>
                        <p class="text-sm text-gray-500">Pilih menu <strong>Perangkat Tertaut</strong> (Linked Devices) > <strong>Tautkan Perangkat</strong>.</p>
                    </li>
                    <li class="ml-6">
                        <span class="absolute flex items-center justify-center w-8 h-8 bg-orange-100 rounded-full -left-4 ring-4 ring-white">
                            <svg class="w-4 h-4 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        </span>
                        <h3 class="font-medium leading-tight text-gray-900">Arahkan Kamera</h3>
                        <p class="text-sm text-gray-500">Arahkan kamera HP ke kode QR di layar ini.</p>
                    </li>
                </ol>
            </div>
        </div>
    </div>
</div>
@endsection