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
    protected $totalDiscount = 0;
    protected $totalLoyaltyDiscount = 0;

    public function __construct($startDate, $endDate)
    {
        $this->startDate = $startDate;
        $this->endDate   = $endDate;
    }

    public function collection()
    {
        return DetailOrder::with(['product.purchases', 'product.type', 'order.detail_order'])
            ->whereHas('order', function ($query) {
                $query->whereBetween('created_at', [$this->startDate, $this->endDate]);
            })->get();
    }

    public function map($detailOrder): array
    {
        $purchase   = $detailOrder->product->purchases->sortByDesc('order_date')->first();
        $buyPrice   = $purchase?->pivot->price ?? 0;
        $sellPrice  = $detailOrder->product->price ?? 0;
        $amount     = $detailOrder->amount;
        $order      = $detailOrder->order;

        // Subtotal per item
        $subtotalBuy  = $buyPrice * $amount;
        $subtotalSell = $sellPrice * $amount;

        // Hitung total penjualan order (untuk distribusi diskon)
        $orderSubtotalSell = $order->detail_order->sum(function ($d) {
            return ($d->product->price ?? 0) * $d->amount;
        });

        // Distribusi diskon proporsional
        $discountShare = $orderSubtotalSell > 0
            ? ($subtotalSell / $orderSubtotalSell) * ($order->discount_amount ?? 0)
            : 0;

        $loyaltyDiscountShare = $orderSubtotalSell > 0
            ? ($subtotalSell / $orderSubtotalSell) * ($order->loyalty_discount_amount ?? 0)
            : 0;

        // Profit bersih
        $profit = ($subtotalSell - $subtotalBuy) - $discountShare - $loyaltyDiscountShare;

        // Akumulasi ke total
        $this->totalBuyPrice        += $subtotalBuy;
        $this->totalSellPrice       += $subtotalSell;
        $this->totalProfit          += $profit;
        $this->totalDiscount        += $discountShare;
        $this->totalLoyaltyDiscount += $loyaltyDiscountShare;

        return [
            $order->invoice_number ?? '',
            $detailOrder->created_at?->format('Y-m-d') ?? '',
            $detailOrder->product->name ?? '',
            $detailOrder->product->type->name ?? '',
            $amount,
            'Rp ' . number_format($buyPrice, 0, ',', '.'),
            'Rp ' . number_format($sellPrice, 0, ',', '.'),
            'Rp ' . number_format($subtotalBuy, 0, ',', '.'),
            'Rp ' . number_format($subtotalSell, 0, ',', '.'),
            'Rp ' . number_format($discountShare, 0, ',', '.'),
            'Rp ' . number_format($loyaltyDiscountShare, 0, ',', '.'),
            'Rp ' . number_format($profit, 0, ',', '.'),
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
            'Subtotal Beli',
            'Subtotal Jual',
            'Diskon',
            'Diskon Loyalti',
            'Profit (Setelah Diskon)',
        ];
    }

    public function startCell(): string
    {
        return 'B11'; // heading mulai baris 11 supaya rapi di bawah kop
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet    = $event->sheet;
                $delegate = $sheet->getDelegate();
                $profile  = Profile::first();

                // Logo
                $drawing = new Drawing();
                $drawing->setName('Logo');
                $drawing->setDescription('Logo');
                $drawing->setPath(public_path('template/dist/img/ular.png'));
                $drawing->setHeight(90);
                $drawing->setCoordinates('C2');
                $drawing->setWorksheet($delegate);

                // Kop
                $sheet->mergeCells('B7:M7');
                $sheet->mergeCells('B9:M9');
                $sheet->mergeCells('D2:G2');
                $sheet->mergeCells('D3:G3');
                $sheet->mergeCells('D4:G4');

                $sheet->setCellValue('B7', 'Laporan Penjualan');
                $sheet->setCellValue('B9', 'Daftar Penjualan Obat');
                $sheet->setCellValue('D2', config('app.name'));
                $sheet->setCellValue('D3', $profile->address ?? '-');
                $sheet->setCellValue('D4', 'Telp.');

                $sheet->getStyle('B7:M7')->applyFromArray([
                    'font' => ['size' => 12, 'bold' => true],
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_CENTER,
                        'vertical'   => Alignment::VERTICAL_CENTER,
                    ],
                ]);

                // Styling tabel
                $highestColumn = $sheet->getHighestColumn();
                $highestRow    = $sheet->getHighestRow();

                $styleArray = [
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => Border::BORDER_THIN,
                            'color'       => ['argb' => '000000'],
                        ],
                    ],
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_CENTER,
                    ],
                ];

                $sheet->getStyle("B11:{$highestColumn}{$highestRow}")->applyFromArray($styleArray);

                // Footer totals
                $totalRow = $highestRow + 2;

                $sheet->setCellValue("B{$totalRow}", 'Total Harga Beli');
                $sheet->setCellValue("I{$totalRow}", 'Rp ' . number_format($this->totalBuyPrice, 0, ',', '.'));

                $totalRow++;
                $sheet->setCellValue("B{$totalRow}", 'Total Seluruh Penjualan');
                $sheet->setCellValue("J{$totalRow}", 'Rp ' . number_format($this->totalSellPrice, 0, ',', '.'));

                $totalRow++;
                $sheet->setCellValue("B{$totalRow}", 'Total Diskon');
                $sheet->setCellValue("K{$totalRow}", 'Rp ' . number_format($this->totalDiscount, 0, ',', '.'));

                $totalRow++;
                $sheet->setCellValue("B{$totalRow}", 'Total Diskon Loyalti');
                $sheet->setCellValue("L{$totalRow}", 'Rp ' . number_format($this->totalLoyaltyDiscount, 0, ',', '.'));

                $totalRow++;
                $sheet->setCellValue("B{$totalRow}", 'Total Laba Bersih Setelah Diskon');
                $sheet->setCellValue("M{$totalRow}", 'Rp ' . number_format($this->totalProfit, 0, ',', '.'));

                // Styling footer
                $sheet->getStyle("B" . ($totalRow - 4) . ":{$highestColumn}{$totalRow}")->applyFromArray([
                    'font' => ['bold' => true],
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => Border::BORDER_THIN,
                            'color'       => ['argb' => '000000'],
                        ],
                    ],
                ]);
            },
        ];
    }
}
