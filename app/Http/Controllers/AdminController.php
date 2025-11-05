<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class AdminController extends Controller
{
    public function dashboard()
    {
        $today = now()->toDateString();
        $stats = [
            'products' => DB::table('products')->count(),
            'orders' => DB::table('orders')->count(),
            'revenue' => (float) DB::table('orders')->sum('total'),
            'users' => DB::table('users')->count(),
            'orders_today' => DB::table('orders')->whereDate('created_at',$today)->count(),
            'revenue_today' => (float) DB::table('orders')->whereDate('created_at',$today)->sum('total'),
            'processing' => DB::table('orders')->whereIn('status',['processing','Đang xử lý'])->count(),
            'completed' => DB::table('orders')->whereIn('status',['completed','Hoàn thành'])->count(),
        ];

        $from = now()->subDays(6)->startOfDay();
        $rows = DB::table('orders')
            ->select(DB::raw('DATE(created_at) as d'), DB::raw('SUM(total) as s'))
            ->where('created_at', '>=', $from)
            ->groupBy('d')
            ->orderBy('d')
            ->get();
        $map = collect();
        for ($i=0; $i<7; $i++) { $map[$from->copy()->addDays($i)->toDateString()] = 0; }
        foreach ($rows as $r) { $map[$r->d] = (float) $r->s; }
        $chart = [
            'labels' => $map->keys()->map(fn($d)=>date('d/m', strtotime($d)))->values(),
            'data' => $map->values(),
        ];

        // Recent orders
        $recentOrders = DB::table('orders')->orderByDesc('id')->limit(10)->get();
        // Top 5 products by quantity sold
        $topProducts = DB::table('order_details as od')
            ->select('od.product_id', DB::raw('SUM(od.quantity) as qty'), DB::raw('SUM(od.price*od.quantity) as amount'))
            ->groupBy('od.product_id')
            ->orderByDesc(DB::raw('SUM(od.quantity)'))
            ->limit(5)
            ->get();
        $productMap = DB::table('products')->whereIn('id', $topProducts->pluck('product_id'))->pluck('name','id');
        foreach ($topProducts as $tp) { $tp->name = $productMap[$tp->product_id] ?? ('#'.$tp->product_id); }

        // Status distribution (canonical codes)
        $statusLabels = [
            'processing' => 'Đang xử lý',
            'shipping' => 'Đang giao',
            'completed' => 'Hoàn thành',
            'cancelled' => 'Đã hủy',
        ];
        $statusCounts = [];
        foreach (array_keys($statusLabels) as $code) {
            $statusCounts[$code] = DB::table('orders')->where('status', $code)->count();
        }

        // Sales by category (amount)
        $categorySales = DB::table('order_details as od')
            ->join('products as p','p.id','=','od.product_id')
            ->leftJoin('categories as c','c.id','=','p.category_id')
            ->select(DB::raw('COALESCE(c.name, "Khác") as name'), DB::raw('SUM(od.price*od.quantity) as amount'))
            ->groupBy('name')
            ->orderByDesc(DB::raw('SUM(od.price*od.quantity)'))
            ->limit(6)
            ->get();

        // Active discounts for quick display
        $activeDiscounts = DB::table('product_discounts')
            ->where('start_at','<=', now())
            ->where('end_at','>=', now())
            ->get()
            ->keyBy('product_id')
            ->map(fn($d)=>['percent'=>$d->percent,'end_at'=>$d->end_at]);

        return view('admin.dashboard', compact('stats','chart','recentOrders','topProducts','statusLabels','statusCounts','categorySales','activeDiscounts'));
    }

    public function products()
    {
        return view('admin.products');
    }

    public function customers()
    {
        return view('admin.customers');
    }

    public function orders()
    {
        return view('admin.orders');
    }
}
