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
        $customerId = auth()->user()->id;

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

        if ($detailCart) {
            $detailCart->increment('amount');
            $detailCart->update(['total_price' => $detailCart->amount * $detailCart->price]);
        } else {
            $cart->details()->create([
                'product_id' => $productId,
                'price' => $product->price,
                'discount' => $product->discount->discount ?? 0,
                'amount' => 1,
                'total_price' => $product->price
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
        $cart->total_price = $cart->details ? $cart->details->sum('total_price') : 0;
        $cart->save();
    }


    public function getCartTotal()
    {
        $customerId = auth()->id();
        $cart = Cart::with('details')->where('customer_id', $customerId)->first();

        $totalPrice = $cart ? $cart->details->sum('total_price') : 0;
        $totalItems = $cart ? $cart->details->sum('amount') : 0;

        return response()->json([
            'total_price' => $totalPrice,
            'total_items' => $totalItems
        ]);
    }

    public function getCart()
    {
        $customerId = auth()->id();
        $cart = Cart::where('customer_id', $customerId)->with('details.product')->first();

        $cartHtml = view('layouts.user.include.cart_modal_content', compact('cart'))->render();

        return response()->json([
            'cart_html' => $cartHtml,
        ]);
    }
}
