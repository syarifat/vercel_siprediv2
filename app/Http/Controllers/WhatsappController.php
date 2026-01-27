<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Siswa;
use App\Models\Kelas;
use App\Models\RombelSiswa;
use App\Models\Report;
use GuzzleHttp\Client;

class WhatsappController extends Controller
{
    // Token sebaiknya ditaruh di .env (FONNTE_TOKEN), tapi ini hardcode sesuai request
    private $token = 'JMyNJwRy999NVUj4eHfS'; 

    public function index()
    {
        $client = new Client();
        $isConnected = false;
        $qrCode = null;
        $deviceInfo = null;

        // 1. Cek Status Device ke Fonnte
        try {
            $response = $client->post('https://api.fonnte.com/device', [
                'headers' => ['Authorization' => $this->token],
            ]);
            $data = json_decode($response->getBody(), true);
            
            // Fonnte biasanya return: status, device_status, name, expired, dll
            if (isset($data['device_status']) && $data['device_status'] == 'connect') {
                $isConnected = true;
                $deviceInfo = $data;
            }
        } catch (\Exception $e) {
            $isConnected = false;
        }

        // 2. Logika Tampilan
        if ($isConnected) {
            // JIKA CONNECT: Siapkan data untuk Form Broadcast
            $kelas = Kelas::all();
            $siswa = Siswa::where('status', 'aktif')->get();
            
            return view('whatsapp.index', compact('isConnected', 'kelas', 'siswa', 'deviceInfo'));
        } else {
            // JIKA DISCONNECT: Ambil QR Code
            try {
                $responseQr = $client->post('https://api.fonnte.com/qr', [
                    'headers' => ['Authorization' => $this->token],
                ]);
                $dataQr = json_decode($responseQr->getBody(), true);
                $qrCode = $dataQr['url'] ?? null; // Base64 image string
            } catch (\Exception $e) {
                $qrCode = null;
            }

            return view('whatsapp.index', compact('isConnected', 'qrCode'));
        }
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

        $nomorTujuan = array_unique(array_filter($nomorTujuan));

        if (empty($nomorTujuan)) {
            return back()->with('error', 'Tidak ada nomor tujuan yang valid.');
        }

        $client = new Client();
        // Kirim Broadcast (Looping sederhana, idealnya pakai Job Queue Laravel)
        foreach ($nomorTujuan as $nomor) {
            try {
                $client->post('https://api.fonnte.com/send', [
                    'headers' => ['Authorization' => $this->token],
                    'form_params' => [
                        'target' => $nomor,
                        'message' => $request->pesan,
                        'countryCode' => '62',
                    ],
                ]);
            } catch (\Exception $e) {
                \Log::error("Gagal kirim WA ke $nomor: " . $e->getMessage());
            }
        }

        return back()->with('success', 'Pesan broadcast sedang dikirim ke server WhatsApp.');
    }

    public function report()
    {
        $reports = Report::orderBy('created_at', 'desc')->paginate(20);
        return view('whatsapp.report', compact('reports'));
    }
    
    public function webhook(Request $request)
    {
        $data = $request->all();
        if(isset($data['id'])) {
            Report::updateOrCreate(
                ['message_id' => $data['id']],
                [
                    'status' => $data['status'] ?? 'unknown',
                    'target' => $data['sender'] ?? '-', 
                    'message' => $data['message'] ?? '-',
                ]
            );
        }
        return response()->json(['status' => 'received']);
    }
}