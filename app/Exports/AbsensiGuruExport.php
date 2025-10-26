<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class AbsensiGuruExport implements FromCollection, WithHeadings, WithMapping
{
    protected $items;

    public function __construct($items)
    {
        $this->items = $items;
    }

    public function collection()
    {
        return collect($this->items);
    }

    public function headings(): array
    {
        return [
            'No', 'Nama Guru', 'Tanggal', 'Jam Masuk', 'Jam Pulang', 'Status', 'Keterangan'
        ];
    }

    public function map($row): array
    {
        return [
            '', // nomor akan diisi oleh Excel consumer or left blank
            $row->guru->nama ?? '-',
            $row->tanggal,
            $row->jam_masuk ?? '-',
            $row->jam_pulang ?? '-',
            $row->status ?? '-',
            $row->keterangan ?? '-',
        ];
    }
}
