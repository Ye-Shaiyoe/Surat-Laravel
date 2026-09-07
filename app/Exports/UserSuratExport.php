<?php

namespace App\Exports;

use App\Models\Surat;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use Illuminate\Support\Facades\Auth;

class UserSuratExport implements FromCollection, WithHeadings, WithMapping, WithStyles, ShouldAutoSize
{
    protected $filters;
    private $rowNumber = 0;

    public function __construct($filters = [])
    {
        $this->filters = $filters;
    }

    public function collection()
    {
        $query = Surat::where('user_id', Auth::id())
                      ->with('tahapans')
                      ->latest();

        if (!empty($this->filters['status'])) {
            $query->where('status', $this->filters['status']);
        } else {
            $query->where('status', '!=', 'draft');
        }

        if (!empty($this->filters['jenis'])) {
            $query->where('jenis', $this->filters['jenis']);
        }

        // Filter Rentang Tanggal Pengajuan (created_at)
        $startDate = $this->filters['tanggal_mulai'] ?? $this->filters['start_date'] ?? null;
        $endDate = $this->filters['tanggal_selesai'] ?? $this->filters['end_date'] ?? null;

        if ($startDate || $endDate) {
            $parsedStart = null;
            $parsedEnd = null;

            if ($startDate) {
                try {
                    $parsedStart = Carbon::parse($startDate)->startOfDay();
                } catch (\Exception $e) {
                    $parsedStart = null;
                }
            }

            if ($endDate) {
                try {
                    $parsedEnd = Carbon::parse($endDate)->endOfDay();
                } catch (\Exception $e) {
                    $parsedEnd = null;
                }
            }

            // Jika tanggal mulai lebih besar dari selesai, tukar urutan agar query valid
            if ($parsedStart && $parsedEnd && $parsedStart->gt($parsedEnd)) {
                $temp = $parsedStart->copy()->startOfDay();
                $parsedStart = $parsedEnd->copy()->startOfDay();
                $parsedEnd = $temp->copy()->endOfDay();
            }

            if ($parsedStart && $parsedEnd) {
                $query->whereBetween('created_at', [$parsedStart, $parsedEnd]);
            } elseif ($parsedStart) {
                $query->where('created_at', '>=', $parsedStart);
            } elseif ($parsedEnd) {
                $query->where('created_at', '<=', $parsedEnd);
            }
        } else {
            if (!empty($this->filters['tahun'])) {
                $query->whereYear('created_at', $this->filters['tahun']);
            }
            if (!empty($this->filters['bulan'])) {
                $query->whereMonth('created_at', $this->filters['bulan']);
            }
        }

        if (!empty($this->filters['search'])) {
            $query->where('judul', 'like', '%' . $this->filters['search'] . '%');
        }

        return $query->get();
    }

    public function headings(): array
    {
        return [
            'No',
            'Judul Surat',
            'Jenis Surat',
            'Sifat',
            'Tujuan Surat',
            'Nomor Surat',
            'Tgl Pengajuan',
            'Status',
            'Tahap Sekarang',
            'Progress (%)',
        ];
    }

    public function map($surat): array
    {
        $this->rowNumber++;

        return [
            $this->rowNumber,
            $surat->judul,
            $surat->jenis_label,
            ucfirst($surat->sifat),
            $surat->tujuan,
            $surat->nomor_surat ?? '-',
            $surat->created_at ? $surat->created_at->format('d/m/Y H:i') : '-',
            ucfirst($surat->status),
            "Tahap {$surat->tahap_sekarang}/10 — {$surat->nama_tahap}",
            $surat->proses_persen . '%',
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
