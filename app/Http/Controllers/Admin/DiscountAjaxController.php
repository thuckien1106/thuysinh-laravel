<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\{Product, ProductDiscount};
use App\Http\Requests\Admin\StoreProductDiscountRequest;
use App\Http\Requests\Admin\UpdateProductDiscountRequest;

class DiscountAjaxController extends Controller
{
    public function modalCreate()
    {
        $products = Product::orderBy('name')
            ->whereDoesntHave('discounts', function($q){ $q->where('end_at','>=', now()); })
            ->get(['id','name']);
        $discount = null;
        $action = route('admin.discounts.ajax.store');
        $method = 'POST';
        $readonlyProduct = false;
        return view('admin.discounts._form', compact('products','discount','action','method','readonlyProduct'));
    }

    public function modalEdit(ProductDiscount $discount)
    {
        $products = Product::where('id', $discount->product_id)->get(['id','name']);
        $action = route('admin.discounts.ajax.update', $discount->id);
        // Use POST to match route definition (method spoofing would cause 405)
        $method = 'POST';
        $readonlyProduct = true;
        return view('admin.discounts._form', compact('products','discount','action','method','readonlyProduct'));
    }

    public function ajaxStore(StoreProductDiscountRequest $request)
    {
        $data = $request->validated();
        $overlap = ProductDiscount::where('product_id', $data['product_id'])->where(function($q) use ($data){
            $q->whereBetween('start_at', [$data['start_at'], $data['end_at']])
              ->orWhereBetween('end_at', [$data['start_at'], $data['end_at']])
              ->orWhere(function($q2) use ($data){ $q2->where('start_at','<=',$data['start_at'])->where('end_at','>=',$data['end_at']); });
        })->exists();
        if ($overlap) return response()->json(['ok'=>false,'message'=>'Khoảng thời gian bị chồng lấn.'], 422);
        $d = ProductDiscount::create($data);
        $d->load('product');
        $html = view('admin.discounts._row', ['d'=>$d])->render();
        return response()->json(['ok'=>true,'id'=>$d->id,'html'=>$html]);
    }

    public function ajaxUpdate(UpdateProductDiscountRequest $request, ProductDiscount $discount)
    {
        $data = $request->validated();
        $overlap = ProductDiscount::where('product_id', $data['product_id'])
            ->where('id','!=',$discount->id)
            ->where(function($q) use ($data){
                $q->whereBetween('start_at', [$data['start_at'], $data['end_at']])
                  ->orWhereBetween('end_at', [$data['start_at'], $data['end_at']])
                  ->orWhere(function($q2) use ($data){ $q2->where('start_at','<=',$data['start_at'])->where('end_at','>=',$data['end_at']); });
            })->exists();
        if ($overlap) return response()->json(['ok'=>false,'message'=>'Khoảng thời gian bị chồng lấn.'], 422);
        $discount->update($data);
        $discount->load('product');
        $html = view('admin.discounts._row', ['d'=>$discount])->render();
        return response()->json(['ok'=>true,'id'=>$discount->id,'html'=>$html]);
    }
}
