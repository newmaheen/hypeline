<?php

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
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Customer Routes
|--------------------------------------------------------------------------
*/

Route::get('/', [HomeController::class, 'index'])
    ->name('home');

Route::get('/products/{slug}', [StoreProductController::class, 'show'])
    ->name('products.show');

Route::get('/category/{slug}', [CategoryProductController::class, 'index'])
    ->name('category.products');

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
    ->name('my-orders');

Route::get('/orders', fn () => redirect()->route('my-orders'))
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
| Admin Protected Routes
|--------------------------------------------------------------------------
*/

Route::middleware('admin')->prefix('admin')->name('admin.')->group(function () {

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->name('dashboard');

    // Admin Password Change
    Route::get('/password/change', [AdminAuthController::class, 'showChangePasswordForm'])
        ->name('password.change');

    Route::post('/password/change', [AdminAuthController::class, 'updatePassword'])
        ->name('password.update');

    // Category Management
    Route::resource('categories', CategoryController::class)
        ->names('categories');

    // Product Management
    Route::resource('products', ProductController::class)
        ->names('products');

    // Product Variant Management
    Route::resource('products/{product}/variants', ProductVariantController::class)
        ->names('variants');

    // Inventory & Reports
    Route::get('/inventory', [InventoryController::class, 'index'])
        ->name('inventory.index');

    Route::put('/inventory/{variant}', [InventoryController::class, 'update'])
        ->name('inventory.update');

    Route::get('/sales-report', [SalesReportController::class, 'index'])
        ->name('sales-report.index');

    // Order Management
    Route::get('/orders', [OrderController::class, 'index'])
        ->name('orders.index');

    Route::get('/orders/{orderCode}', [OrderController::class, 'show'])
        ->name('orders.show');

    Route::put('/orders/{orderCode}/status', [OrderController::class, 'updateStatus'])
        ->name('orders.update-status');

    Route::post('/orders/{orderCode}/approve-payment', [OrderController::class, 'approvePayment'])
        ->name('orders.approve-payment');

    Route::post('/orders/{orderCode}/decline-payment', [OrderController::class, 'declinePayment'])
        ->name('orders.decline-payment');

    // Offline Sales (POS)
    Route::get('/offline-sales', [OfflineSaleController::class, 'index'])
        ->name('offline-sales.index');

    Route::get('/offline-sales/report', [OfflineSaleController::class, 'report'])
        ->name('offline-sales.report');

    Route::get('/offline-sales/create', [OfflineSaleController::class, 'create'])
        ->name('offline-sales.create');

    Route::post('/offline-sales', [OfflineSaleController::class, 'store'])
        ->name('offline-sales.store');

    Route::get('/offline-sales/{offlineSale}', [OfflineSaleController::class, 'show'])
        ->name('offline-sales.show');

    Route::post('/offline-sales/{offlineSale}/cancel', [OfflineSaleController::class, 'cancel'])
        ->name('offline-sales.cancel');

    Route::get('/offline-sales/{offlineSale}/receipt', [OfflineSaleController::class, 'receipt'])
        ->name('offline-sales.receipt');
});


/*
|--------------------------------------------------------------------------
| Auth Routes
|--------------------------------------------------------------------------
*/

require __DIR__.'/auth.php';


Route::get('/clear-cache', function () {
    \Illuminate\Support\Facades\Artisan::call('optimize:clear');
    return '<h1>App Optimized & Cache Cleared Successfully!</h1>';
});

Route::get('/clear-cache', function () {
    \Illuminate\Support\Facades\Artisan::call('optimize:clear');
    \Illuminate\Support\Facades\Artisan::call('config:clear');
    \Illuminate\Support\Facades\Artisan::call('cache:clear');
    \Illuminate\Support\Facades\Artisan::call('view:clear');
    \Illuminate\Support\Facades\Artisan::call('route:clear');
    
    return '<div style="text-align:center; padding:50px; font-family:sans-serif;">
                <h1 style="color:green;">✓ App Optimized & All Caches Cleared Successfully!</h1>
                <p>Config, Cache, Views, and Routes are fresh now.</p>
            </div>';
});