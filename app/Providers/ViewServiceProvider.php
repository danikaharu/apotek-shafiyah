<?php

namespace App\Providers;

use App\Models\Brand;
use App\Models\Cart;
use App\Models\Category;
use App\Models\Customer;
use App\Models\Order;
use App\Models\Product;
use App\Models\Profile;
use App\Models\Purchase;
use App\Models\Receipt;
use App\Models\Refund;
use App\Models\Supplier;
use App\Models\Type;
use App\Models\Unit;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class ViewServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        View::composer(['admin.product.index', 'admin.product.include.action'], function ($view) {
            $view->with([
                'categories' => Category::latest()->get(),
                'types' => Type::latest()->get(),
                'brands' => Brand::latest()->get(),
                'units' => Unit::latest()->get(),
            ]);
        });

        View::composer('admin.discount.index', function ($view) {
            $view->with([
                'products' => Product::where('stock', '>', 0)->get(),
            ]);
        });

        View::composer('admin.order.index', function ($view) {
            $view->with([
                'totalOrder1' => Order::where('status', 1)->count(),
                'totalOrder2' => Order::where('status', 2)->count(),
                'totalOrder3' => Order::where('status', 3)->count(),
            ]);
        });

        View::composer('admin.user.index', function ($view) {
            $view->with([
                'totalCustomers' => Customer::count(),
                'totalCustomersThisWeek' => Customer::whereBetween('created_at', [now()->startOfWeek()->toDateString(), now()->endOfWeek()->toDateString()])->count(),
                'totalCustomersThisMonth' => Customer::whereBetween('created_at', [now()->startOfMonth()->toDateString(), now()->endOfMonth()->toDateString()])->count(),
            ]);
        });

        View::composer('admin.purchase.index', function ($view) {
            $view->with([
                'suppliers' => Supplier::latest()->get(),
                'products' => Product::latest()->get(),
                'totalPurchasesToday' => Purchase::whereDate('order_date', now()->toDateString())->count(),
                'totalPurchasesThisWeek' => Purchase::whereBetween('order_date', [now()->startOfWeek()->toDateString(), now()->endOfWeek()->toDateString()])->count(),
                'totalPurchasesThisMonth' => Purchase::whereBetween('order_date', [now()->startOfMonth()->toDateString(), now()->endOfMonth()->toDateString()])->count(),
            ]);
        });

        View::composer('admin.receipt.index', function ($view) {
            $view->with([
                'totalReceiptsToday' => Receipt::whereDate('receipt_date', now()->toDateString())->count(),
                'totalReceiptsThisWeek' => Receipt::whereBetween('receipt_date', [now()->startOfWeek()->toDateString(), now()->endOfWeek()->toDateString()])->count(),
                'totalReceiptsThisMonth' => Receipt::whereBetween('receipt_date', [now()->startOfMonth()->toDateString(), now()->endOfMonth()->toDateString()])->count(),
            ]);
        });

        View::composer('admin.receipt.include.form', function ($view) {
            $view->with([
                'purchases' => Purchase::with('detail_purchase.product', 'supplier')->latest()->get(),
            ]);
        });

        View::composer('admin.refund.index', function ($view) {
            $view->with([
                'totalRefundsToday' => Refund::whereDate('refund_date', now()->toDateString())->count(),
                'totalRefundsThisWeek' => Refund::whereBetween('refund_date', [now()->startOfWeek()->toDateString(), now()->endOfWeek()->toDateString()])->count(),
                'totalRefundsThisMonth' => Refund::whereBetween('refund_date', [now()->startOfMonth()->toDateString(), now()->endOfMonth()->toDateString()])->count(),
            ]);
        });

        View::composer('admin.refund.include.form', function ($view) {
            $view->with([
                'purchases' => Purchase::with('supplier')->latest()->get(),
            ]);
        });

        View::composer('layouts.user.include.navbar', function ($view) {
            $view->with([
                'categories' => Category::latest()->get(),
            ]);
        });

        View::composer(['admin.product.expire', 'admin.product.dwindling'], function ($view) {
            $view->with([
                'totalProducts' => Product::where('stock', '>', 0)->count(),
                'totalProductsDwindling' => Product::where('stock', '>', 0)->where('stock', '<=', 5)->count(),
                'totalProductsExpire' => Product::join('detail_purchases', 'products.id', '=', 'detail_purchases.product_id')
                    ->whereDate('detail_purchases.expired_date', '<=', now()->today())
                    ->count(),
            ]);
        });

        View::composer('layouts.user.include.navbar', function ($view) {
            $user = auth()->user();
            if ($user) {
                $view->with([
                    'carts' => Cart::with('details')->where('customer_id', auth()->user()->id)->get(),
                    'orders' => Order::with('detail_order')->where('customer_id', auth()->user()->id)->where('status', 4)->get(),
                ]);
            }
        });

        View::composer('layouts.user.include.footer', function ($view) {
            $view->with([
                'profile' => Profile::first(),
            ]);
        });
    }
}
