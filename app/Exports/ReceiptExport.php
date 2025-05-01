<?php

namespace App\Exports;

use App\Models\DetailReceipt;
use App\Models\Profile;
use App\Models\Receipt;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;

class ReceiptExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithEvents, WithCustomStartCell
{
    use Exportable;

    protected $startDate;
    protected $endDate;
    protected $row;

    public function __construct($startDate, $endDate)
    {
        $this->startDate = $startDate;
        $this->endDate = $endDate;
    }

    public function model(array $row)
    {
        ++$this->row;
    }

    public function collection()
    {
        $data = DetailReceipt::with(['receipt.purchase.detail_purchase', 'product.unit'])
            ->whereHas('receipt', function ($query) {
                $query->whereBetween('receipt_date', [$this->startDate, $this->endDate]);
            })->get();

        return $data;
    }

    /**
     * @var DetailReceipt $detailReceipt
     */
    public function map($detailReceipt): array
    {
        $data = [];

        // Akses semua purchases yang terkait dengan receipt
        foreach ($detailReceipt->receipt->purchase->detail_purchase as $purchase) {
            $data[] = [
                ++$this->row, // Nomor urut baris
                $detailReceipt->product->name ?? '', // Nama Obat
                $detailReceipt->product->batch_number ?? '', // No Batch
                $detailReceipt->product->unit->name ?? '', // Kemasan
                $purchase->quantity ?? '', // Qty Dipesan
                $detailReceipt->amount, // Qty Diterima
                $purchase->expired_date ?? '', // Tanggal Kadaluwarsa
                'Rp ' . number_format($purchase->price, 0, ',', '.'), // Harga per Unit
                'Rp ' . number_format($purchase->total_price, 0, ',', '.'), // Total Harga
            ];
        }

        return $data;
    }

    public function headings(): array
    {
        $headings = [
            'No',
            'Nama Obat',
            'No Batch',
            'Kemasan',
            'Qty Dipesan',
            'Qty Diterima',
            'Tanggal Kadaluwarsa',
            'Harga per Unit',
            'Total Harga',
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
                $drawing->setPath(asset('template/dist/img/ular.png')); // Sesuaikan path logo
                $drawing->setHeight(90); // Tinggi logo
                $drawing->setCoordinates('C2'); // Lokasi penempatan logo
                $drawing->setWorksheet($event->sheet->getDelegate());

                // Menggabungkan sel untuk heading (kop surat)
                $event->sheet->mergeCells('B7:J7');
                $event->sheet->mergeCells('B9:J9');
                $event->sheet->mergeCells('D2:G2');
                $event->sheet->mergeCells('D3:G3');
                $event->sheet->mergeCells('D4:G4');

                // Menambahkan teks kop surat
                $event->sheet->setCellValue('B7', 'Laporan Penerimaan');
                $event->sheet->setCellValue('B9', 'Daftar Penerimaan Obat');
                $event->sheet->setCellValue('D2', config('app.name'));
                $event->sheet->setCellValue('D3', $profile->address);
                $event->sheet->setCellValue('D4', 'Telp.');

                // Styling kop surat agar teks berada di tengah
                $event->sheet->getStyle('B7:J7')->applyFromArray([
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
