@extends('layouts.app')

@section('content')
<div class="max-w-6xl mx-auto space-y-6">
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">Laporan WhatsApp</h2>
            <p class="text-sm text-gray-500">Log pengiriman pesan broadcast.</p>
        </div>
        
        <button onclick="window.location.reload()" class="inline-flex items-center justify-center px-4 py-2 bg-white border border-gray-300 text-gray-700 text-sm font-medium rounded-lg shadow-sm hover:bg-gray-50 transition-colors duration-200">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
            Refresh Data
        </button>
    </div>

    <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 rounded-r shadow-sm flex items-start">
        <svg class="w-5 h-5 text-yellow-400 mr-3 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
        </svg>
        <div>
            <h4 class="text-sm font-bold text-yellow-800">Status Beta</h4>
            <p class="text-sm text-yellow-700">Fitur pelacakan status pesan masih dalam pengembangan. Status 'Terkirim' atau 'Gagal' mungkin mengalami keterlambatan update (delay).</p>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-orange-50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-orange-800 uppercase tracking-wider">Waktu / ID</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-orange-800 uppercase tracking-wider">Tujuan</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-orange-800 uppercase tracking-wider w-1/2">Pesan</th>
                        <th scope="col" class="px-6 py-3 text-center text-xs font-bold text-orange-800 uppercase tracking-wider">Status</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($reports as $report)
                    <tr class="hover:bg-orange-50/50 transition duration-150">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex flex-col">
                                <span class="text-xs font-medium text-gray-500">{{ $report->created_at ?? now()->format('d M H:i') }}</span>
                                <span class="text-xs text-gray-400 font-mono">{{ Str::limit($report->message_id, 10) }}</span>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700 font-medium">
                            {{ $report->target }}
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-600">
                            {{ Str::limit($report->message, 60) }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-center">
                            @if(strtolower($report->status) == 'sent' || strtolower($report->status) == 'terkirim')
                                <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                    Terkirim
                                </span>
                            @elseif(strtolower($report->status) == 'failed' || strtolower($report->status) == 'gagal')
                                <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                    Gagal
                                </span>
                            @else
                                <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">
                                    {{ ucfirst($report->status) }}
                                </span>
                            @endif
                            <div class="text-[10px] text-gray-400 mt-1">{{ $report->state ?? '-' }}</div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="px-6 py-10 text-center text-gray-500">
                            Belum ada laporan pengiriman.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection