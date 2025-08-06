<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\DetailCart;
use App\Models\Product;
use Illuminate\Http\Request;

class CartController extends Controller
{

    public function add(Request $request)
    {
        $productId = $request->input('product_id');
        $adminId = 1;
        $customerId = auth()->user()->customer->id;

        $product = Product::findOrFail($productId);

        $cart = Cart::firstOrCreate(
            ['customer_id' => $customerId],
            ['admin_id' => $adminId, 'total_price' => 0]
        );

        $detailCart = $cart->details()->where('product_id', $productId)->first();

        $currentAmount = $detailCart ? $detailCart->amount : 0;
        $newAmount = $currentAmount + 1;

        if ($newAmount > $product->stock) {
            return response()->json([
                'success' => false,
                'message' => 'Jumlah melebihi stok tersedia.'
            ], 400);
        }

        // Cek apakah produk memiliki diskon
        $discountAmount = 0;
        $finalPrice = $product->price;

        if ($product->discount) {
            $discountAmount = $product->discount->discount_amount;
        }

        // Hitung harga final setelah diskon
        $finalPrice = $product->price - $discountAmount;

        // Update atau buat detail produk dalam keranjang
        if ($detailCart) {
            $detailCart->increment('amount');
            $detailCart->update(['total_price' => $detailCart->amount * $finalPrice]);
        } else {
            $cart->details()->create([
                'product_id' => $productId,
                'price' => $finalPrice,  // Simpan harga setelah diskon
                'discount' => $discountAmount,
                'amount' => 1,
                'total_price' => $finalPrice  // Total harga untuk satu item
            ]);
        }

        $cart->refresh();
        $this->updateCartTotal($cart);

        if ($request->ajax()) {
            $cartHtml = view('layouts.user.include.cart_modal_content', ['cart' => $cart])->render();

            return response()->json([
                'success' => true,
                'total_price' => $cart->total_price,
                'total_items' => $cart->details->sum('amount'),
                'cart_html' => $cartHtml,
                'cart_total_price_formatted' => 'Rp ' . number_format($cart->total_price, 0, ',', '.'),
            ]);
        }

        return redirect()->back();
    }



    public function updateQuantity(Request $request, $cartItemId)
    {
        $cartItem = DetailCart::findOrFail($cartItemId);
        $newQuantity = (int) $request->input('quantity');

        $product = Product::findOrFail($cartItem->product_id);

        if ($newQuantity < 1) {
            return response()->json([
                'status' => 'error',
                'message' => 'Jumlah tidak valid.'
            ], 400);
        }

        if ($newQuantity > $product->stock) {
            return response()->json([
                'status' => 'error',
                'message' => 'Stok tidak mencukupi. Maksimum stok tersedia: ' . $product->stock
            ], 400);
        }

        $cartItem->amount = $newQuantity;
        $cartItem->total_price = $cartItem->price * $newQuantity;
        $cartItem->save();

        $cart = Cart::find($cartItem->cart_id);
        $this->updateCartTotal($cart);

        return response()->json([
            'status' => 'success',
            'message' => 'Jumlah item diperbarui.',
            'total_price' => number_format($cart->total_price, 0, ',', '.'),
            'item_total' => number_format($cartItem->total_price, 0, ',', '.')
        ]);
    }

    // Method to remove an item from the cart
    public function removeItem($cartItemId)
    {
        $cartItem = DetailCart::findOrFail($cartItemId);
        $cart = Cart::find($cartItem->cart_id);

        // Remove the item and update the cart's total price
        $cart->total_price -= $cartItem->total_price;
        $cart->save();

        $cartItem->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Item berhasil dihapus.',
            'total_price' => number_format($cart->total_price, 0, ',', '.')
        ]);
    }

    // Method to update the total price of the cart
    private function updateCartTotal($cart)
    {
        $total = $cart->details ? $cart->details->sum('total_price') : 0;
        $customer = auth()->user()->customer;

        // Diskon member berdasarkan level
        $memberDiscountPercent = $customer->member_discount ?? 0;
        $memberDiscount = ($memberDiscountPercent / 100) * $total;

        // Diskon loyalitas 5% setiap 5 transaksi selesai
        $loyaltyDiscountPercent = floor($customer->finished_orders_count / 5) * 5;
        if ($loyaltyDiscountPercent > 0) {
            $loyaltyDiscount = ($loyaltyDiscountPercent / 100) * ($total - $memberDiscount);
        } else {
            $loyaltyDiscount = 0;
        }

        $finalTotal = $total - $memberDiscount - $loyaltyDiscount;

        $cart->total_price = $finalTotal;
        $cart->save();
    }


    public function getCartTotal()
    {
        $customer = auth()->user()->customer;
        $cart = Cart::with(['details.product.discount'])->where('customer_id', $customer->id)->first();

        $subtotal = 0;
        $productDiscount = 0;

        foreach ($cart?->details ?? [] as $item) {
            $price = $item->product->price;
            $amount = $item->amount;
            $productDiscountNominal = $item->product->discount?->discount_amount ?? 0;

            $subtotal += $price * $amount;
            $productDiscount += $productDiscountNominal * $amount;
        }

        $afterProductDiscount = $subtotal - $productDiscount;

        // Diskon Member
        $memberDiscountPercent = $customer->member_discount ?? 0;
        $memberDiscount = ($memberDiscountPercent / 100) * $afterProductDiscount;

        // Diskon Loyalitas (5% setiap 5 transaksi)
        $loyaltyDiscountPercent = floor($customer->finished_orders_count / 5) * 5;
        $loyaltyDiscount = ($loyaltyDiscountPercent / 100) * ($afterProductDiscount - $memberDiscount);

        $grandTotal = $afterProductDiscount - $memberDiscount - $loyaltyDiscount;

        return response()->json([
            'subtotal' => number_format($subtotal, 0, ',', '.'),
            'product_discount' => number_format($productDiscount, 0, ',', '.'),
            'member_discount' => number_format($memberDiscount, 0, ',', '.'),
            'loyalty_discount' => number_format($loyaltyDiscount, 0, ',', '.'),
            'total' => number_format($grandTotal, 0, ',', '.'),
            'total_price' => $grandTotal,
            'total_items' => $cart?->details->sum('amount') ?? 0
        ]);
    }

    public function getCart()
    {
        $customerId = auth()->user()->customer->id;
        $cart = Cart::where('customer_id', $customerId)->with('details.product')->first();

        $cartHtml = view('layouts.user.include.cart_modal_content', compact('cart'))->render();

        return response()->json([
            'cart_html' => $cartHtml,
        ]);
    }
}
