<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ProductApiController;
use App\Http\Controllers\Api\DiscountApiController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Public product APIs
Route::get('/products', [ProductApiController::class, 'index']);
Route::get('/products/{product}', [ProductApiController::class, 'show']);

// Live search products by name (public)
Route::get('/search/products', function (Illuminate\Http\Request $r) {
    $q = trim((string) $r->query('q',''));
    $products = \App\Models\Product::select('id','name','price','image','category_id')
        ->with('category:id,name')
        ->when($q !== '', fn($qr)=>$qr->where('name','like', "%$q%"))
        ->orderBy('name')
        ->limit(8)
        ->get();
    
    return $products->map(fn($p) => [
        'id' => $p->id,
        'name' => $p->name,
        'price' => (int)$p->price,
        'image' => $p->image ?? 'default.png',
        'category' => $p->category?->name ?? 'Khác'
    ])->values();
});

// Discounts API (public read)
Route::get('/discounts', [DiscountApiController::class, 'index']);
Route::get('/discounts/{discount}', [DiscountApiController::class, 'show']);
