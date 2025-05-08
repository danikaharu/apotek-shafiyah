<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Product;
use App\Models\Recipe;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class RecipeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if (request()->ajax()) {
            $recipes = Recipe::with('customer')->latest()->get();
            return DataTables::of($recipes)
                ->addIndexColumn()
                ->addColumn('customer', function ($row) {
                    return $row->customer ? $row->customer->full_name : '-';
                })
                ->addColumn('created_at', function ($row) {
                    return $row->created_at;
                })
                ->addColumn('status', function ($row) {
                    return $row->status();
                })
                ->addColumn('action', 'admin.recipe.include.action')
                ->toJson();
        }

        return view('admin.recipe.index');
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
    public function store(Request $request) {}

    /**
     * Display the specified resource.
     */
    public function show(Recipe $recipe)
    {
        $recipe->load('customer');
        $allProducts = Product::all();
        return view('admin.recipe.show', compact('recipe', 'allProducts'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Recipe $recipe)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Recipe $recipe)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Recipe $recipe)
    {
        // 
    }

    public function approveRecipe(Request $request, Recipe $recipe)
    {
        $request->validate([
            'products' => 'required|array|min:1',
            'products.*.product_id' => 'required|exists:products,id',
            'products.*.quantity' => 'required|integer|min:1',
            'products.*.price' => 'required|numeric|min:0',
        ]);

        $customerId = $recipe->customer_id;
        $adminId = auth()->id();

        $cart = Cart::firstOrCreate(
            ['customer_id' => $customerId],
            ['admin_id' => $adminId, 'total_price' => 0]
        );

        $total = 0;
        foreach ($request->products as $item) {
            $product = Product::find($item['product_id']);
            $amount = $item['quantity'];
            $price = $item['price'];
            $totalPrice = $amount * $price;

            $cart->details()->create([
                'product_id' => $product->id,
                'price' => $price,
                'discount' => $item->discount->discount_amount ?? 0,
                'amount' => $amount,
                'total_price' => $totalPrice,
            ]);

            $total += $totalPrice;
        }

        $cart->update(['total_price' => $total]);
        $recipe->update(['status' => 2]);

        return redirect()->route('admin.recipe.index')->with('success', 'Resep disetujui dan produk ditambahkan ke keranjang.');
    }

    public function rejectRecipe(Recipe $recipe)
    {
        $recipe->update([
            'status' => 3
        ]);

        return redirect()->route('admin.recipe.index')->with('error', 'Resep telah ditolak.');
    }
}
