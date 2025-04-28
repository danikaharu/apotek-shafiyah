<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Midtrans\Notification;

class MidtransController extends Controller
{
    public function callback(Request $request)
    {
        try {
            $notif = new Notification();

            $transactionStatus = $notif->transaction_status;
            $paymentType = $notif->payment_type;
            $orderId = $notif->order_id;
            $fraudStatus = $notif->fraud_status;

            $realOrderId = explode('-', $orderId)[1] ?? null;

            if (!$realOrderId) {
                return response()->json(['message' => 'Order ID tidak valid'], 400);
            }

            $order = Order::find($realOrderId);

            if (!$order) {
                return response()->json(['message' => 'Order tidak ditemukan'], 404);
            }

            if ($transactionStatus == 'capture') {
                if ($paymentType == 'credit_card') {
                    if ($fraudStatus == 'challenge') {
                        $order->status = 5; // Challenge (Perlu Verifikasi Manual)
                    } else {
                        $order->status = 1; // Lunas
                    }
                }
            } else if ($transactionStatus == 'settlement') {
                $order->status = 1; // Lunas
            } else if ($transactionStatus == 'pending') {
                $order->status = 4; // Menunggu Pembayaran
            } else if ($transactionStatus == 'deny' || $transactionStatus == 'expire' || $transactionStatus == 'cancel') {
                $order->status = 3; // Gagal
            }

            $order->save();

            return response()->json(['message' => 'Notifikasi diproses'], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => 'Error: ' . $th->getMessage()
            ], 500);
        }
    }

    public function success($order_id)
    {
        $order = Order::find($order_id);

        // Pastikan order ada
        if (!$order) {
            return redirect()->route('order.error')->with('error', 'Order tidak ditemukan.');
        }

        $order->status = 1;
        $order->save();

        return view('user.payment.success', compact('order'));
    }

    public function pending()
    {
        return view('user.payment.pending');
    }

    public function failed()
    {
        return view('user.payment.failed');
    }
}
