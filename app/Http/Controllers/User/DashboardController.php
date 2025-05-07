<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use App\Models\Category;
use App\Models\Discount;
use App\Models\Product;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $products = Product::where('stock', '>', 0)->latest()->limit(4)->get();

        $productDiscount = Discount::with('product')->where('show_on_dashboard', true)->latest()->limit(4)->get();

        $banners = Banner::latest()->limit(3)->get();

        return view('user.index', compact('products', 'productDiscount', 'banners'));
    }

    public function product()
    {
        $products = Product::where('stock', '>', 0)->latest()->paginate(12);

        return view('user.product', compact('products'));
    }

    public function discount()
    {
        $discounts = Discount::with('product')->where('show_on_dashboard', true)->latest()->paginate(12);

        return view('user.discount', compact('discounts'));
    }

    public function detailProduct(Product $product)
    {
        $product->load('discount');
        return view('user.detail-product', compact('product'));
    }

    public function searchProduct(Request $request)
    {
        $search = $request->search;

        $products = Product::where('name', 'like', "%" . $search . "%")
            ->where('stock', '>', 0)
            ->latest()
            ->paginate(12);

        return view('user.search-product', compact('products', 'search'));
    }

    public function category(Category $category)
    {
        $products = Product::where('category_id', $category->id)->where('stock', '>', 0)->paginate(12);

        return view('user.category', compact('products', 'category'));
    }
}
