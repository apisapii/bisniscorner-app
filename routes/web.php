<?php

use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\TenantController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CatalogController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\Customer\OrderHistoryController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\OrderItemFulfillmentController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', [CatalogController::class, 'index'])->name('catalog');
Route::get('/toko/{umkm}', [CatalogController::class, 'byUmkm'])->name('catalog.umkm');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';


Route::middleware(['auth', 'role:customer'])->group(function () {
    Route::get('/history', [OrderHistoryController::class, 'index'])->name('customer.history');
    Route::get('/history/{order}/struk', [OrderHistoryController::class, 'receipt'])->name('customer.orders.receipt');
    Route::get('/history/{order}', [OrderHistoryController::class, 'show'])->name('customer.orders.show');
});

Route::middleware(['auth', 'role:admin_umkm'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::patch('/dashboard/order-items/{orderItem}', [OrderItemFulfillmentController::class, 'update'])->name('umkm.order-items.update');
    Route::get('/products', [ProductController::class, 'index'])->name('products.index');
    Route::get('/products/create', [ProductController::class, 'create'])->name('products.create');
    Route::post('/products', [ProductController::class, 'store'])->name('products.store');
    Route::get('/products/{product}/edit', [ProductController::class, 'edit'])->name('products.edit');
    Route::put('/products/{product}', [ProductController::class, 'update'])->name('products.update');
    Route::delete('/products/{product}', [ProductController::class, 'destroy'])->name('products.destroy');
});

Route::middleware(['auth', 'role:super_admin'])->group(function () {
    Route::get('/admin/categories', [CategoryController::class, 'index'])->name('admin.categories.index');
    Route::get('/admin/categories/create', [CategoryController::class, 'create'])->name('admin.categories.create');
    Route::post('/admin/categories', [CategoryController::class, 'store'])->name('admin.categories.store');
    Route::get('/admin/categories/{category}/edit', [CategoryController::class, 'edit'])->name('admin.categories.edit');
    Route::put('/admin/categories/{category}', [CategoryController::class, 'update'])->name('admin.categories.update');
    Route::delete('/admin/categories/{category}', [CategoryController::class, 'destroy'])->name('admin.categories.destroy');

    Route::get('/admin/tenants', [TenantController::class, 'index'])->name('admin.tenants.index');
    Route::post('/admin/tenants', [TenantController::class, 'store'])->name('admin.tenants.store');
    Route::delete('/admin/tenants/{id}', [TenantController::class, 'destroy'])->name('admin.tenants.destroy');
});

Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
Route::post('/cart/add/{id}', [CartController::class, 'add'])->name('cart.add');
Route::post('/cart/{id}/increase', [CartController::class, 'increase'])->name('cart.increase');
Route::post('/cart/{id}/decrease', [CartController::class, 'decrease'])->name('cart.decrease');

Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
Route::post('/checkout/process', [CheckoutController::class, 'process'])->name('checkout.process');
Route::get('/checkout/success/{id}', [CheckoutController::class, 'success'])->name('checkout.success');
Route::post('/checkout/{order}/dummy-pay', [CheckoutController::class, 'dummyPay'])->name('checkout.dummy-pay');
