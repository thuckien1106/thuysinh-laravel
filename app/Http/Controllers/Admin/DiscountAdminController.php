<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\{Product, ProductDiscount};
use Illuminate\Support\Facades\DB;

class DiscountAdminController extends Controller
{
    public function index(Request $request)
    {
        // Tự động xoá các bản ghi hết hạn
        \App\Models\ProductDiscount::where('end_at','<', now())->delete();
        $status = $request->query('status'); // active|upcoming|expired|all
        $productId = $request->query('product_id');

        $query = ProductDiscount::query()->with('product');
        if ($productId) $query->where('product_id', $productId);
        if ($status === 'active') {
            $query->where('start_at','<=', now())->where('end_at','>=', now());
        } elseif ($status === 'upcoming') {
            $query->where('start_at','>', now());
        } elseif ($status === 'expired') {
            $query->where('end_at','<', now());
        }
        $discounts = $query->orderByDesc('start_at')->paginate(15)->withQueryString();
        $products = Product::orderBy('name')->get(['id','name']);
        return view('admin.discounts.index', compact('discounts','products','status','productId'));
    }

    public function create()
    {
        // Chỉ hiển thị sản phẩm CHƯA có giảm giá đang hiệu lực
        $products = Product::orderBy('name')
            ->whereDoesntHave('discounts', function($q){
                $q->where('end_at','>=', now());
            })
            ->get(['id','name']);
        return view('admin.discounts.create', compact('products'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'product_id' => 'required|integer|exists:products,id',
            'percent' => 'required|integer|min:1|max:90',
            'start_at' => 'required|date',
            'end_at' => 'required|date|after:start_at',
            'note' => 'nullable|string|max:120',
        ]);
        // Chỉ tạo khi sản phẩm KHÔNG có giảm giá đang hiệu lực
        $hasActive = ProductDiscount::where('product_id', $data['product_id'])
            ->where('end_at', '>=', now())
            ->exists();
        if ($hasActive) {
            return back()->withErrors(['discount' => 'Sản phẩm đang có giảm giá hiệu lực. Hãy xóa/đợi hết hạn trước khi tạo mới.']);
        }
        ProductDiscount::create($data);
        return redirect()->route('admin.discounts.index')->with('success','Đã tạo giảm giá.');
    }

    public function edit($id)
    {
        $discount = ProductDiscount::findOrFail($id);
        // Khi sửa: chỉ cho phép sửa bản ghi hiện tại của chính sản phẩm đó
        $products = Product::where('id', $discount->product_id)->get(['id','name']);
        return view('admin.discounts.edit', compact('discount','products'));
    }

    public function update(Request $request, $id)
    {
        $discount = ProductDiscount::findOrFail($id);
        $data = $request->validate([
            'product_id' => 'required|integer|exists:products,id',
            'percent' => 'required|integer|min:1|max:90',
            'start_at' => 'required|date',
            'end_at' => 'required|date|after:start_at',
            'note' => 'nullable|string|max:120',
        ]);
        // Nếu đổi sang product khác, không cho phép nếu product đó đang có giảm giá hiệu lực
        if ($discount->product_id != $data['product_id']) {
            $targetHasActive = ProductDiscount::where('product_id', $data['product_id'])
                ->where('end_at', '>=', now())
                ->where('id', '!=', $discount->id)
                ->exists();
            if ($targetHasActive) {
                return back()->withErrors(['discount' => 'Sản phẩm đích đang có giảm giá hiệu lực. Hãy xóa/đợi hết hạn trước khi chuyển.']);
            }
        }
        $discount->update($data);
        return redirect()->route('admin.discounts.index')->with('success','Đã cập nhật giảm giá.');
    }

    public function destroy($id)
    {
        $discount = ProductDiscount::findOrFail($id);
        $discount->delete();
        return back()->with('success','Đã xóa giảm giá.');
    }
}
