<?php

namespace App\Exports;

use App\Http\Controllers\Admin\LaporanAdminController;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;

class Laporan2Export implements FromArray, WithStyles, WithTitle, ShouldAutoSize
{
    public function __construct(private array $filters) {}

    public function array(): array
    {
        $controller = new LaporanAdminController();
        $data       = $controller->getLaporan2Data($this->filters);

        $rows   = [];
        $rows[] = ["LAPORAN 2 - DETAIL PENELITIAN DAN CAPAIAN LUARAN"];
        $rows[] = ["Dicetak: " . now()->format('d/m/Y H:i')];
        $rows[] = [];
        $rows[] = [
            'No',
            'Tahun',
            'Judul',
            'Ketua Peneliti',
            'Jurusan Ketua',
            'Anggota Tim',
            'Jenis',
            'Jalur',
            'Skema',
            'Luaran Diusulkan',
            'Luaran Tercapai',
            'Status',
        ];

        $no         = 1;
        $kolomTahun = $this->filters['kolomTahun'] ?? 'tahun_pengajuan';

        foreach ($data as $p) {
            // Anggota tim
            $anggotaNama = $p->anggotas
                ->map(fn($a) => $a->pegawai?->nama ?? '-')
                ->filter()
                ->implode(', ');

            // Helper konversi luaran ke string
            $toLuaranStr = function ($val): string {
                if (is_null($val))   return '-';
                if (is_string($val)) return $val;
                if (is_array($val)) {
                    return implode(', ', array_map(function ($item) {
                        if (is_array($item)) {
                            return $item['nama']
                                ?? $item['jenis']
                                ?? $item['judul']
                                ?? json_encode($item);
                        }
                        return (string) $item;
                    }, $val));
                }
                return (string) $val;
            };

            $lk              = $p->laporanKemajuan;
            $luaranDiusulkan = '-';
            $luaranTercapai  = '-';

            if ($lk) {
                $luaranDiusulkan = $toLuaranStr(
                    $lk->luaran_diusulkan
                    ?? $lk->target_luaran
                    ?? $lk->luaran
                    ?? null
                );
                $luaranTercapai = $toLuaranStr(
                    $lk->luaran_tercapai
                    ?? $lk->capaian_luaran
                    ?? $lk->capaian
                    ?? null
                );
            }

            // Nilai tahun
            $tahunVal = $p->{$kolomTahun} ?? $p->created_at?->year ?? '-';

            $rows[] = [
                $no++,
                $tahunVal,
                $p->judul,
                $p->pegawai?->nama ?? '-',
                $p->pegawai?->jurusan ?? '-',
                $anggotaNama ?: '-',
                ucfirst($p->jenis ?? '-'),
                ucfirst($p->jalur ?? '-'),
                $p->skema?->nama ?? '-',
                $luaranDiusulkan,
                $luaranTercapai,
                ucfirst(str_replace('_', ' ', $p->status ?? '-')),
            ];
        }

        return $rows;
    }

    // ✅ Return type harus ?array bukan void
    public function styles(Worksheet $sheet): ?array
    {
        $lastRow = $sheet->getHighestRow();

        // Merge title
        $sheet->mergeCells('A1:L1');
        $sheet->mergeCells('A2:L2');

        // Style judul
        $sheet->getStyle('A1')->applyFromArray([
            'font' => [
                'bold'  => true,
                'size'  => 13,
                'color' => ['rgb' => '1A5276'],
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
            ],
        ]);

        // Style header tabel (baris 4)
        $sheet->getStyle('A4:L4')->applyFromArray([
            'font' => [
                'bold'  => true,
                'color' => ['rgb' => 'FFFFFF'],
            ],
            'fill' => [
                'fillType'   => Fill::FILL_SOLID,
                'startColor' => ['rgb' => '1A5276'],
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
            ],
        ]);

        // Border
        if ($lastRow >= 4) {
            $sheet->getStyle("A4:L{$lastRow}")->applyFromArray([
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => Border::BORDER_THIN,
                        'color'       => ['rgb' => 'CCCCCC'],
                    ],
                ],
            ]);
        }

        // Wrap text kolom panjang
        if ($lastRow >= 5) {
            $sheet->getStyle("C5:L{$lastRow}")
                ->getAlignment()
                ->setWrapText(true);
        }

        // Lebar kolom manual
        $sheet->getColumnDimension('C')->setWidth(40); // Judul
        $sheet->getColumnDimension('J')->setWidth(35); // Luaran diusulkan
        $sheet->getColumnDimension('K')->setWidth(35); // Luaran tercapai

        // Warna selang-seling
        for ($row = 5; $row <= $lastRow; $row++) {
            if ($row % 2 === 0) {
                $sheet->getStyle("A{$row}:L{$row}")->applyFromArray([
                    'fill' => [
                        'fillType'   => Fill::FILL_SOLID,
                        'startColor' => ['rgb' => 'F4F6F7'],
                    ],
                ]);
            }
        }

        return null;
    }

    public function title(): string
    {
        return 'Laporan 2';
    }
}