<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{
    PageController, ProductController, CartController,
    OrderController, AuthController, AdminController
};
use App\Http\Controllers\AuthPasswordController;
use App\Http\Controllers\AccountController;

// =========================
// 🏠 TRANG NGƯỜI DÙNG
// =========================

// Trang chính
Route::get('/', [PageController::class, 'home'])->name('home');
Route::get('/about', [PageController::class, 'about'])->name('about');
Route::get('/contact', [PageController::class, 'contact'])->name('contact');
Route::post('/contact', [PageController::class, 'submitContact'])->name('contact.submit');

// Sản phẩm
Route::get('/product/{id}', [ProductController::class, 'show'])->name('product.show');
// Listing & search
Route::get('/products', [ProductController::class, 'index'])->name('products.index');
// Reviews
Route::post('/product/{id}/reviews', [ProductController::class, 'addReview'])->name('product.review.add');

// Giỏ hàng & thanh toán
Route::get('/cart', [CartController::class, 'index'])->name('cart');
// Các hành vi mua hàng CHỈ cho khách hàng (user), không cho admin
Route::middleware('customer.role')->group(function () {
    Route::get('/checkout', [OrderController::class, 'checkout'])->name('checkout');
    Route::post('/checkout', [OrderController::class, 'process'])->name('checkout.process');
    Route::get('/my-orders', [OrderController::class, 'myOrders'])->name('orders.mine');
    Route::post('/my-orders/{id}/cancel', [OrderController::class, 'cancel'])->name('orders.cancel');
    Route::get('/thank-you/{id}', [OrderController::class, 'thankyou'])->name('order.thankyou');
});

// Cart actions
Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');
Route::patch('/cart/{id}', [CartController::class, 'update'])->name('cart.update');
Route::delete('/cart/{id}', [CartController::class, 'remove'])->name('cart.remove');
Route::post('/cart/coupon', [CartController::class, 'applyCoupon'])->name('cart.coupon');
Route::get('/mini-cart', [CartController::class, 'mini'])->name('cart.mini');

// Order confirmation (moved into auth group)


// =========================
// 🔐 ĐĂNG NHẬP / ĐĂNG XUẤT
// =========================

// Hiển thị form đăng nhập (route chính)
Route::get('/login', [AuthController::class, 'loginForm'])->name('login');
Route::get('/login-form', [AuthController::class, 'loginForm'])->name('login.form');

// Password reset (demo: code hiển thị qua flash, không gửi email)
Route::get('/password/forgot', [AuthPasswordController::class, 'forgotForm'])->name('password.forgot');
Route::post('/password/forgot', [AuthPasswordController::class, 'forgotSubmit'])->name('password.forgot.submit');
Route::get('/password/reset', [AuthPasswordController::class, 'resetForm'])->name('password.reset.form');
Route::post('/password/reset', [AuthPasswordController::class, 'resetSubmit'])->name('password.reset.submit');

// Tài khoản khách hàng (role user)
Route::middleware('customer.role')->group(function(){
    Route::get('/account', [AccountController::class, 'profile'])->name('account.profile');
    Route::post('/account', [AccountController::class, 'saveProfile'])->name('account.profile.save');
    // Back-compat: nếu client cũ gửi vào /account/address thì xử lý như /account
    Route::post('/account/address', [AccountController::class, 'saveProfile'])->name('account.address.save');
});


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
Route::prefix('admin')->middleware('admin.role')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');

    // Product CRUD
    Route::get('/products', [\App\Http\Controllers\Admin\ProductAdminController::class, 'index'])->name('admin.products.index');
    Route::get('/products/create', [\App\Http\Controllers\Admin\ProductAdminController::class, 'create'])->name('admin.products.create');
    Route::post('/products', [\App\Http\Controllers\Admin\ProductAdminController::class, 'store'])->name('admin.products.store');
    Route::get('/products/{id}/edit', [\App\Http\Controllers\Admin\ProductAdminController::class, 'edit'])->name('admin.products.edit');
    Route::put('/products/{id}', [\App\Http\Controllers\Admin\ProductAdminController::class, 'update'])->name('admin.products.update');
    Route::delete('/products/{id}', [\App\Http\Controllers\Admin\ProductAdminController::class, 'destroy'])->name('admin.products.destroy');

    // Orders
    Route::get('/orders', [\App\Http\Controllers\Admin\OrderAdminController::class, 'index'])->name('admin.orders.index');
    Route::get('/orders/{id}', [\App\Http\Controllers\Admin\OrderAdminController::class, 'show'])->name('admin.orders.show');
    Route::post('/orders/{id}/status', [\App\Http\Controllers\Admin\OrderAdminController::class, 'updateStatus'])->name('admin.orders.status');
    Route::get('/orders-export/csv', [\App\Http\Controllers\Admin\OrderAdminController::class, 'exportCsv'])->name('admin.orders.export.csv');
});
