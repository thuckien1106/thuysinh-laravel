<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use Illuminate\Support\Facades\DB;

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
        $product = Product::with('activeDiscount')->findOrFail($data['product_id']);

        $cart = session('cart', []);
        if (isset($cart[$product->id])) {
            $cart[$product->id]['quantity'] += $qty;
        } else {
            $cart[$product->id] = [
                'id' => $product->id,
                'name' => $product->name,
                'price' => (float) $product->final_price,
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
        return back()->withErrors(['cart' => 'Sáº£n pháº©m khÃ´ng tá»“n táº¡i trong giá».']);
    }

    public function remove($id)
    {
        $cart = session('cart', []);
        if (isset($cart[$id])) {
            unset($cart[$id]);
            session(['cart' => $cart]);
            return back()->with('success', 'Đã xóa sản phẩm khỏi giỏ.');
        }
        return back()->withErrors(['cart' => 'Sáº£n pháº©m khÃ´ng tá»“n táº¡i trong giá».']);
    }

    public function applyCoupon(Request $request)
    {
        $data = $request->validate(['code' => 'required|string|max:30']);
        $code = strtoupper(trim($data['code']));
        // Minimal built-in coupons (no DB dependency)
        $coupons = [
            'SALE10' => ['type' => 'percent', 'value' => 10],
            'FREESHIP30K' => ['type' => 'fixed', 'value' => 30000],
            'DATCUTELOVE' => ['type' => 'percent', 'value' => 15], // mÃ£ yÃªu cáº§u: giáº£m 15%
        ];
        if (!isset($coupons[$code])) {
            session()->forget('coupon');
            return back()->withErrors(['coupon' => 'MÃ£ khÃ´ng há»£p lá»‡.']);
        }
        // One-time usage per user for DATCUTELOVE
        $userId = optional(session('admin'))->id;
        if ($code === 'DATCUTELOVE' && $userId) {
            $used = DB::table('coupon_usages')->where(['user_id'=>$userId,'code'=>$code])->exists();
            if ($used) {
                return back()->withErrors(['coupon' => 'Báº¡n Ä‘Ã£ sá»­ dá»¥ng mÃ£ nÃ y trÆ°á»›c Ä‘Ã³.']);
            }
            DB::table('coupon_usages')->updateOrInsert(
                ['user_id'=>$userId,'code'=>$code],
                ['used_at'=>now()]
            );
        }
        // Apply to session for current checkout
        session(['coupon' => array_merge(['code' => $code], $coupons[$code])]);
        return back()->with('success', 'Đã áp dụng mã khuyến mãi.');
    }

    public function saveCoupon(Request $request)
    {
        $data = $request->validate(['code' => 'required|string|max:30']);
        $code = strtoupper(trim($data['code']));
        // LÆ°u láº¡i Ä‘á»ƒ hiá»ƒn thá»‹ á»Ÿ trang thanh toÃ¡n; khÃ´ng tÃ­nh giáº£m á»Ÿ Ä‘Ã¢y
        session(['saved_coupon' => ['code' => $code]]);
        return back()->with('success', 'Đã lưu mã giảm giá. Áp dụng khi thanh toán.');
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
