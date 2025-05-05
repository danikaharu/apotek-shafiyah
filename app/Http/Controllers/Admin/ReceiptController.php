<?php

namespace App\Http\Controllers\Admin;

use App\Exports\ReceiptExport;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreReceiptRequest;
use App\Http\Requests\Admin\UpdateReceiptRequest;
use App\Models\DetailPurchase;
use App\Models\DetailReceipt;
use App\Models\Product;
use App\Models\Receipt;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\Facades\DataTables;

class ReceiptController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if (request()->ajax()) {
            $receipts = Receipt::with('purchase.supplier')->latest()->get();
            return DataTables::of($receipts)
                ->addIndexColumn()
                ->addColumn('action', 'admin.receipt.include.action')
                ->toJson();
        }

        return view('admin.receipt.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create() {}

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreReceiptRequest $request)
    {
        $attr = $request->validated();
        try {
            DB::beginTransaction();

            $receipt = new Receipt();
            $receipt->purchase_id = $attr['purchase_id'];
            $receipt->receipt_date = $attr['receipt_date'];
            $receipt->information = $attr['information'];
            $receipt->save();

            foreach ($attr['product_id'] as $index => $productId) {
                $amountToReceive = $attr['amount'][$index];

                // Ambil detail pembelian produk terkait
                $detailPurchase = DetailPurchase::where('purchase_id', $attr['purchase_id'])
                    ->where('product_id', $productId)
                    ->first();

                if (!$detailPurchase) {
                    throw new \Exception("Produk dengan ID {$productId} tidak ada dalam detail pembelian.");
                }

                // Hitung total yang sudah diterima sebelumnya
                $totalReceived = DetailReceipt::whereHas('receipt', function ($q) use ($attr) {
                    $q->where('purchase_id', $attr['purchase_id']);
                })->where('product_id', $productId)->sum('amount');

                $sisa = $detailPurchase->quantity - $totalReceived;

                if ($sisa <= 0) {
                    throw new \Exception("Produk '{$productId}' sudah diterima sepenuhnya.");
                }

                if ($amountToReceive > $sisa) {
                    throw new \Exception("Jumlah penerimaan produk '{$productId}' melebihi sisa stok pembelian ($sisa).");
                }

                // Simpan detail penerimaan
                DetailReceipt::create([
                    'receipt_id' => $receipt->id,
                    'product_id' => $productId,
                    'amount' => $amountToReceive,
                ]);

                // Update stok produk
                $product = Product::find($productId);
                $product->stock += $amountToReceive;
                $product->save();
            }

            DB::commit();
            return redirect()->back()->with('success', 'Data berhasil ditambah');
        } catch (\Throwable $th) {
            DB::rollback();
            return redirect()->back()->withErrors(['error' => $th->getMessage()]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Receipt $receipt)
    {
        $receipt->load('purchase.detail_purchase', 'detail_receipt.product');
        return view('admin.receipt.show', compact('receipt'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Receipt $receipt)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateReceiptRequest $request, Receipt $receipt) {}

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Receipt $receipt)
    {
        try {
            $receipt->detail_receipt()->delete();
            $receipt->delete();

            return redirect()->back();
        } catch (\Throwable $th) {
            dd($th->getMessage());
        }
    }

    public function print(Receipt $receipt)
    {
        $receipt->load('purchase', 'detail_receipt');
        return view('admin.receipt.print', compact('receipt'));
    }

    public function export(Request $request)
    {
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        if ($startDate && $endDate) {
            return Excel::download(new ReceiptExport($startDate, $endDate), 'Laporan Penerimaan.xlsx');
        } else {
            return redirect()->back()->with('error', 'Maaf, tidak bisa export data');
        }
    }
}
