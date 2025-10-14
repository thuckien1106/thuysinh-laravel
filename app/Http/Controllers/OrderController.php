<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Address;
use App\Models\Payment;
use App\Models\Shipment;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function checkout()
    {
        $cart = session('cart', []);
        $total = 0;
        foreach ($cart as $item) {
            $total += $item['price'] * $item['quantity'];
        }

        // Resolve linked customer_id for logged-in user (if exists)
        $customerId = null;
        if (session('admin')) {
            $cust = DB::table('customers')->where('email', session('admin')->email)->first();
            if ($cust) { $customerId = $cust->id; }
        }

        // nothing here
        $coupon = session('coupon');
        $discount = 0;
        if ($coupon) {
            if (($coupon['type'] ?? '') === 'percent') {
                $discount = round($total * (($coupon['value'] ?? 0) / 100));
            } elseif (($coupon['type'] ?? '') === 'fixed') {
                $discount = (int)($coupon['value'] ?? 0);
            }
        }
        $total = max(0, $total - $discount);
        $prefill = null;
        if (session('admin')) {
            $prefill = DB::table('customers')->where('email', session('admin')->email)->first();
            $addr = null;
            if ($prefill) {
                $addr = DB::table('addresses')
                    ->where('customer_id', $prefill->id)
                    ->where('is_default', 1)
                    ->first();
                if ($addr) {
                    $prefill->address = $addr->address_line;
                    $prefill->full_name = $prefill->full_name ?: $addr->full_name;
                    $prefill->phone = $prefill->phone ?: $addr->phone;
                    // Prefill for province/district/ward selects
                    $prefill->province = $addr->province;
                    $prefill->district = $addr->district;
                    $prefill->ward     = $addr->ward;
                }
            }
            // Chưa có thông tin tài khoản/địa chỉ mặc định → chuyển sang trang tài khoản
            if (!$prefill || empty($prefill->full_name) || !$addr || empty($addr->address_line)) {
                return redirect()->route('account.profile')
                    ->with('warning', 'Vui lòng nhập thông tin tài khoản và địa chỉ nhận hàng trước khi thanh toán.');
            }
        }
        return view('checkout', compact('cart', 'total','prefill'));
    }

    public function process(Request $request)
    {
        $cart = session('cart', []);
        if (empty($cart)) {
            return redirect()->route('cart')->withErrors(['cart' => 'Giỏ hàng đang trống.']);
        }

        $data = $request->validate([
            'customer_name' => 'required|string|max:120',
            'customer_address' => 'required|string|max:255',
            'customer_phone' => 'nullable|string|max:30',
            'payment_method' => 'nullable|in:cod,online'
        ]);
        
        // Resolve linked customer_id for logged-in user (if exists)
        $customerId = null;
        if (session('admin')) {
            $cust = DB::table('customers')->where('email', session('admin')->email)->first();
            if ($cust) { $customerId = $cust->id; }
        }

        $total = 0;
        foreach ($cart as $item) {
            $total += $item['price'] * $item['quantity'];
        }

        $order = Order::create([
            'user_id' => optional(session('admin'))->id,
            'total' => $total,
            'status' => 'Đang xử lý',
            'customer_name' => $data['customer_name'],
            'customer_address' => $data['customer_address'],
        ]);

        foreach ($cart as $item) {
            OrderDetail::create([
                'order_id' => $order->id,
                'product_id' => $item['id'],
                'quantity' => $item['quantity'],
                'price' => $item['price'],
            ]);
        }

        // Ensure canonical status code stored
        Order::where('id', $order->id)->update(['status' => 'processing']);

        // Attach customer_id if available
        if ($customerId) {
            Order::where('id', $order->id)->update(['customer_id' => $customerId]);
        }

        // If logged in and customer record exists, store/update default address for that customer
        if ($customerId) {
            Address::create([
                'customer_id' => $customerId,
                'full_name' => $data['customer_name'],
                'phone' => $data['customer_phone'] ?? null,
                'address_line' => $data['customer_address'],
                'is_default' => 1,
            ]);
            // Also sync key fields to customers table
            DB::table('customers')->where('id',$customerId)->update([
                'full_name' => $data['customer_name'],
                'phone' => $data['customer_phone'] ?? null,
                'address' => $data['customer_address'],
            ]);
        }

        // Create payment record (pending for COD)
        Payment::create([
            'order_id' => $order->id,
            'method' => $data['payment_method'] ?? 'cod',
            'amount' => $total,
            'status' => ($data['payment_method'] ?? 'cod') === 'online' ? 'paid' : 'pending',
        ]);

        // Create initial shipment record
        Shipment::create([
            'order_id' => $order->id,
            'carrier' => 'local',
            'status' => 'pending',
        ]);

        // Clear cart and coupon
        session()->forget('cart');
        session()->forget('coupon');

        return redirect()->route('order.thankyou', $order->id)->with('success', 'Đặt hàng thành công!');
    }

    public function thankyou($id)
    {
        $order = Order::findOrFail($id);
        $user = session('admin');
        if ($user && $order->user_id && $order->user_id !== $user->id) {
            return redirect()->route('orders.mine')->with('error', 'Bạn không có quyền truy cập đơn hàng này.');
        }
        $items = OrderDetail::where('order_id', $order->id)->get();
        // Attach product name and image for display
        $productMap = \App\Models\Product::whereIn('id', $items->pluck('product_id'))
            ->get()->keyBy('id');
        $items->transform(function ($it) use ($productMap) {
            $p = $productMap->get($it->product_id);
            $it->product_name = $p->name ?? ('#'.$it->product_id);
            $it->product_image = $p->image ?? 'placeholder.webp';
            return $it;
        });
        $payment = Payment::where('order_id', $order->id)->orderByDesc('id')->first();
        $shipment = Shipment::where('order_id', $order->id)->orderByDesc('id')->first();

        return view('order_thankyou', compact('order', 'items', 'payment', 'shipment'));
    }

    public function myOrders(Request $request)
    {
        $user = session('admin');
        if (!$user) {
            return redirect()->route('login');
        }

        $statusParam = $request->query('status');
        $code = Order::normalizeStatus($statusParam);
        $query = Order::where('user_id', $user->id);
        if (in_array($code, ['completed','cancelled','shipping'])) {
            $query->where('status', $code);
        }
        $orders = $query->orderByDesc('id')->limit(50)->get();
        // Map latest payment and shipment per order
        $payments = Payment::whereIn('order_id', $orders->pluck('id'))
            ->orderBy('id')->get()->groupBy('order_id');
        $shipments = Shipment::whereIn('order_id', $orders->pluck('id'))
            ->orderBy('id')->get()->groupBy('order_id');

        return view('orders_mine', compact('orders', 'payments', 'shipments', 'statusParam'));
    }

    public function cancel($id)
    {
        $user = session('admin');
        if (!$user) return redirect()->route('login');
        $order = Order::findOrFail($id);
        if ($order->user_id !== $user->id) return redirect()->route('orders.mine')->with('error','Bạn không thể hủy đơn này.');
        // Only allow cancel when still processing
        $statusCode = Order::normalizeStatus($order->getRawOriginal('status') ?? $order->status);
        if ($statusCode !== 'processing') {
            return back()->with('warning','Chỉ hủy được khi đơn đang xử lý.');
        }
        $order->update(['status'=>'cancelled']);
        // Update shipment
        Shipment::where('order_id',$order->id)->update(['status'=>'cancelled']);
        return back()->with('success','Đã hủy đơn hàng.');
    }
}
