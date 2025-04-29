<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', [App\Http\Controllers\User\DashboardController::class, 'index'])->name('dashboard');
Route::get('/product', [App\Http\Controllers\User\DashboardController::class, 'product'])->name('product');
Route::get('/discount', [App\Http\Controllers\User\DashboardController::class, 'discount'])->name('discount');
Route::get('/detail-product/{product}', [App\Http\Controllers\User\DashboardController::class, 'detailProduct'])->name('detail.product');
Route::get('/search-product', [App\Http\Controllers\User\DashboardController::class, 'searchProduct'])->name('search.product');
Route::get('/kategori/{category}', [App\Http\Controllers\User\DashboardController::class, 'category'])->name('category');

// Cart
Route::post('/cart/add', [App\Http\Controllers\User\CartController::class, 'add'])->name('cart.add');
Route::patch('/cart/update-quantity/{cartItemId}', [App\Http\Controllers\User\CartController::class, 'updateQuantity'])->name('cart.updateQuantity');
Route::delete('/cart/remove/{cartItemId}', [App\Http\Controllers\User\CartController::class, 'removeItem'])->name('cart.removeItem');
Route::get('/cart/total', [App\Http\Controllers\User\CartController::class, 'getCartTotal'])->name('cart.total');
Route::get('/cart/get', [App\Http\Controllers\User\CartController::class, 'getCart'])->name('cart.get');


Route::post('/resep', [App\Http\Controllers\User\RecipeController::class, 'store'])->name('store.recipe');

Route::get('/akun', [App\Http\Controllers\User\AccountController::class, 'index'])->name('account.index');
Route::put('/akun/{customer}', [App\Http\Controllers\User\AccountController::class, 'update'])->name('account.update');

// Order
Route::get('/riwayat', [App\Http\Controllers\User\OrderController::class, 'index'])->name('order.history');
Route::get('/nota/{order}', [App\Http\Controllers\User\OrderController::class, 'note'])->name('order.note');
Route::post('/order', [App\Http\Controllers\User\OrderController::class, 'store'])->name('store.order');
Route::put('/order/{order}', [App\Http\Controllers\User\OrderController::class, 'cancel'])->name('cancel.order');
Route::post('/order/self-pickup/{order_id}', [App\Http\Controllers\User\OrderController::class, 'selfPickup'])->name('order.selfPickup');
Route::post('/order/maxim/{order_id}', [App\Http\Controllers\User\OrderController::class, 'orderMaxim'])->name('order.maxim');

// Midtrans
Route::post('/midtrans/callback', [App\Http\Controllers\User\MidtransController::class, 'callback'])->name('midtrans.callback');
Route::get('/payment/success/{order_id}', [App\Http\Controllers\User\MidtransController::class, 'success'])->name('payment.success');
Route::get('/payment/pending', [App\Http\Controllers\User\MidtransController::class, 'pending'])->name('payment.pending');
Route::get('/payment/failed', [App\Http\Controllers\User\MidtransController::class, 'failed'])->name('payment.failed');


Route::group(['prefix' => 'admin', 'as' => 'admin.', 'middleware' => 'auth'], function () {
    // Cashier
    Route::get('/cashier', [App\Http\Controllers\Admin\CashierController::class, 'index'])->name('cashier');

    Route::resource('/cart', App\Http\Controllers\Admin\CartController::class);

    // Dashboard
    Route::get('/dashboard', [App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('dashboard');

    // Product
    Route::resource('/product', App\Http\Controllers\Admin\ProductController::class);
    Route::get('/dwindling-product', [App\Http\Controllers\Admin\ProductController::class, 'dwindling'])->name('dwindling.product');
    Route::get('/expire-product', [App\Http\Controllers\Admin\ProductController::class, 'expire'])->name('expire.product');

    // Discount
    Route::resource('/discount', App\Http\Controllers\Admin\DiscountController::class);

    // Category
    Route::resource('/category', App\Http\Controllers\Admin\CategoryController::class);

    // Type
    Route::resource('/type', App\Http\Controllers\Admin\TypeController::class);

    // Brand
    Route::resource('/brand', App\Http\Controllers\Admin\BrandController::class);

    // Unit
    Route::resource('/unit', App\Http\Controllers\Admin\UnitController::class);

    // Order
    Route::resource('/order', App\Http\Controllers\Admin\OrderController::class);
    Route::get('/approveOrder/{order}', [App\Http\Controllers\Admin\OrderController::class, 'approve'])->name('approve.order');
    Route::get('/export/order', [App\Http\Controllers\Admin\OrderController::class, 'export'])->name('export.order');

    // User
    Route::resource('/user', App\Http\Controllers\Admin\UserController::class);

    // Supplier
    Route::resource('/supplier', App\Http\Controllers\Admin\SupplierController::class);

    // Purchase
    Route::resource('/purchase', App\Http\Controllers\Admin\PurchaseController::class);
    Route::get('/getPurchaseDetails/{id}', [App\Http\Controllers\Admin\PurchaseController::class, 'getPurchaseDetails']);
    Route::get('/print/purchase/{purchase}', [App\Http\Controllers\Admin\PurchaseController::class, 'print'])->name('print.purchase');
    Route::get('/export/purchase', [App\Http\Controllers\Admin\PurchaseController::class, 'export'])->name('export.purchase');

    // Receipt
    Route::resource('/receipt', App\Http\Controllers\Admin\ReceiptController::class);
    Route::get('/print/receipt/{receipt}', [App\Http\Controllers\Admin\ReceiptController::class, 'print'])->name('print.receipt');
    Route::get('/export/receipt', [App\Http\Controllers\Admin\ReceiptController::class, 'export'])->name('export.receipt');

    // Refund
    Route::resource('/refund', App\Http\Controllers\Admin\RefundController::class);
    Route::get('/print/refund/{refund}', [App\Http\Controllers\Admin\RefundController::class, 'print'])->name('print.refund');

    // Report
    Route::get('/report/receipt', [App\Http\Controllers\Admin\ReportController::class, 'receipt'])->name('report.receipt');
    Route::get('/report/purchase', [App\Http\Controllers\Admin\ReportController::class, 'purchase'])->name('report.purchase');
    Route::get('/report/order', [App\Http\Controllers\Admin\ReportController::class, 'order'])->name('report.order');

    // Banner
    Route::resource('/banner', App\Http\Controllers\Admin\BannerController::class);

    // Profile
    Route::resource('/profile', App\Http\Controllers\Admin\ProfileController::class);

    // Recipe
    Route::resource('/recipe', App\Http\Controllers\Admin\RecipeController::class);
    Route::post('/approveRecipe/{recipe}', [App\Http\Controllers\Admin\RecipeController::class, 'approveRecipe'])->name('approveRecipe');
    Route::post('/rejectRecipe/{recipe}', [App\Http\Controllers\Admin\RecipeController::class, 'rejectRecipe'])->name('rejectRecipe');
});
