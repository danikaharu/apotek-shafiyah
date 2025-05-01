<?php

namespace App\Exports;

use App\Models\Profile;
use App\Models\Purchase;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;

class PurchaseExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithEvents, WithCustomStartCell
{
    use Exportable;

    protected $startDate;
    protected $endDate;

    public function __construct($startDate, $endDate)
    {
        $this->startDate = $startDate;
        $this->endDate = $endDate;
    }

    public function collection()
    {
        $data = Purchase::with('supplier', 'product')->whereBetween('order_date', [$this->startDate, $this->endDate])->get();

        return $data;
    }

    /**
     * @var Purchase $purchase
     */
    public function map($purchase): array
    {
        return [
            $purchase->order_date,
            $purchase->supplier->name,
            $purchase->product->pluck('name')->join(', '),
            $purchase->purchase_number,
            $purchase->detail_purchase->pluck('quantity')->join(', '),
            $purchase->detail_purchase->pluck('total_price')->map(function ($price) {
                return 'Rp ' . number_format($price, 0, ',', '.');
            })->join(', ')
        ];
    }

    public function headings(): array
    {
        $headings = [
            'Tanggal',
            'Supplier',
            'Nama Obat',
            'No SP',
            'Qty',
            'Harga',
        ];

        return $headings;
    }

    public function startCell(): string
    {
        return 'B10';
    }

    /**
     * @return array
     */
    public function registerEvents(): array
    {
        return [
            AfterSheet::class    => function (AfterSheet $event) {
                $profile = Profile::first();

                // Menyisipkan gambar/logo
                $drawing = new \PhpOffice\PhpSpreadsheet\Worksheet\Drawing();
                $drawing->setName('Logo');
                $drawing->setDescription('Logo');
                $drawing->setPath(public_path('template/dist/img/ular.png')); // Sesuaikan path logo
                $drawing->setHeight(90); // Tinggi logo
                $drawing->setCoordinates('C2'); // Lokasi penempatan logo
                $drawing->setWorksheet($event->sheet->getDelegate());

                // Menggabungkan sel untuk heading (kop surat)
                $event->sheet->mergeCells('B7:G7');
                $event->sheet->mergeCells('B9:G9');
                $event->sheet->mergeCells('D2:G2');
                $event->sheet->mergeCells('D3:G3');
                $event->sheet->mergeCells('D4:G4');
                $event->sheet->mergeCells('D6:E6');

                // Menambahkan teks kop surat
                $event->sheet->setCellValue('B7', 'Laporan Pembelian');
                $event->sheet->setCellValue('B9', 'Daftar Obat yang Dibeli');
                $event->sheet->setCellValue('D2', config('app.name'));
                $event->sheet->setCellValue('D3', $profile->address);
                $event->sheet->setCellValue('D4', 'Telp.');

                // Styling kop surat agar teks berada di tengah
                $event->sheet->getStyle('B7:G7')->applyFromArray([
                    'font' => ['size' => 12],
                    'alignment' => [
                        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER, // Rata tengah horizontal
                        'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER, // Rata tengah vertikal
                    ],
                ]);

                $highestColumn = $event->sheet->getHighestColumn();
                $highestRow = $event->sheet->getHighestRow();

                $styleArray = [
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                            'color' => ['argb' => '000000'],
                        ],
                    ],
                    'alignment' => [
                        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                    ],
                ];

                $range = 'B9:' . $highestColumn . $highestRow;
                $event->sheet->getStyle($range)->applyFromArray($styleArray);
            },
        ];
    }
}
