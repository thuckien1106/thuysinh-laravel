<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;

class CartController extends Controller
{
    public function index()
    {
        $cart = session('cart', []);
        $total = 0;
        foreach ($cart as $item) {
            $total += $item['price'] * $item['quantity'];
        }
        $coupon = session('coupon');
        $discount = 0;
        if ($coupon) {
            if (($coupon['type'] ?? '') === 'percent') {
                $discount = round($total * ($coupon['value'] / 100));
            } elseif (($coupon['type'] ?? '') === 'fixed') {
                $discount = (int)$coupon['value'];
            }
        }
        $grand_total = max(0, $total - $discount);
        return view('cart', compact('cart', 'total', 'coupon', 'discount', 'grand_total'));
    }

    public function add(Request $request)
    {
        $data = $request->validate([
            'product_id' => 'required|integer|exists:products,id',
            'quantity' => 'nullable|integer|min:1'
        ]);

        $qty = $data['quantity'] ?? 1;
        $product = Product::findOrFail($data['product_id']);

        $cart = session('cart', []);
        if (isset($cart[$product->id])) {
            $cart[$product->id]['quantity'] += $qty;
        } else {
            $cart[$product->id] = [
                'id' => $product->id,
                'name' => $product->name,
                'price' => (float) $product->price,
                'image' => $product->image,
                'quantity' => $qty,
            ];
        }

        session(['cart' => $cart]);

        if ($request->ajax()) {
            return response()->json(['ok' => true]);
        }
        return redirect()->route('cart')->with('success', 'Đã thêm vào giỏ hàng.');
    }

    public function update(Request $request, $id)
    {
        $data = $request->validate([
            'quantity' => 'required|integer|min:1'
        ]);
        $cart = session('cart', []);
        if (isset($cart[$id])) {
            $cart[$id]['quantity'] = $data['quantity'];
            session(['cart' => $cart]);
            if ($request->ajax()) { return response()->json(['ok'=>true]); }
            return back()->with('success', 'Cập nhật giỏ hàng thành công.');
        }
        return back()->withErrors(['cart' => 'Sản phẩm không tồn tại trong giỏ.']);
    }

    public function remove($id)
    {
        $cart = session('cart', []);
        if (isset($cart[$id])) {
            unset($cart[$id]);
            session(['cart' => $cart]);
            return back()->with('success', 'Đã xóa sản phẩm khỏi giỏ.');
        }
        return back()->withErrors(['cart' => 'Sản phẩm không tồn tại trong giỏ.']);
    }

    public function applyCoupon(Request $request)
    {
        $data = $request->validate(['code' => 'required|string|max:30']);
        $code = strtoupper(trim($data['code']));
        // Minimal built-in coupons (no DB dependency)
        $coupons = [
            'SALE10' => ['type' => 'percent', 'value' => 10],
            'FREESHIP30K' => ['type' => 'fixed', 'value' => 30000],
        ];
        if (!isset($coupons[$code])) {
            session()->forget('coupon');
            return back()->withErrors(['coupon' => 'Mã không hợp lệ.']);
        }
        session(['coupon' => array_merge(['code' => $code], $coupons[$code])]);
        return back()->with('success', 'Đã áp dụng mã khuyến mãi.');
    }

    public function mini()
    {
        $cart = session('cart', []);
        $total = 0;
        foreach ($cart as $it) { $total += $it['price'] * $it['quantity']; }
        $count = array_sum(array_map(fn($i)=>$i['quantity'] ?? 0, $cart));
        $html = view('partials.mini_cart', compact('cart','total','count'))->render();
        return response()->json(['html'=>$html,'count'=>$count]);
    }
}
