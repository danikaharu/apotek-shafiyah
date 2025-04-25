<?php

namespace App\Exports;

use App\Models\DetailOrder;
use App\Models\Profile;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;

class OrderExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithEvents, WithCustomStartCell
{
    use Exportable;

    protected $startDate;
    protected $endDate;
    protected $totalBuyPrice = 0;
    protected $totalSellPrice = 0;
    protected $totalProfit = 0;
    protected $dailyProfit = 0;
    protected $loss = 0;

    public function __construct($startDate, $endDate)
    {
        $this->startDate = $startDate;
        $this->endDate = $endDate;
    }

    public function collection()
    {
        return DetailOrder::with(['product'])
            ->whereHas('order', function ($query) {
                $query->whereBetween('created_at', [$this->startDate, $this->endDate]);
            })->get();
    }

    public function map($detailOrder): array
    {
        $buyPrice = 0; // Ubah jika ingin ambil dari purchase
        $sellPrice = $detailOrder->product->price ?? 0;
        $amount = $detailOrder->amount;

        // Hitung total untuk rekap
        $this->totalBuyPrice += $buyPrice;
        $this->totalSellPrice += $sellPrice;
        $this->totalProfit += ($sellPrice - $buyPrice);

        if ($sellPrice >= $buyPrice) {
            $this->dailyProfit += ($sellPrice - $buyPrice) * $amount;
        } else {
            $this->loss += ($buyPrice - $sellPrice) * $amount;
        }

        return [
            $detailOrder->order->invoice_number ?? '',
            $detailOrder->created_at->format('Y-m-d') ?? '',
            $detailOrder->product->name ?? '',
            $detailOrder->product->type->name ?? '',
            $amount,
            'Rp ' . number_format($buyPrice, 0, ',', '.'),
            'Rp ' . number_format($sellPrice, 0, ',', '.'),
        ];
    }

    public function headings(): array
    {
        return [
            'Kode Invoice',
            'Tanggal',
            'Nama Obat',
            'Jenis Obat',
            'Qty',
            'Harga Beli',
            'Harga Jual',
        ];
    }

    public function startCell(): string
    {
        return 'B10';
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet;
                $delegate = $sheet->getDelegate();
                $profile = Profile::first();

                // Logo
                $drawing = new Drawing();
                $drawing->setName('Logo');
                $drawing->setDescription('Logo');
                $drawing->setPath(public_path('template/dist/img/logo ular.png'));
                $drawing->setHeight(90);
                $drawing->setCoordinates('C2');
                $drawing->setWorksheet($delegate);

                // Kop Surat
                $sheet->mergeCells('B7:H7');
                $sheet->mergeCells('B9:H9');
                $sheet->mergeCells('D2:G2');
                $sheet->mergeCells('D3:G3');
                $sheet->mergeCells('D4:G4');

                $sheet->setCellValue('B7', 'Laporan Penjualan');
                $sheet->setCellValue('B9', 'Daftar Penjualan Obat');
                $sheet->setCellValue('D2', config('app.name'));
                $sheet->setCellValue('D3', $profile->address);
                $sheet->setCellValue('D4', 'Telp.');

                $sheet->getStyle('B7:H7')->applyFromArray([
                    'font' => ['size' => 12],
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_CENTER,
                        'vertical' => Alignment::VERTICAL_CENTER,
                    ],
                ]);

                // Styling tabel utama
                $highestColumn = $sheet->getHighestColumn();
                $highestRow = $sheet->getHighestRow();

                $styleArray = [
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => Border::BORDER_THIN,
                            'color' => ['argb' => '000000'],
                        ],
                    ],
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_CENTER,
                    ],
                ];

                $sheet->getStyle("B9:{$highestColumn}{$highestRow}")->applyFromArray($styleArray);

                // Tambahkan baris total dan keuntungan/kerugian
                $totalRow = $highestRow + 1;
                $sheet->setCellValue("B{$totalRow}", 'Total Harga Beli');
                $sheet->setCellValue("G{$totalRow}", 'Rp ' . number_format($this->totalBuyPrice, 0, ',', '.'));

                $totalRow++;
                $sheet->setCellValue("B{$totalRow}", 'Total Seluruh Penjualan');
                $sheet->setCellValue("H{$totalRow}", 'Rp ' . number_format($this->totalSellPrice, 0, ',', '.'));

                $totalRow++;
                $sheet->setCellValue("B{$totalRow}", 'Total Laba Bersih');
                $sheet->setCellValue("G{$totalRow}", 'Rp ' . number_format($this->totalProfit, 0, ',', '.'));

                $totalRow++;
                $sheet->mergeCells("B{$totalRow}:F{$totalRow}");
                $sheet->setCellValue("B{$totalRow}", 'Keuntungan Harian');
                $sheet->mergeCells("G{$totalRow}:H{$totalRow}");
                $sheet->setCellValue("G{$totalRow}", 'Rp ' . number_format($this->dailyProfit, 0, ',', '.'));

                $totalRow++;
                $sheet->mergeCells("B{$totalRow}:F{$totalRow}");
                $sheet->setCellValue("B{$totalRow}", 'Kerugian');
                $sheet->mergeCells("G{$totalRow}:H{$totalRow}");
                $sheet->setCellValue("G{$totalRow}", 'Rp ' . number_format($this->loss, 0, ',', '.'));

                // Styling total
                $sheet->getStyle("B" . ($totalRow - 4) . ":{$highestColumn}{$totalRow}")->applyFromArray([
                    'font' => ['bold' => true],
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => Border::BORDER_THIN,
                            'color' => ['argb' => '000000'],
                        ],
                    ],
                ]);
            },
        ];
    }
}
