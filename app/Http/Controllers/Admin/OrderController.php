<?php

namespace App\Http\Controllers\Admin;

use App\Exports\OrderExport;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreOrderRequest;
use App\Http\Requests\UpdateOrderRequest;
use App\Models\Cart;
use App\Models\DetailCart;
use App\Models\DetailOrder;
use App\Models\Order;
use App\Models\Product;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\Facades\DataTables;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $orders = Order::with('user')->latest()->get();
        if (request()->ajax()) {
            return DataTables::of($orders)
                ->addIndexColumn()
                ->addColumn('customer', function ($row) {
                    return $row->user && $row->user->customer ? $row->user->customer->full_name : '-';
                })
                ->addColumn('created_at', function ($row) {
                    return Carbon::parse($row->created_at)->format('M d Y');
                })
                ->addColumn('status', function ($row) {
                    return $row->status();
                })
                ->addColumn('total_price', function ($row) {
                    return 'Rp. ' . number_format($row->total_price, 0, ',', '.');
                })
                ->addColumn('action', 'admin.order.include.action')
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('admin.order.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            DB::beginTransaction();

            $customerId = $request->input('customer_id');
            $totalPrice = $request->input('total_price');
            $products = $request->input('products');
            $status = 2;
            $adminId = 1;

            $order = Order::create([
                'admin_id' => $adminId,
                'customer_id' => $customerId,
                'total_price' => $totalPrice,
                'status' => $status
            ]);

            foreach ($products as $productData) {
                $productId = $productData['product_id'];
                $amount = $productData['amount'];
                $price = $productData['price'];
                $discount = $productData['discount'] ?? 0;
                $totalPrice = $productData['total_price'];

                // Ambil produk dari database
                $product = Product::find($productId);

                // Cek apakah stok produk mencukupi
                if ($product->stock < $amount) {
                    return response()->json(['message' => 'Stok produk tidak mencukupi untuk ' . $product->name], 400);
                }

                // Buat detail order untuk setiap produk
                $detailOrder = new DetailOrder([
                    'order_id' => $order->id, // Gunakan order yang sama
                    'product_id' => $productId,
                    'price' => $price,
                    'discount' => $discount,
                    'amount' => $amount,
                    'total_price' => $totalPrice
                ]);
                $detailOrder->save();

                // Kurangi stok produk
                $product->stock -= $amount;
                $product->save();
            }

            // Hapus keranjang setelah proses pembelian selesai
            $cart = Cart::where('customer_id', $customerId)->first();
            if ($cart) {
                DetailCart::where('cart_id', $cart->id)->delete(); // Hapus detail cart
                $cart->delete(); // Hapus cart
            }

            DB::commit();

            return response()->json(['message' => 'Produk berhasil dibayar.']);
        } catch (\Throwable $th) {
            DB::rollback();

            return response()->json(['message' => $th->getMessage()], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Order $order)
    {
        $detailOrders = DetailOrder::where('order_id', $order->id)->get();

        $price = 0;
        $discount = 0;
        $totalPrice = 0;

        foreach ($detailOrders as $detailOrder) {
            $price += $detailOrder->price;
            $discount += $detailOrder->discount;
            $totalPrice += $detailOrder->total_price;
        }

        return view('admin.order.show', compact('order', 'price', 'discount', 'totalPrice'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Order $order)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateOrderRequest $request, Order $order)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Order $order)
    {
        //
    }

    public function approve(Order $order)
    {
        $order->update([
            'status' => 2
        ]);

        return redirect()->back()->with('success', 'Pesanan berhasil diterima');
    }

    public function export(Request $request)
    {
        $startDate = Carbon::parse($request->input('start_date'))->startOfDay();
        $endDate = Carbon::parse($request->input('end_date'))->endOfDay();

        if ($startDate && $endDate) {
            return Excel::download(new OrderExport($startDate, $endDate), 'Laporan Penjualan.xlsx');
        } else {
            return redirect()->back()->with('error', 'Maaf, tidak bisa export data');
        }
    }
}
