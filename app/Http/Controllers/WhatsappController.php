<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Siswa;
use App\Models\Kelas;
use App\Models\RombelSiswa;
use App\Models\Report;
use GuzzleHttp\Client; // Pastikan install guzzlehttp/guzzle

class WhatsappController extends Controller
{
    public function index()
    {
        $kelas = Kelas::all();
        $siswa = Siswa::where('status', 'aktif')->get();
        return view('whatsapp.index', compact('kelas', 'siswa'));
    }

    public function send(Request $request)
    {
        $request->validate([
            'pesan' => 'required',
            'tipe' => 'required|in:semua,kelas,individu',
        ]);

        $nomorTujuan = [];

        if ($request->tipe == 'semua') {
            $nomorTujuan = Siswa::where('status', 'aktif')->pluck('no_hp_ortu')->toArray();
        } elseif ($request->tipe == 'kelas') {
            $rombel = RombelSiswa::where('kelas_id', $request->kelas_id)
                ->where('tahun_ajaran_id', session('tahun_ajaran_id'))
                ->with('siswa')
                ->get();
            $nomorTujuan = $rombel->pluck('siswa.no_hp_ortu')->toArray();
        } elseif ($request->tipe == 'individu') {
            $nomorTujuan[] = $request->no_hp_ortu;
        }

        // Filter nomor kosong & duplikat
        $nomorTujuan = array_unique(array_filter($nomorTujuan));

        if (empty($nomorTujuan)) {
            return back()->with('error', 'Tidak ada nomor tujuan yang valid.');
        }

        // Logic kirim Fonnte
        $client = new Client();
        foreach ($nomorTujuan as $nomor) {
            try {
                $client->post('https://api.fonnte.com/send', [
                    'headers' => ['Authorization' => 'JMyNJwRy999NVUj4eHfS'], // Ganti Token Fonnte
                    'form_params' => [
                        'target' => $nomor,
                        'message' => $request->pesan,
                        'countryCode' => '62',
                    ],
                ]);
            } catch (\Exception $e) {
                // Log error tapi lanjut loop
                \Log::error("Gagal kirim WA ke $nomor: " . $e->getMessage());
            }
        }

        return back()->with('success', 'Pesan broadcast sedang dikirim.');
    }

    public function qr()
    {
        // Logic ambil QR Fonnte
        $client = new Client();
        try {
            $response = $client->post('https://api.fonnte.com/qr', [
                'headers' => ['Authorization' => 'JMyNJwRy999NVUj4eHfS'],
            ]);
            $data = json_decode($response->getBody(), true);
            $qr = $data['url'] ?? null;
            $reason = $data['reason'] ?? null;
        } catch (\Exception $e) {
            $qr = null;
            $reason = $e->getMessage();
        }

        return view('whatsapp.qr', compact('qr', 'reason'));
    }

    public function report()
    {
        $reports = Report::orderBy('created_at', 'desc')->paginate(20);
        return view('whatsapp.report', compact('reports'));
    }
    
    // Webhook Fonnte untuk update status pesan
    public function webhook(Request $request)
    {
        $data = $request->all();
        // Simpan log ke tabel Report
        if(isset($data['id'])) {
            Report::updateOrCreate(
                ['message_id' => $data['id']],
                [
                    'status' => $data['status'] ?? 'unknown',
                    'target' => $data['sender'] ?? '-', // kadang sender, kadang target tergantung webhook
                    'message' => $data['message'] ?? '-',
                ]
            );
        }
        return response()->json(['status' => 'received']);
    }
}