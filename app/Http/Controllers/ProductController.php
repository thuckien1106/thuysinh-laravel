<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Review;
use App\Models\Order;
use App\Models\OrderDetail;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    /**
     * Helper: Lấy danh sách ID sản phẩm bán chạy nhất
     */
    private function getTopProductIds($limit = 8)
    {
        return DB::table('order_details as od')
            ->select('od.product_id', DB::raw('SUM(od.quantity) as qty'))
            ->groupBy('od.product_id')
            ->orderByDesc(DB::raw('SUM(od.quantity)'))
            ->limit($limit)
            ->pluck('od.product_id')
            ->toArray();
    }

    public function index(Request $request)
    {
        $q = $request->get('q');
        $category = $request->get('category');
        $brand = $request->get('brand');
        $min = $request->get('min_price');
        $max = $request->get('max_price');
        $sale = $request->boolean('sale');

        $query = Product::query()->with('activeDiscount');

        if ($q) {
            $query->where('name', 'like', "%$q%");
        }
        if ($category) {
            $query->where('category_id', $category);
        }
        if ($brand) {
            $query->where('brand_id', $brand);
        }
        if ($min !== null && $min !== '') {
            $query->where('price', '>=', (float)$min);
        }
        if ($max !== null && $max !== '') {
            $query->where('price', '<=', (float)$max);
        }
        if ($sale) {
            $query->whereHas('discounts', function ($s) {
                $s->where('start_at', '<=', now())
                  ->where('end_at', '>=', now());
            });
        }

        $products = $query->orderBy('created_at', 'desc')
                          ->paginate(12)
                          ->withQueryString();

        $categories = DB::table('categories')->orderBy('name')->get();
        $brands = DB::table('brands')->orderBy('name')->get();
        
        // Sử dụng hàm helper đã tách
        $topIds = $this->getTopProductIds(8);

        // Ratings & sold counts calculation
        $ids = $products->pluck('id')->all();
        
        // Tính trung bình đánh giá
        $ratingAgg = Review::selectRaw('product_id, AVG(rating) as avg, COUNT(*) as cnt')
            ->whereIn('product_id', $ids)
            ->groupBy('product_id')
            ->get()
            ->keyBy('product_id');

        $ratings = [];
        foreach ($ratingAgg as $pid => $row) {
            $ratings[$pid] = ['avg' => (float)$row->avg, 'cnt' => (int)$row->cnt];
        }

        // Tính số lượng đã bán
        $soldRows = DB::table('order_details as od')
            ->join('orders as o', 'o.id', '=', 'od.order_id')
            ->whereIn('od.product_id', $ids)
            ->where('o.status', 'completed')
            ->selectRaw('od.product_id, SUM(od.quantity) as s')
            ->groupBy('od.product_id')
            ->get();

        $soldCounts = [];
        foreach ($soldRows as $r) {
            $soldCounts[$r->product_id] = (int)$r->s;
        }

        return view('products_index', compact(
            'products', 'q', 'category', 'brand', 'min', 'max', 'sale', 
            'categories', 'brands', 'topIds', 'ratings', 'soldCounts'
        ));
    }

    public function show($id)
    {
        $product = Product::with('activeDiscount')->findOrFail($id);
        
        // Sử dụng hàm helper đã tách
        $topIds = $this->getTopProductIds(8);
        $isTop = in_array($product->id, $topIds, true);

        $reviews = Review::where('product_id', $product->id)
            ->orderBy('id', 'desc')
            ->limit(20)
            ->get();

        $avgRating = (float) (Review::where('product_id', $product->id)->avg('rating') ?? 0);
        $reviewCount = (int) Review::where('product_id', $product->id)->count();

        $soldCount = (int) DB::table('order_details as od')
            ->join('orders as o', 'o.id', '=', 'od.order_id')
            ->where('od.product_id', $product->id)
            ->where('o.status', 'completed')
            ->sum('od.quantity');

        return view('product_detail', compact('product', 'reviews', 'isTop', 'avgRating', 'reviewCount', 'soldCount'));
    }

    public function sale(Request $request)
    {
        $products = Product::with('activeDiscount')
            ->whereHas('discounts', function ($q) {
                $q->where('start_at', '<=', now())
                  ->where('end_at', '>=', now());
            })
            ->orderByDesc('id')
            ->paginate(12)
            ->withQueryString();

        // Sử dụng hàm helper đã tách
        $topIds = $this->getTopProductIds(8);

        // Lấy danh mục/brand cho menu dropdown header nếu có
        $categories = DB::table('categories')->orderBy('name')->get();
        $brands = DB::table('brands')->orderBy('name')->get();

        return view('products_sale', compact('products', 'categories', 'brands', 'topIds'));
    }

    public function addReview(Request $request, $id)
    {
        $user = session('admin');
        if (!$user) {
            return redirect()->route('login');
        }

        $product = Product::findOrFail($id);

        $data = $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'content' => 'required|string|max:1000',
            'order_id' => 'required|integer|exists:orders,id',
        ]);

        $order = Order::findOrFail($data['order_id']);
        
        // Kiểm tra order thuộc về user hiện tại
        if ($order->user_id !== $user->id) {
            return back()->withErrors(['review' => 'Đơn hàng này không phải của bạn.']);
        }

        // Kiểm tra đơn hàng đã hoàn thành
        $statusCode = Order::normalizeStatus($order->getRawOriginal('status') ?? $order->status);
        if ($statusCode !== 'completed') {
            return back()->withErrors(['review' => 'Bạn chỉ có thể đánh giá sau khi đã nhận hàng.']);
        }

        // Kiểm tra sản phẩm có trong đơn hàng này
        $inOrder = OrderDetail::where('order_id', $order->id)
            ->where('product_id', $product->id)
            ->exists();

        if (!$inOrder) {
            return back()->withErrors(['review' => 'Sản phẩm này không có trong đơn hàng.']);
        }

        // Kiểm tra đã đánh giá sản phẩm này trong đơn hàng này chưa
        $already = Review::where('product_id', $product->id)
            ->where('user_id', $user->id)
            ->where('order_id', $order->id)
            ->exists();

        if ($already) {
            return back()->withErrors(['review' => 'Bạn đã đánh giá sản phẩm này trong đơn hàng này rồi.']);
        }

        Review::create([
            'product_id' => $product->id,
            'user_id' => $user->id,
            'order_id' => $order->id,
            'rating' => $data['rating'],
            'content' => $data['content'],
            'created_at' => now(),
        ]);

        // Nếu là AJAX request, return JSON
        if ($request->expectsJson()) {
            return response()->json(['success' => true, 'message' => 'Đã gửi đánh giá!']);
        }

        return back()->with('success', 'Đã gửi đánh giá!');
    }
}