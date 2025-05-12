<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCartRequest;
use App\Http\Requests\UpdateCartRequest;
use App\Models\Cart;
use App\Models\DetailCart;
use App\Models\Product;
use Illuminate\Http\Request;

class CartController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
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
        $request->validate([
            'product_id' => 'required|exists:products,id',
        ]);

        $productId = $request->input('product_id');
        $customerId = $request->input('customer_id');

        $adminId = 1;

        $cart = Cart::firstOrCreate(
            ['customer_id' => $customerId],
            ['admin_id' => $adminId, 'total_price' => 0]
        );

        $product = Product::with('discount')->findOrFail($productId);
        $detailCart = DetailCart::where('cart_id', $cart->id)
            ->where('product_id', $productId)
            ->first();

        if ($detailCart) {
            $detailCart->amount++;
            $detailCart->total_price = $detailCart->amount * $detailCart->price;
            $detailCart->save();
        } else {
            $detailCart = new DetailCart([
                'cart_id' => $cart->id,
                'product_id' => $productId,
                'price' => $product->price,
                'discount' => $product->discount->discount_amount ?? 0,
                'amount' => 1,
                'total_price' => $product->price
            ]);
            $detailCart->save();
        }

        $cartTotalPrice = DetailCart::where('cart_id', $cart->id)->sum('total_price');
        $cart->update(['total_price' => $cartTotalPrice]);

        $detailCart->load('product');

        return response()->json(['message' => 'Produk berhasil ditambahkan ke keranjang belanja.', 'detail' => $detailCart]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Cart $cart)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Cart $cart)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCartRequest $request, Cart $cart)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Cart $cart)
    {
        $detailCart = DetailCart::where('cart_id', $cart->id)
            ->where('product_id', request()->input('product_id'))
            ->first();

        $newTotalPrice = $cart->total_price - $detailCart->total_price;

        $cart->update(['total_price' => $newTotalPrice]);

        $detailCart->delete();

        return response()->json(['message' => 'Produk berhasil dihapus.']);
    }
}
