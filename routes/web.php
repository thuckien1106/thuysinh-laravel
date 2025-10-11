<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{
    PageController, ProductController, CartController,
    OrderController, AuthController, AdminController
};

// Trang chính
Route::get('/', [PageController::class, 'home'])->name('home');
Route::get('/about', [PageController::class, 'about'])->name('about');
Route::get('/contact', [PageController::class, 'contact'])->name('contact');

// Sản phẩm
Route::get('/product/{id}', [ProductController::class, 'show'])->name('product.show');

// Giỏ hàng & thanh toán
Route::get('/cart', [CartController::class, 'index'])->name('cart');
Route::get('/checkout', [OrderController::class, 'checkout'])->name('checkout');

// Tài khoản
Route::get('/login', [AuthController::class, 'login'])->name('login');
Route::get('/register', [AuthController::class, 'register'])->name('register');

// Quản trị
Route::prefix('admin')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
    Route::get('/products', [AdminController::class, 'products'])->name('admin.products');
    Route::get('/customers', [AdminController::class, 'customers'])->name('admin.customers');
    Route::get('/orders', [AdminController::class, 'orders'])->name('admin.orders');
});
