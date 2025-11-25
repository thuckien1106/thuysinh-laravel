<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Payment;
use App\Models\Product;
use App\Models\Shipment;

class OrderAdminController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->get('status');
        $q = trim((string) $request->get('q'));
        $from = $request->get('from');
        $to = $request->get('to');
        $sort = $request->get('sort'); // date_desc, date_asc, total_desc, total_asc

        $statusOptions = \App\Models\Order::STATUS_OPTIONS;
        $norm = \App\Models\Order::normalizeStatus($status);

        $query = Order::query();
        if ($norm) $query->where('status', $norm);
        if ($q !== '') {
            $query->where(function($w) use ($q){
                $w->where('customer_name','like',"%$q%")
                  ->orWhere('id', intval($q));
            });
        }
        if ($from) $query->whereDate('created_at','>=',$from);
        if ($to) $query->whereDate('created_at','<=',$to);

        if ($sort==='date_asc') $query->orderBy('created_at','asc');
        elseif ($sort==='total_desc') $query->orderBy('total','desc');
        elseif ($sort==='total_asc') $query->orderBy('total','asc');
        else $query->orderBy('created_at','desc');

        $orders = $query->paginate(15)->withQueryString();
        return view('admin.orders.index', compact('orders', 'status','q','from','to','sort','statusOptions'));
    }

    public function exportCsv(Request $request)
    {
        $status = $request->get('status');
        $q = trim((string) $request->get('q'));
        $from = $request->get('from');
        $to = $request->get('to');

        $query = Order::query();
        $norm = \App\Models\Order::normalizeStatus($status);
        if ($norm) $query->where('status', $norm);
        if ($q !== '') {
            $query->where(function($w) use ($q){
                $w->where('customer_name','like',"%$q%")
                  ->orWhere('id', intval($q));
            });
        }
        if ($from) $query->whereDate('created_at','>=',$from);
        if ($to) $query->whereDate('created_at','<=',$to);

        $rows = $query->orderBy('created_at','desc')->get(['id','customer_name','customer_address','total','status','created_at']);
        $filename = 'orders_'.date('Ymd_His').'.csv';
        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];

        $callback = function() use ($rows){
            $out = fopen('php://output','w');
            // BOM for Excel UTF-8
            fprintf($out, chr(0xEF).chr(0xBB).chr(0xBF));
            fputcsv($out, ['ID','KhÃ¡ch hÃ ng','Äá»‹a chá»‰','Tá»•ng','Tráº¡ng thÃ¡i','NgÃ y']);
            foreach ($rows as $r) {
                $label = \App\Models\Order::STATUS_OPTIONS[$r->status] ?? $r->status;
                fputcsv($out, [$r->id, $r->customer_name, $r->customer_address, $r->total, $label, $r->created_at]);
            }
            fclose($out);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function show($id)
    {
        $order = Order::findOrFail($id);
        $items = OrderDetail::where('order_id', $order->id)->get();
        $productMap = Product::whereIn('id', $items->pluck('product_id'))
            ->get()->keyBy('id');
        $items->transform(function ($it) use ($productMap) {
            $prod = $productMap->get($it->product_id);
            $it->product_name = $prod->name ?? ('#'.$it->product_id);
            $it->product_image = $prod->image ?? 'default.png';
            return $it;
        });
        $payment = Payment::where('order_id', $order->id)->orderByDesc('id')->first();
        $shipment = Shipment::where('order_id', $order->id)->orderByDesc('id')->first();
        return view('admin.orders.show', compact('order','items','payment','shipment'));
    }

    public function updateStatus(Request $request, $id)
    {
        $order = Order::findOrFail($id);
        $data = $request->validate(['status' => 'required|string|max:50']);
        $code = \App\Models\Order::normalizeStatus($data['status']);
        if (!array_key_exists($code ?? '', \App\Models\Order::STATUS_OPTIONS)) {
            return back()->withErrors(['status' => 'GiÃ¡ trá»‹ tráº¡ng thÃ¡i khÃ´ng há»£p lá»‡.']);
        }
        $order->update(['status' => $code]);

        // Auto-sync shipment/payment to keep consistency
        $shipment = Shipment::firstOrCreate(['order_id' => $order->id], ['carrier'=>'local','status'=>'pending']);
        $payment = Payment::firstOrCreate(['order_id' => $order->id], ['method'=>'cod','amount'=>$order->total,'status'=>'pending']);

        if ($code === 'shipping') {
            $shipment->update(['status'=>'shipping', 'shipped_at'=>now()]);
        } elseif ($code === 'completed') {
            $shipment->update(['status'=>'delivered', 'delivered_at'=>now()]);
            // Mark paid for demo if delivered
            $payment->update(['status'=>'paid', 'paid_at'=>now()]);
        } elseif ($code === 'cancelled') {
            $shipment->update(['status'=>'cancelled']);
            // Leave payment pending (or failed) â€“ demo keeps pending
        }
        return back()->with('success', 'Đã cập nhật trạng thái đơn hàng.');
    }
}
