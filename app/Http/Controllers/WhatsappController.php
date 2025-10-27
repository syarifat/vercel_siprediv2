<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Siswa;
use App\Models\Kelas;
use App\Models\RombelSiswa;
use App\Models\Report;

class WhatsappController extends Controller
{
    public function index()
    {
        $kelas = Kelas::all();
        $siswa = Siswa::all();
        return view('whatsapp.index', compact('kelas', 'siswa'));
    }

    private function sendFonnte($hp, $pesan)
    {
        $token = 'JMyNJwRy999NVUj4eHfS';
        $url = 'https://api.fonnte.com/send';
        $data = [
            'target' => $hp,
            'message' => $pesan,
            'countryCode' => '62'
        ];
        $client = new \GuzzleHttp\Client();
        try {
            $response = $client->post($url, [
                'headers' => ['Authorization' => $token],
                'form_params' => $data
            ]);
        } catch (\Exception $e) {
            \Log::error('Fonnte send exception: ' . $e->getMessage());
            return ['success' => false, 'reason' => $e->getMessage()];
        }
        $body = $response->getBody()->getContents();
        $res = json_decode($body, true);

        \Log::info('Fonnte send response:', $res);

        if (isset($res["id"])) {
            foreach($res["id"] as $k => $v){
                \App\Models\Report::create([
                    'message_id' => $v,
                    'target' => $res["target"][$k] ?? $hp,
                    'message' => $pesan,
                    'status' => $res["process"] ?? null,
                ]);
            }
            return ['success' => true];
        } else {
            \Log::error('No message id in Fonnte response', $res);
            return ['success' => false, 'reason' => $res['reason'] ?? 'Gagal kirim'];
        }
    }

    public function send(Request $request)
    {
        $request->validate([
            'pesan' => 'required',
            'tipe' => 'required|in:semua,kelas,individu',
            'kelas_id' => 'nullable|exists:kelas,id',
            'no_hp_ortu' => 'nullable|string',
        ]);

        $nomor = [];
        if ($request->tipe == 'semua') {
            $nomor = Siswa::pluck('no_hp_ortu')->toArray();
        } elseif ($request->tipe == 'kelas') {
            if (!$request->kelas_id) {
                return back()->with('error', 'Pilih kelas terlebih dahulu.');
            }
            $siswaIds = RombelSiswa::where('kelas_id', $request->kelas_id)->pluck('siswa_id');
            $nomor = Siswa::whereIn('id', $siswaIds)->pluck('no_hp_ortu')->toArray();
        } elseif ($request->tipe == 'individu') {
            if (!$request->no_hp_ortu) {
                return back()->with('error', 'Pilih siswa terlebih dahulu.');
            }
            $nomor[] = $request->no_hp_ortu;
        }

        // filter out empty numbers
        $nomor = array_filter(array_map(function($n) {
            return $n === null ? null : trim($n);
        }, $nomor));

        $failed = [];
        foreach ($nomor as $hp) {
            $original = $hp;
            // normalize phone number to target + country
            $norm = $this->normalizePhone($hp);
            if (!$norm) {
                $failed[] = ['number' => $original, 'reason' => 'Format nomor tidak valid'];
                continue;
            }
            $target = $norm['target'];
            $country = $norm['country'];
            try {
                $result = $this->sendFonnte($target, $request->pesan);
                if (!isset($result['success']) || !$result['success']) {
                    $reason = $result['reason'] ?? 'Unknown error';
                    $failed[] = ['number' => $original, 'reason' => $reason];
                }
            } catch (\Exception $e) {
                \Log::error('Failed sending whatsapp to ' . $original . ': ' . $e->getMessage());
                $failed[] = ['number' => $original, 'reason' => $e->getMessage()];
            }
        }

        if (count($failed) > 0) {
            // Log full failed list for debugging
            \Log::warning('WhatsApp send failed list', ['failed' => $failed]);

            // Build a readable message (limit to first 10 numbers to avoid huge flash)
            $items = array_map(function($f) {
                return ($f['number'] ?: '-') . ' (' . ($f['reason'] ?: '-') . ')';
            }, array_slice($failed, 0, 10));
            $msg = "Gagal kirim ke " . count($failed) . " nomor: " . implode(', ', $items);
            if (count($failed) > 10) {
                $msg .= " (dan " . (count($failed) - 10) . " lainnya)";
            }
            $msg .= ". Periksa koneksi device WhatsApp atau format nomor. Detail lengkap ada di log.";
            return back()->with('error', $msg);
        }

        return back()->with('success', 'Pesan berhasil dikirim!');
    }

    /**
     * Normalize phone number for Fonnte: return ['target' => number_without_country, 'country' => countryCode]
     */
    private function normalizePhone($hp)
    {
        if (!$hp) return null;
        $s = preg_replace('/[^0-9+]/', '', $hp);
        // remove leading + if present
        if (substr($s, 0, 1) === '+') $s = substr($s, 1);

        // if starts with 0 -> remove leading 0 and country 62
        if (substr($s, 0, 1) === '0') {
            $target = substr($s, 1);
            return ['target' => $target, 'country' => '62'];
        }
        // if starts with 62
        if (substr($s, 0, 2) === '62') {
            $target = substr($s, 2);
            return ['target' => $target, 'country' => '62'];
        }
        // if starts with 8 (local without leading 0)
        if (substr($s, 0, 1) === '8') {
            return ['target' => $s, 'country' => '62'];
        }
        // otherwise, return raw digits and empty country (let API infer)
        return ['target' => $s, 'country' => ''];
    }

    public function qr()
    {
        $token = 'JMyNJwRy999NVUj4eHfS';
        $url = 'https://api.fonnte.com/qr';

        $client = new \GuzzleHttp\Client();
        $response = $client->post($url, [
            'headers' => ['Authorization' => $token],
            'form_params' => [
                'type' => 'qr',
                'whatsapp' => '6287859017087' // ganti dengan nomor device kamu
            ]
        ]);
        $data = json_decode($response->getBody(), true);

        // Jika device sudah connect, tampilkan pesan
        if (isset($data['reason']) && $data['reason'] === 'device already connect') {
            $qr = null;
            $reason = $data['reason'];
        } elseif (isset($data['url'])) {
            $qr = $data['url'];
            $reason = null;
        } else {
            $qr = null;
            $reason = $data['reason'] ?? 'QR tidak tersedia';
        }

        return view('whatsapp.qr', compact('qr', 'reason'));
    }

    public function webhook(Request $request)
    {
        \Log::info('Webhook Fonnte masuk:', $request->all());

        $data = $request->all();
        $id = $data['id'] ?? null;
        $stateid = $data['stateid'] ?? null;
        $status = $data['status'] ?? null;
        $state = $data['state'] ?? null;

        if ($id) {
            $report = Report::where('message_id', $id)->first();
            if ($report) {
                $report->update([
                    'status' => $status,
                    'state' => $state,
                    'stateid' => $stateid,
                ]);
            }
        }
        return response()->json(['success' => true]);
    }

    public function report()
    {
        $reports = \App\Models\Report::orderBy('created_at', 'desc')->get();
        return view('whatsapp.report', compact('reports'));
    }
}