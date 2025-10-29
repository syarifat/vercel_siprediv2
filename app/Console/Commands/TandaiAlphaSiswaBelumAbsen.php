<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class TandaiAlphaSiswaBelumAbsen extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'absensi:tandai-alpha {--tahun= : ID tahun_ajaran untuk diproses} {--dry-run : Tampilkan saja tanpa menyimpan} {--limit= : Batasi jumlah siswa yang diproses} {--use-last : Jika tidak ada rombel untuk tahun, gunakan rombel terakhir siswa}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Otomatis menandai siswa yang belum absen sampai jam 9 sebagai Alpha';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        date_default_timezone_set('Asia/Jakarta');
        $tanggal = date('Y-m-d');
        $jam = '09:00:00';
        $jumlah = 0;
        // Determine tahun ajaran to use: explicit option -> active -> latest
        $tahunOption = $this->option('tahun');
        if ($tahunOption) {
            $tahun = \App\Models\TahunAjaran::find($tahunOption);
            if (!$tahun) {
                $this->error("TahunAjaran dengan id {$tahunOption} tidak ditemukan. Gagal.");
                return 1;
            }
        } else {
            $tahun = \App\Models\TahunAjaran::where('aktif', true)->first();
            if (!$tahun) {
                $tahun = \App\Models\TahunAjaran::orderBy('id', 'desc')->first();
            }
        }

        $limit = (int) $this->option('limit') ?: null;
        $siswaQuery = \App\Models\Siswa::with('rombel');
        if ($limit) {
            $siswaQuery = $siswaQuery->limit($limit);
        }
        $siswaList = $siswaQuery->get();
        $dry = (bool) $this->option('dry-run');
        foreach ($siswaList as $siswa) {
            // find the student's rombel for the active year
            $rombelQuery = \App\Models\RombelSiswa::where('siswa_id', $siswa->id);

            // If user specified a tahun id, match that. Otherwise match rombel whose tahunAjaran is aktif = 1
            if ($this->option('tahun')) {
                $rombelQuery = $rombelQuery->where('tahun_ajaran_id', $tahun->id);
            } else {
                $rombelQuery = $rombelQuery->whereHas('tahunAjaran', function ($q) {
                    $q->where('aktif', true);
                });
            }

            $rombel = $rombelQuery->first();

            if (!$rombel) {
                // student not assigned to any rombel for the selected year
                if ($this->option('use-last')) {
                    $rombel = \App\Models\RombelSiswa::where('siswa_id', $siswa->id)
                        ->orderBy('tahun_ajaran_id', 'desc')
                        ->first();
                    if ($rombel) {
                        $this->line("[FALLBACK] {$siswa->nama} (id={$siswa->id}) - menggunakan rombel terakhir (tahun_id={$rombel->tahun_ajaran_id})");
                        // proceed with this rombel
                    } else {
                        $this->line("[SKIP] {$siswa->nama} (id={$siswa->id}) - tidak ada rombel sama sekali");
                        continue;
                    }
                } else {
                    $this->line("[SKIP] {$siswa->nama} (id={$siswa->id}) - tidak ada rombel untuk tahun yang dipilih");
                    continue;
                }
            }

            $sudahAbsen = \App\Models\Absensi::where('rombel_siswa_id', $rombel->id)
                ->where('tanggal', $tanggal)
                ->exists();

            if ($sudahAbsen) {
                $this->line("[EXISTS] {$siswa->nama} (rombel_id={$rombel->id}) sudah absen hari ini");
                continue;
            }

            if ($dry) {
                $this->line("[DRY] akan menandai Alpha: {$siswa->nama} (rombel_id={$rombel->id})");
                $jumlah++;
                continue;
            }

            \App\Models\Absensi::create([
                'rombel_siswa_id' => $rombel->id,
                'tanggal' => $tanggal,
                'jam_masuk' => $jam,
                'status' => 'Alpha',
                'keterangan' => 'Ditandai alpha oleh sistem',
            ]);
            $jumlah++;
        }
        $this->info("{$jumlah} siswa ditandai Alpha otomatis.");
    }
}
