<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\DetailOrder;
use App\Models\Order;
use App\Models\Profile;
use App\Services\MemberLevelService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Midtrans\Snap;
use Midtrans\Config;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::where('customer_id', auth()->user()->id)
            ->where('status', 2)
            ->with('detail_order')
            ->latest()
            ->get();

        return view('user.history', compact('orders'));
    }

    public function store(Request $request)
    {
        try {
            DB::beginTransaction();

            $user = auth()->user();
            $customer = $user->customer;
            $customerId = $user->id;

            $cart = Cart::where('customer_id', $customerId)
                ->with('details.product')
                ->first();

            if (!$cart || $cart->details->isEmpty()) {
                return response()->json([
                    'message' => 'Cart kosong, tidak bisa checkout.'
                ], 400);
            }

            // Validasi stok
            foreach ($cart->details as $detail) {
                $product = $detail->product;
                if (!$product || $product->stock < $detail->amount) {
                    DB::rollBack();
                    return response()->json([
                        'message' => "Stok produk {$product->name} hanya tersedia {$product->stock}."
                    ], 400);
                }
            }

            // Subtotal dari cart (sudah termasuk diskon produk per item)
            $subtotal = $cart->details->sum('total_price');

            // === DISKON MEMBER LEVEL ===
            $memberDiscountPercent = $customer->memberLevel->discount_percent ?? 0;
            $memberDiscountAmount = ($memberDiscountPercent / 100) * $subtotal;

            // === DISKON LOYALITAS ===
            $completedOrders = Order::where('customer_id', $customer->id)
                ->where('status', 6) // Status 6 = selesai
                ->count();

            $loyaltyDiscountPercent = floor($completedOrders / 5) * 5; // 5% tiap kelipatan 5 order
            if ($loyaltyDiscountPercent > 15) $loyaltyDiscountPercent = 15; // optional batas maksimal
            $loyaltyDiscountAmount = ($loyaltyDiscountPercent / 100) * $subtotal;

            // === TOTAL ===
            $finalTotal = $subtotal - $memberDiscountAmount - $loyaltyDiscountAmount;

            // Simpan order
            $order = Order::create([
                'admin_id' => 1,
                'customer_id' => $customerId,
                'total_price' => $finalTotal,
                'discount_percent' => $memberDiscountPercent,
                'discount_amount' => $memberDiscountAmount,
                'loyalty_discount_percent' => $loyaltyDiscountPercent,
                'loyalty_discount_amount' => $loyaltyDiscountAmount,
                'status' => 4, // Menunggu pembayaran
            ]);

            // Simpan detail + update stok
            foreach ($cart->details as $detail) {
                $product = $detail->product;
                $product->stock -= $detail->amount;
                $product->save();

                DetailOrder::create([
                    'order_id' => $order->id,
                    'product_id' => $detail->product_id,
                    'price' => $detail->price,
                    'discount' => $detail->discount,
                    'amount' => $detail->amount,
                    'total_price' => $detail->total_price,
                ]);
            }

            // Hapus keranjang
            $cart->details()->delete();
            $cart->delete();

            // === MIDTRANS ===
            \Midtrans\Config::$serverKey = env('MIDTRANS_SERVER_KEY');
            \Midtrans\Config::$isProduction = false;
            \Midtrans\Config::$isSanitized = true;
            \Midtrans\Config::$is3ds = true;

            $params = [
                'transaction_details' => [
                    'order_id' => 'ORDER-' . $order->id . '-' . time(),
                    'gross_amount' => $finalTotal,
                ],
                'customer_details' => [
                    'first_name' => $user->name,
                    'email' => $user->email,
                ],
            ];

            $snapToken = \Midtrans\Snap::getSnapToken($params);

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

        $customer = $order->customer;
        MemberLevelService::upgradeLevel($customer);
        return redirect()->route('dashboard');
    }

    public function orderMaxim(Request $request)
    {
        $order = Order::find($request->order_id);
        $order->status = 2;
        $order->save();

        $customer = $order->customer;
        MemberLevelService::upgradeLevel($customer);
        return redirect('https://wa.me/082393232710'); // Redirect to WhatsApp Admin
    }
}
