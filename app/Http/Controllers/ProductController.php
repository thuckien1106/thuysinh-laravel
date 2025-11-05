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
        $sale = $request->boolean('sale');

        $query = Product::query()->with('activeDiscount');
        if ($q) $query->where('name', 'like', "%$q%");
        if ($category) $query->where('category_id', $category);
        if ($brand) $query->where('brand_id', $brand);
        if ($min !== null && $min !== '') $query->where('price', '>=', (float)$min);
        if ($max !== null && $max !== '') $query->where('price', '<=', (float)$max);
        if ($sale) {
            $query->whereHas('discounts', function($s){
                $s->where('start_at','<=', now())->where('end_at','>=', now());
            });
        }

        $products = $query->orderBy('created_at', 'desc')->paginate(12)->withQueryString();
        $categories = \Illuminate\Support\Facades\DB::table('categories')->orderBy('name')->get();
        $brands = \Illuminate\Support\Facades\DB::table('brands')->orderBy('name')->get();
        $topIds = \Illuminate\Support\Facades\DB::table('order_details as od')
            ->select('od.product_id', \Illuminate\Support\Facades\DB::raw('SUM(od.quantity) as qty'))
            ->groupBy('od.product_id')
            ->orderByDesc(\Illuminate\Support\Facades\DB::raw('SUM(od.quantity)'))
            ->limit(8)
            ->pluck('od.product_id')
            ->toArray();
        // Ratings & sold counts
        $ids = $products->pluck('id')->all();
        $ratingAgg = Review::selectRaw('product_id, AVG(rating) as avg, COUNT(*) as cnt')
            ->whereIn('product_id', $ids)->groupBy('product_id')->get()->keyBy('product_id');
        $ratings = [];
        foreach ($ratingAgg as $pid => $row) { $ratings[$pid] = ['avg'=>(float)$row->avg, 'cnt'=>(int)$row->cnt]; }
        $soldRows = \Illuminate\Support\Facades\DB::table('order_details as od')
            ->join('orders as o','o.id','=','od.order_id')
            ->whereIn('od.product_id', $ids)->where('o.status','completed')
            ->selectRaw('od.product_id, SUM(od.quantity) as s')->groupBy('od.product_id')->get();
        $soldCounts = [];
        foreach ($soldRows as $r) { $soldCounts[$r->product_id] = (int)$r->s; }
        return view('products_index', compact('products', 'q', 'category', 'brand', 'min', 'max', 'sale', 'categories', 'brands', 'topIds','ratings','soldCounts'));
    }
    public function show($id)
    {
        $product = Product::with('activeDiscount')->findOrFail($id);
        $topIds = \Illuminate\Support\Facades\DB::table('order_details as od')
            ->select('od.product_id', \Illuminate\Support\Facades\DB::raw('SUM(od.quantity) as qty'))
            ->groupBy('od.product_id')
            ->orderByDesc(\Illuminate\Support\Facades\DB::raw('SUM(od.quantity)'))
            ->limit(8)
            ->pluck('od.product_id')
            ->toArray();
        $isTop = in_array($product->id, $topIds, true);
        $reviews = Review::where('product_id', $product->id)->orderBy('id','desc')->limit(20)->get();
        $avgRating = (float) (Review::where('product_id',$product->id)->avg('rating') ?? 0);
        $reviewCount = (int) Review::where('product_id',$product->id)->count();
        $soldCount = (int) \Illuminate\Support\Facades\DB::table('order_details as od')
            ->join('orders as o','o.id','=','od.order_id')
            ->where('od.product_id',$product->id)
            ->where('o.status','completed')
            ->sum('od.quantity');
        return view('product_detail', compact('product','reviews','isTop','avgRating','reviewCount','soldCount'));
    }

    public function sale(Request $request)
    {
        $products = Product::with('activeDiscount')
            ->whereHas('discounts', function($q){
                $q->where('start_at','<=', now())->where('end_at','>=', now());
            })
            ->orderByDesc('id')
            ->paginate(12)
            ->withQueryString();
        $topIds = \Illuminate\Support\Facades\DB::table('order_details as od')
            ->select('od.product_id', \Illuminate\Support\Facades\DB::raw('SUM(od.quantity) as qty'))
            ->groupBy('od.product_id')
            ->orderByDesc(\Illuminate\Support\Facades\DB::raw('SUM(od.quantity)'))
            ->limit(8)
            ->pluck('od.product_id')
            ->toArray();
        // Lấy danh mục/brand cho menu dropdown header nếu có
        $categories = \Illuminate\Support\Facades\DB::table('categories')->orderBy('name')->get();
        $brands = \Illuminate\Support\Facades\DB::table('brands')->orderBy('name')->get();
        return view('products_sale', compact('products','categories','brands','topIds'));
    }

    public function addReview(Request $request, $id)
    {
        $user = session('admin');
        if (!$user) { return redirect()->route('login'); }
        $product = Product::findOrFail($id);
        $data = $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'content' => 'required|string|max:1000',
        ]);
        // Chỉ cho phép đánh giá sau khi đã nhận hàng (đơn hoàn thành)
        $hasCompletedOrder = \App\Models\Order::where('user_id', $user->id)
            ->where('status','completed')
            ->whereIn('id', function($q) use ($product){
                $q->select('order_id')->from('order_details')->where('product_id',$product->id);
            })
            ->exists();
        if (!$hasCompletedOrder) {
            return back()->withErrors(['review' => 'Bạn chỉ có thể đánh giá sau khi đã nhận hàng.']);
        }
        // Mỗi người dùng chỉ đánh giá 1 lần cho mỗi sản phẩm
        $already = Review::where('product_id',$product->id)->where('user_id',$user->id)->exists();
        if ($already) {
            return back()->withErrors(['review' => 'Bạn đã đánh giá sản phẩm này rồi.']);
        }
        Review::create([
            'product_id' => $product->id,
            'user_id' => $user->id,
            'rating' => $data['rating'],
            'content' => $data['content'],
            'created_at' => now(),
        ]);
        return back()->with('success','Đã gửi đánh giá!');
    }
}
