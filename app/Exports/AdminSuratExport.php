<?php

namespace App\Exports;

use App\Models\Surat;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

class AdminSuratExport implements FromCollection, WithHeadings, WithMapping, WithStyles, ShouldAutoSize
{
    protected $data;
    private $rowNumber = 0;

    /**
     * @param Builder|Collection $queryOrCollection
     */
    public function __construct($queryOrCollection)
    {
        if ($queryOrCollection instanceof Builder) {
            $this->data = $queryOrCollection->get();
        } elseif ($queryOrCollection instanceof Collection) {
            $this->data = $queryOrCollection;
        } else {
            $this->data = collect($queryOrCollection);
        }
    }

    public function collection()
    {
        return $this->data;
    }

    public function headings(): array
    {
        return [
            'No',
            'Nomor Surat',
            'Judul Surat',
            'Nama Pengusul',
            'Jenis Surat',
            'Sifat',
            'Tujuan Surat',
            'Tgl Pengajuan',
            'Tgl Selesai / Disetujui',
            'Deadline SLA',
            'Tahap Proses',
            'Status',
            'SLA Status',
        ];
    }

    /**
     * @param Surat $surat
     */
    public function map($surat): array
    {
        $this->rowNumber++;

        return [
            $this->rowNumber,
            $surat->nomor_surat ?? '-',
            $surat->judul,
            $surat->user?->name ?? '-',
            $surat->jenis_label,
            ucfirst($surat->sifat),
            $surat->tujuan ?? '-',
            $surat->created_at ? $surat->created_at->format('d/m/Y H:i') : '-',
            $surat->disetujui_pada ? $surat->disetujui_pada->format('d/m/Y H:i') : '-',
            $surat->deadline_sla ? $surat->deadline_sla->format('d/m/Y H:i') : '-',
            "Tahap {$surat->tahap_sekarang}/10 — {$surat->nama_tahap}",
            ucfirst($surat->status),
            $surat->sla_status === 'terlambat' ? 'Terlambat' : 'Tepat Waktu',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => [
                'font' => [
                    'bold' => true,
                    'color' => ['rgb' => 'FFFFFF'],
                    'size' => 11,
                ],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => ['rgb' => '1E3A5F'],
                ],
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                    'vertical' => Alignment::VERTICAL_CENTER,
                ],
            ],
        ];
    }
}
