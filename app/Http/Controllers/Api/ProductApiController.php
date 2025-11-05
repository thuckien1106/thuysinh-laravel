<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Http\Resources\ProductResource;

class ProductApiController extends Controller
{
    public function index(Request $request)
    {
        $q = trim((string) $request->query('q', ''));
        $categoryId = $request->query('category_id');
        $brandId = $request->query('brand_id');
        $sort = $request->query('sort'); // price_asc, price_desc, newest

        $query = Product::query()->with(['category','brand']);
        if ($q !== '') $query->where('name','like',"%$q%");
        if ($categoryId) $query->where('category_id', $categoryId);
        if ($brandId) $query->where('brand_id', $brandId);
        if ($sort === 'price_asc') $query->orderBy('price');
        elseif ($sort === 'price_desc') $query->orderByDesc('price');
        else $query->orderByDesc('id');

        $products = $query->paginate(12)->appends($request->query());
        return ProductResource::collection($products);
    }

    public function show(Product $product)
    {
        $product->load(['category','brand']);
        return new ProductResource($product);
    }
}

