<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;

class PageController extends Controller
{
    public function home()
    {
        // Sản phẩm đang sale
        $saleProducts = Product::with('activeDiscount')
            ->whereHas('discounts', function($q){
                $q->where('start_at','<=', now())->where('end_at','>=', now());
            })
            ->orderByDesc('id')
            ->limit(12)
            ->get();

        // IDs sản phẩm top bán chạy
        $topIds = \Illuminate\Support\Facades\DB::table('order_details as od')
            ->select('od.product_id', \Illuminate\Support\Facades\DB::raw('SUM(od.quantity) as qty'))
            ->groupBy('od.product_id')
            ->orderByDesc(\Illuminate\Support\Facades\DB::raw('SUM(od.quantity)'))
            ->limit(12)
            ->pluck('od.product_id')
            ->toArray();
        $topProducts = Product::with('activeDiscount')->whereIn('id', $topIds)->get();

        // Nổi bật = union sale + top (unique theo id)
        $featuredProducts = $saleProducts->concat($topProducts)->unique('id')->take(12);

        // Các sản phẩm khác (không thuộc featured)
        $otherProducts = Product::with('activeDiscount')
            ->whereNotIn('id', $featuredProducts->pluck('id'))
            ->orderByDesc('created_at')
            ->limit(12)
            ->get();

        return view('home', compact('featuredProducts','otherProducts','topIds'));
    }

    public function about()
    {
        return view('about');
    }

    public function contact()
    {
        return view('contact');
    }

    public function submitContact(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:120',
            'email' => 'nullable|email|max:120',
            'message' => 'required|string|max:2000',
        ]);
        \App\Models\Contact::create($data);
        return back()->with('success','Đã gửi liên hệ!');
    }
}
