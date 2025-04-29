<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\DetailOrder;
use App\Models\Order;
use App\Models\Profile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Midtrans\Snap;
use Midtrans\Config;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::where('customer_id', auth()->user()->id)
            ->with('detail_order')
            ->latest()
            ->get();

        return view('user.history', compact('orders'));
    }

    public function store(Request $request)
    {
        try {
            DB::beginTransaction();

            $customerId = auth()->user()->id;
            $total_price = (int) $request->total_price; // atau bisa juga dihitung ulang dari cart
            $cart = Cart::where('customer_id', $customerId)->with('details')->first();

            if (!$cart || $cart->details->isEmpty()) {
                return response()->json([
                    'message' => 'Cart kosong, tidak bisa checkout.'
                ], 400);
            }

            // Buat order baru
            $order = Order::create([
                'admin_id' => 1,
                'customer_id' => $customerId,
                'total_price' => $cart->total_price,
                'status' => 4, // Menunggu pembayaran
            ]);

            // Insert semua detail order
            foreach ($cart->details as $detail) {
                DetailOrder::create([
                    'order_id' => $order->id,
                    'product_id' => $detail->product_id,
                    'price' => $detail->price,
                    'discount' => $detail->discount,
                    'amount' => $detail->amount,
                    'total_price' => $detail->total_price,
                ]);
            }

            // Hapus Cart setelah checkout
            $cart->details()->delete();
            $cart->delete();

            // Midtrans
            Config::$serverKey = env('MIDTRANS_SERVER_KEY');
            Config::$isProduction = false;
            Config::$isSanitized = true;
            Config::$is3ds = true;

            $params = [
                'transaction_details' => [
                    'order_id' => 'ORDER-' . $order->id . '-' . time(),
                    'gross_amount' => $order->total_price,
                ],
                'customer_details' => [
                    'first_name' => auth()->user()->name,
                    'email' => auth()->user()->email,
                ],
            ];

            $snapToken = Snap::getSnapToken($params);

            DB::commit();

            return response()->json([
                'snap_token' => $snapToken,
                'order_id' => $order->id
            ]);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json([
                'message' => $th->getMessage()
            ], 500);
        }
    }

    public function cancel(Order $order)
    {
        $order->update([
            'status' => 3
        ]);

        return redirect()->back();
    }

    public function note(Order $order)
    {
        $detailOrders = DetailOrder::where('order_id', $order->id)->get();
        $profile = Profile::first();

        $price = 0;
        $discount = 0;
        $totalPrice = 0;

        foreach ($detailOrders as $detailOrder) {
            $price += $detailOrder->price;
            $discount += $detailOrder->discount;
            $totalPrice += $detailOrder->total_price;
        }

        return view('user.order-note', compact('order', 'price', 'discount', 'totalPrice', 'profile'));
    }

    public function selfPickup(Request $request)
    {
        $order = Order::find($request->order_id);
        $order->status = 2;
        $order->save();
        return redirect()->route('invoice.download', ['order_id' => $order->id]); // Redirect to invoice page
    }

    public function orderMaxim(Request $request)
    {
        $order = Order::find($request->order_id);
        $order->status = 2;
        $order->save();
        return redirect('https://wa.me/082393232710'); // Redirect to WhatsApp Admin
    }
}
