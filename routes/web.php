<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\MyOrderController;

use App\Http\Controllers\AdminAuthController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\ProductVariantController;
use App\Http\Controllers\Admin\OrderController;

use App\Http\Controllers\HomeController;
use App\Http\Controllers\StoreProductController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\InventoryController;
use App\Http\Controllers\Admin\SalesReportController;
use App\Http\Controllers\Admin\OfflineSaleController;
use App\Http\Controllers\CategoryProductController;

/*
|--------------------------------------------------------------------------
| Customer Routes
|--------------------------------------------------------------------------
*/

Route::get('/', [HomeController::class, 'index'])
    ->name('home');

Route::get('/products/{slug}', [StoreProductController::class, 'show'])
    ->name('products.show');

Route::get('/checkout', [CheckoutController::class, 'index'])
    ->name('checkout.index');

Route::post('/checkout', [CheckoutController::class, 'store'])
    ->name('checkout.store');

Route::get('/order-confirmation/{orderCode}', [CheckoutController::class, 'confirmation'])
    ->name('order.confirmation');

Route::get('/order-invoice/{orderCode}/{invoiceToken}', [CheckoutController::class, 'invoice'])
    ->name('order.invoice');


/*
|--------------------------------------------------------------------------
| Cart Routes
|--------------------------------------------------------------------------
*/

Route::post('/cart/add/{product}', [CartController::class, 'addToCart'])
    ->name('cart.add');

Route::get('/cart', [CartController::class, 'index'])
    ->name('cart.index');

Route::delete('/cart/remove/{variant}', [CartController::class, 'remove'])
    ->name('cart.remove');

Route::delete('/cart/clear', [CartController::class, 'clear'])
    ->name('cart.clear');

Route::put('/cart/update/{variant}', [CartController::class, 'update'])
    ->name('cart.update');


/*
|--------------------------------------------------------------------------
| Customer Authentication & Dashboard
|--------------------------------------------------------------------------
*/

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])
    ->name('dashboard');


/*
|--------------------------------------------------------------------------
| Customer My Orders
|--------------------------------------------------------------------------
*/

Route::get('/my-orders', [MyOrderController::class, 'index'])
    ->middleware('auth')
    ->name('orders.index');

Route::get('/my-orders/{orderCode}', [MyOrderController::class, 'show'])
    ->middleware('auth')
    ->name('my-orders.show');

Route::post('/my-orders/{orderCode}/cancel', [MyOrderController::class, 'cancel'])
    ->middleware('auth')
    ->name('my-orders.cancel');


/*
|--------------------------------------------------------------------------
| Customer Profile
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])
        ->name('profile.edit');

    Route::patch('/profile', [ProfileController::class, 'update'])
        ->name('profile.update');

    Route::delete('/profile', [ProfileController::class, 'destroy'])
        ->name('profile.destroy');
});


/*
|--------------------------------------------------------------------------
| Admin Authentication
|--------------------------------------------------------------------------
*/

Route::get('/admin/login', [AdminAuthController::class, 'showLogin'])
    ->name('admin.login');

Route::post('/admin/login', [AdminAuthController::class, 'login'])
    ->name('admin.login.submit');

Route::post('/admin/logout', [AdminAuthController::class, 'logout'])
    ->name('admin.logout');


/*
|--------------------------------------------------------------------------
| Admin Dashboard
|--------------------------------------------------------------------------
*/

Route::get('/admin/dashboard', [DashboardController::class, 'index'])
    ->middleware('admin')
    ->name('admin.dashboard');


/*
|--------------------------------------------------------------------------
| Admin Category Management
|--------------------------------------------------------------------------
*/

Route::resource('/admin/categories', CategoryController::class)
    ->middleware('admin')
    ->names('admin.categories');


/*
|--------------------------------------------------------------------------
| Admin Product Management
|--------------------------------------------------------------------------
*/

Route::resource('/admin/products', ProductController::class)
    ->middleware('admin');


/*
|--------------------------------------------------------------------------
| Admin Product Variant Management
|--------------------------------------------------------------------------
*/

Route::resource(
    '/admin/products/{product}/variants',
    ProductVariantController::class
)->middleware('admin');


/*
|--------------------------------------------------------------------------
| Admin Inventory Management
|--------------------------------------------------------------------------
*/

Route::get('/admin/inventory', [InventoryController::class, 'index'])
    ->middleware('admin')
    ->name('admin.inventory.index');

Route::put('/admin/inventory/{variant}', [InventoryController::class, 'update'])
    ->middleware('admin')
    ->name('admin.inventory.update');

Route::get('/admin/sales-report', [SalesReportController::class, 'index'])
    ->middleware('admin')
    ->name('admin.sales-report.index');


/*
|--------------------------------------------------------------------------
| Admin Order Management
|--------------------------------------------------------------------------
*/

Route::get('/admin/orders', [OrderController::class, 'index'])
    ->middleware('admin')
    ->name('admin.orders.index');

Route::get('/admin/orders/{orderCode}', [OrderController::class, 'show'])
    ->middleware('admin')
    ->name('admin.orders.show');

Route::post('/admin/orders/{orderCode}/approve-payment', [OrderController::class, 'approvePayment'])
    ->middleware('admin')
    ->name('admin.orders.approve-payment');

Route::put('/admin/orders/{orderCode}/status', [OrderController::class, 'updateStatus'])
    ->middleware('admin')
    ->name('admin.orders.update-status');


/*
|--------------------------------------------------------------------------
| Admin Offline Sales Management
|--------------------------------------------------------------------------
*/

Route::get('/admin/offline-sales', [OfflineSaleController::class, 'index'])
    ->middleware('admin')
    ->name('admin.offline-sales.index');

Route::get('/admin/offline-sales/report', [OfflineSaleController::class, 'report'])
    ->middleware('admin')
    ->name('admin.offline-sales.report');

Route::get('/admin/offline-sales/create', [OfflineSaleController::class, 'create'])
    ->middleware('admin')
    ->name('admin.offline-sales.create');

Route::post('/admin/offline-sales', [OfflineSaleController::class, 'store'])
    ->middleware('admin')
    ->name('admin.offline-sales.store');

Route::get('/admin/offline-sales/{offlineSale}', [OfflineSaleController::class, 'show'])
    ->middleware('admin')
    ->name('admin.offline-sales.show');

Route::post('/admin/offline-sales/{offlineSale}/cancel', [OfflineSaleController::class, 'cancel'])
    ->middleware('admin')
    ->name('admin.offline-sales.cancel');

Route::get('/admin/offline-sales/{offlineSale}/receipt', [OfflineSaleController::class, 'receipt'])
    ->middleware('admin')
    ->name('admin.offline-sales.receipt');


/*
|--------------------------------------------------------------------------
| Breeze & Category Routes
|--------------------------------------------------------------------------
*/

Route::get('/category/{slug}', [CategoryProductController::class, 'index'])
    ->name('category.products');

require __DIR__.'/auth.php';