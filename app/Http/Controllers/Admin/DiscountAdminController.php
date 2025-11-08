<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\{Product, ProductDiscount};
use App\Http\Requests\Admin\StoreProductDiscountRequest;
use App\Http\Requests\Admin\UpdateProductDiscountRequest;
use Carbon\Carbon;

class DiscountAdminController extends Controller
{
    // Danh sách chương trình giảm giá
    public function index(Request $request)
    {
        $status = $request->query('status'); // active | upcoming | expired | all
        $productId = $request->query('product_id');

        $query = ProductDiscount::query()->with('product');

        if ($productId) {
            $query->where('product_id', $productId);
        }

        if ($status === 'active') {
            $query->where('start_at', '<=', now())
                  ->where('end_at', '>=', now());
        } elseif ($status === 'upcoming') {
            $query->where('start_at', '>', now());
        } elseif ($status === 'expired') {
            $query->where('end_at', '<', now());
        }

        $discounts = $query->orderByDesc('start_at')
                           ->paginate(15)
                           ->withQueryString();

        $products = Product::orderBy('name')->get(['id', 'name']);

        return view('admin.discounts.index', compact('discounts', 'products', 'status', 'productId'));
    }

    // Hiển thị form tạo giảm giá
    public function create()
    {
        // Chỉ hiện thị sản phẩm chưa có giảm giá hiệu lực
        $products = Product::orderBy('name')
            ->whereDoesntHave('discounts', function ($q) {
                $q->where('end_at', '>=', now());
            })
            ->get(['id', 'name']);

        return view('admin.discounts.create', compact('products'));
    }

    // Lưu giảm giá mới
    public function store(StoreProductDiscountRequest $request)
    {
        $data = $request->validated();

        // Đảm bảo không trùng lặp/đè khoảng thời gian với cùng sản phẩm
        $overlap = ProductDiscount::where('product_id', $data['product_id'])
            ->where(function ($q) use ($data) {
                $q->whereBetween('start_at', [$data['start_at'], $data['end_at']])
                  ->orWhereBetween('end_at', [$data['start_at'], $data['end_at']])
                  ->orWhere(function ($q2) use ($data) {
                      $q2->where('start_at', '<=', $data['start_at'])
                         ->where('end_at', '>=', $data['end_at']);
                  });
            })
            ->exists();

        if ($overlap) {
            return back()->withErrors([
                'discount' => 'Khoảng thời gian giảm giá bị chồng lấn với bản ghi khác của cùng sản phẩm.'
            ])->withInput();
        }

        ProductDiscount::create($data);

        return redirect()->route('admin.discounts.index')->with('success', 'Đã tạo giảm giá.');
    }

    // Form chỉnh sửa
    public function edit(ProductDiscount $discount)
    {
        // Chỉ hiển thị sản phẩm hiện tại của bản ghi giảm giá
        $products = Product::where('id', $discount->product_id)->get(['id', 'name']);
        return view('admin.discounts.edit', compact('discount', 'products'));
    }

    // Cập nhật giảm giá
    public function update(UpdateProductDiscountRequest $request, ProductDiscount $discount)
    {
        $data = $request->validated();

        // Nếu đổi sang sản phẩm khác, kiểm tra overlap với các bản ghi khác
        if ($discount->product_id != $data['product_id']) {
            $targetHasActive = ProductDiscount::where('product_id', $data['product_id'])
                ->where('end_at', '>=', now())
                ->where('id', '!=', $discount->id)
                ->exists();

            if ($targetHasActive) {
                return back()->withErrors([
                    'discount' => 'Sản phẩm đích đang có giảm giá hiệu lực. Hãy xóa hoặc đợi hết hạn trước khi chuyển.'
                ]);
            }
        }

        $overlap = ProductDiscount::where('product_id', $data['product_id'])
            ->where('id', '!=', $discount->id)
            ->where(function ($q) use ($data) {
                $q->whereBetween('start_at', [$data['start_at'], $data['end_at']])
                  ->orWhereBetween('end_at', [$data['start_at'], $data['end_at']])
                  ->orWhere(function ($q2) use ($data) {
                      $q2->where('start_at', '<=', $data['start_at'])
                         ->where('end_at', '>=', $data['end_at']);
                  });
            })
            ->exists();

        if ($overlap) {
            return back()->withErrors([
                'discount' => 'Khoảng thời gian giảm giá bị chồng lấn với bản ghi khác của cùng sản phẩm.'
            ])->withInput();
        }

        $discount->update($data);

        return redirect()->route('admin.discounts.index')->with('success', 'Đã cập nhật giảm giá.');
    }

    // Xóa giảm giá
    public function destroy(ProductDiscount $discount)
    {
        $discount->delete();
        return back()->with('success', 'Đã xóa giảm giá.');
    }
}

