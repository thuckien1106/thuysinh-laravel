<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{
    PageController, ProductController, CartController,
    OrderController, AuthController, AdminController
};
use App\Http\Controllers\AuthPasswordController;
use App\Http\Controllers\AccountController;

// =========================
// ðŸ  TRANG NGÆ¯á»œI DÃ™NG
// =========================

// Trang chÃ­nh
Route::get('/', [PageController::class, 'home'])->name('home');
Route::get('/about', [PageController::class, 'about'])->name('about');
Route::get('/contact', [PageController::class, 'contact'])->name('contact');
Route::post('/contact', [PageController::class, 'submitContact'])->name('contact.submit');

// Sáº£n pháº©m
Route::get('/product/{id}', [ProductController::class, 'show'])->name('product.show');
// Listing & search
Route::get('/products', [ProductController::class, 'index'])->name('products.index');
// Sáº£n pháº©m giáº£m giÃ¡
Route::get('/sale', [ProductController::class, 'sale'])->name('products.sale');
// LÆ°u mÃ£ giáº£m giÃ¡ (khÃ´ng Ã¡p dá»¥ng ngay)
Route::post('/coupon/save', [CartController::class, 'saveCoupon'])->name('coupon.save');
// Reviews
Route::post('/product/{id}/reviews', [ProductController::class, 'addReview'])->name('product.review.add');

// Giá» hÃ ng & thanh toÃ¡n
Route::get('/cart', [CartController::class, 'index'])->name('cart');
// CÃ¡c hÃ nh vi mua hÃ ng CHá»ˆ cho khÃ¡ch hÃ ng (user), khÃ´ng cho admin
Route::middleware('customer.role')->group(function () {
    Route::get('/checkout', [OrderController::class, 'checkout'])->name('checkout');
    Route::post('/checkout', [OrderController::class, 'process'])->name('checkout.process');
    Route::get('/my-orders', [OrderController::class, 'myOrders'])->name('orders.mine');
    Route::post('/my-orders/{id}/cancel', [OrderController::class, 'cancel'])->name('orders.cancel');
    Route::post('/my-orders/{id}/received', [OrderController::class, 'received'])->name('orders.received');
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
// ðŸ” ÄÄ‚NG NHáº¬P / ÄÄ‚NG XUáº¤T
// =========================

// Hiá»ƒn thá»‹ form Ä‘Äƒng nháº­p (route chÃ­nh)
Route::get('/login', [AuthController::class, 'loginForm'])->name('login');
Route::get('/login-form', [AuthController::class, 'loginForm'])->name('login.form');

// Password reset (demo: code hiá»ƒn thá»‹ qua flash, khÃ´ng gá»­i email)
Route::get('/password/forgot', [AuthPasswordController::class, 'forgotForm'])->name('password.forgot');
Route::post('/password/forgot', [AuthPasswordController::class, 'forgotSubmit'])->name('password.forgot.submit');
Route::get('/password/reset', [AuthPasswordController::class, 'resetForm'])->name('password.reset.form');
Route::post('/password/reset', [AuthPasswordController::class, 'resetSubmit'])->name('password.reset.submit');

// TÃ i khoáº£n khÃ¡ch hÃ ng (role user)
Route::middleware('customer.role')->group(function(){
    Route::get('/account', [AccountController::class, 'profile'])->name('account.profile');
    Route::post('/account', [AccountController::class, 'saveProfile'])->name('account.profile.save');
    // Back-compat: náº¿u client cÅ© gá»­i vÃ o /account/address thÃ¬ xá»­ lÃ½ nhÆ° /account
    Route::post('/account/address', [AccountController::class, 'saveProfile'])->name('account.address.save');
});


// Gá»­i form Ä‘Äƒng nháº­p (POST)
Route::post('/login', [AuthController::class, 'login'])->name('login.process');

// ÄÄƒng xuáº¥t
Route::get('/logout', [AuthController::class, 'logout'])->name('logout');

// ÄÄƒng kÃ½
Route::get('/register', [AuthController::class, 'register'])->name('register');
Route::post('/register', [AuthController::class, 'registerProcess'])->name('register.process');


// =========================
// ðŸ§‘â€ðŸ’¼ KHU Vá»°C QUáº¢N TRá»Š
// =========================
// -> Chá»‰ vÃ o Ä‘Æ°á»£c náº¿u Ä‘Ã£ Ä‘Äƒng nháº­p (middleware 'auth' tá»± Ä‘á»‹nh nghÄ©a)
Route::prefix('admin')->middleware('admin.role')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');

    // Product CRUD
    Route::get('/products', [\App\Http\Controllers\Admin\ProductAdminController::class, 'index'])->name('admin.products.index');
    Route::get('/products/create', [\App\Http\Controllers\Admin\ProductAdminController::class, 'create'])->name('admin.products.create');
    Route::post('/products', [\App\Http\Controllers\Admin\ProductAdminController::class, 'store'])->name('admin.products.store');
    Route::get('/products/{product}/edit', [\App\Http\Controllers\Admin\ProductAdminController::class, 'edit'])->name('admin.products.edit');
    Route::put('/products/{product}', [\App\Http\Controllers\Admin\ProductAdminController::class, 'update'])->name('admin.products.update');
    Route::delete('/products/{product}', [\App\Http\Controllers\Admin\ProductAdminController::class, 'destroy'])->name('admin.products.destroy');
    // Quick discount schedule
    Route::post('/products/{product}/discount', [\App\Http\Controllers\Admin\ProductAdminController::class, 'setDiscount'])->name('admin.products.discount');

    // Orders
    Route::get('/orders', [\App\Http\Controllers\Admin\OrderAdminController::class, 'index'])->name('admin.orders.index');
    Route::get('/orders/{id}', [\App\Http\Controllers\Admin\OrderAdminController::class, 'show'])->name('admin.orders.show');
    Route::post('/orders/{id}/status', [\App\Http\Controllers\Admin\OrderAdminController::class, 'updateStatus'])->name('admin.orders.status');
    Route::get('/orders-export/csv', [\App\Http\Controllers\Admin\OrderAdminController::class, 'exportCsv'])->name('admin.orders.export.csv');

    // Brands CRUD
    Route::get('/brands', [\App\Http\Controllers\Admin\BrandAdminController::class, 'index'])->name('admin.brands.index');
    Route::get('/brands/create', [\App\Http\Controllers\Admin\BrandAdminController::class, 'create'])->name('admin.brands.create');
    Route::post('/brands', [\App\Http\Controllers\Admin\BrandAdminController::class, 'store'])->name('admin.brands.store');
    Route::get('/brands/{brand}/edit', [\App\Http\Controllers\Admin\BrandAdminController::class, 'edit'])->name('admin.brands.edit');
    Route::put('/brands/{brand}', [\App\Http\Controllers\Admin\BrandAdminController::class, 'update'])->name('admin.brands.update');
    Route::delete('/brands/{brand}', [\App\Http\Controllers\Admin\BrandAdminController::class, 'destroy'])->name('admin.brands.destroy');

    // Discounts CRUD
    Route::get('/discounts', [\App\Http\Controllers\Admin\DiscountAdminController::class, 'index'])->name('admin.discounts.index');
    Route::get('/discounts/create', [\App\Http\Controllers\Admin\DiscountAdminController::class, 'create'])->name('admin.discounts.create');
    Route::post('/discounts', [\App\Http\Controllers\Admin\DiscountAdminController::class, 'store'])->name('admin.discounts.store');
    Route::get('/discounts/{discount}/edit', [\App\Http\Controllers\Admin\DiscountAdminController::class, 'edit'])->name('admin.discounts.edit');
    Route::put('/discounts/{discount}', [\App\Http\Controllers\Admin\DiscountAdminController::class, 'update'])->name('admin.discounts.update');
    Route::delete('/discounts/{discount}', [\App\Http\Controllers\Admin\DiscountAdminController::class, 'destroy'])->name('admin.discounts.destroy');

    // Categories CRUD
    Route::get('/categories', [\App\Http\Controllers\Admin\CategoryAdminController::class, 'index'])->name('admin.categories.index');
    Route::get('/categories/create', [\App\Http\Controllers\Admin\CategoryAdminController::class, 'create'])->name('admin.categories.create');
    Route::post('/categories', [\App\Http\Controllers\Admin\CategoryAdminController::class, 'store'])->name('admin.categories.store');
    Route::get('/categories/{id}/edit', [\App\Http\Controllers\Admin\CategoryAdminController::class, 'edit'])->name('admin.categories.edit');
    Route::put('/categories/{id}', [\App\Http\Controllers\Admin\CategoryAdminController::class, 'update'])->name('admin.categories.update');
    Route::delete('/categories/{id}', [\App\Http\Controllers\Admin\CategoryAdminController::class, 'destroy'])->name('admin.categories.destroy');
});



// AJAX routes for admin discounts (separate group)
Route::prefix('admin')->middleware('admin.role')->group(function () {
    Route::get('/discounts/modal/create', [\App\Http\Controllers\Admin\DiscountAjaxController::class, 'modalCreate'])->name('admin.discounts.modal.create');
    Route::get('/discounts/{discount}/modal/edit', [\App\Http\Controllers\Admin\DiscountAjaxController::class, 'modalEdit'])->name('admin.discounts.modal.edit');
    Route::post('/discounts/ajax', [\App\Http\Controllers\Admin\DiscountAjaxController::class, 'ajaxStore'])->name('admin.discounts.ajax.store');
    Route::post('/discounts/{discount}/ajax', [\App\Http\Controllers\Admin\DiscountAjaxController::class, 'ajaxUpdate'])->name('admin.discounts.ajax.update');
});
