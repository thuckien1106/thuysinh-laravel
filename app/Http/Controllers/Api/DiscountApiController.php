<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ProductDiscount;
use App\Http\Resources\DiscountResource;

class DiscountApiController extends Controller
{
    public function index(Request $request)
    {
        $query = ProductDiscount::query()->with('product:id,name');
        if ($request->boolean('active')) {
            $query->where('start_at','<=', now())->where('end_at','>=', now());
        }
        if ($request->filled('product_id')) {
            $query->where('product_id', (int)$request->query('product_id'));
        }
        $discounts = $query->orderByDesc('start_at')->paginate(15)->appends($request->query());
        return DiscountResource::collection($discounts);
    }

    public function show(ProductDiscount $discount)
    {
        $discount->load('product:id,name');
        return new DiscountResource($discount);
    }
}

