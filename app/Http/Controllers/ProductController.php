<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Review;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $q = $request->get('q');
        $category = $request->get('category');
        $brand = $request->get('brand');
        $min = $request->get('min_price');
        $max = $request->get('max_price');

        $query = Product::query();
        if ($q) $query->where('name', 'like', "%$q%");
        if ($category) $query->where('category_id', $category);
        if ($brand) $query->where('brand_id', $brand);
        if ($min !== null && $min !== '') $query->where('price', '>=', (float)$min);
        if ($max !== null && $max !== '') $query->where('price', '<=', (float)$max);

        $products = $query->orderBy('created_at', 'desc')->paginate(12)->withQueryString();
        $categories = \Illuminate\Support\Facades\DB::table('categories')->orderBy('name')->get();
        $brands = \Illuminate\Support\Facades\DB::table('brands')->orderBy('name')->get();
        return view('products_index', compact('products', 'q', 'category', 'brand', 'min', 'max', 'categories', 'brands'));
    }
    public function show($id)
    {
        $product = Product::findOrFail($id);
        $reviews = Review::where('product_id', $product->id)->orderBy('id','desc')->limit(20)->get();
        return view('product_detail', compact('product','reviews'));
    }

    public function addReview(Request $request, $id)
    {
        $product = Product::findOrFail($id);
        $data = $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'content' => 'required|string|max:1000',
        ]);
        Review::create([
            'product_id' => $product->id,
            'user_id' => optional(session('admin'))->id,
            'rating' => $data['rating'],
            'content' => $data['content'],
        ]);
        return back()->with('success','Đã gửi đánh giá!');
    }
}
