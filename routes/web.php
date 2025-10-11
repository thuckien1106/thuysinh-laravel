<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{
    PageController, ProductController, CartController,
    OrderController, AuthController, AdminController
};

// =========================
// 🏠 TRANG NGƯỜI DÙNG
// =========================

// Trang chính
Route::get('/', [PageController::class, 'home'])->name('home');
Route::get('/about', [PageController::class, 'about'])->name('about');
Route::get('/contact', [PageController::class, 'contact'])->name('contact');

// Sản phẩm
Route::get('/product/{id}', [ProductController::class, 'show'])->name('product.show');

// Giỏ hàng & thanh toán
Route::get('/cart', [CartController::class, 'index'])->name('cart');
Route::get('/checkout', [OrderController::class, 'checkout'])->name('checkout');


// =========================
// 🔐 ĐĂNG NHẬP / ĐĂNG XUẤT
// =========================

// Hiển thị form đăng nhập (route chính)
Route::get('/login', [AuthController::class, 'loginForm'])->name('login');

// Alias phụ cho form đăng ký dùng
Route::get('/login', [AuthController::class, 'loginForm'])->name('login.form');

// Gửi form đăng nhập (POST)
Route::post('/login', [AuthController::class, 'login'])->name('login.process');

// Đăng xuất
Route::get('/logout', [AuthController::class, 'logout'])->name('logout');

// Đăng ký
Route::get('/register', [AuthController::class, 'register'])->name('register');
Route::post('/register', [AuthController::class, 'registerProcess'])->name('register.process');


// =========================
// 🧑‍💼 KHU VỰC QUẢN TRỊ
// =========================
// -> Chỉ vào được nếu đã đăng nhập (middleware 'auth' tự định nghĩa)
Route::prefix('admin')->middleware('auth')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
    Route::get('/products', [AdminController::class, 'products'])->name('admin.products');
    Route::get('/customers', [AdminController::class, 'customers'])->name('admin.customers');
    Route::get('/orders', [AdminController::class, 'orders'])->name('admin.orders');
});
