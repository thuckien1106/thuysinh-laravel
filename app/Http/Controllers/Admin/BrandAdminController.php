<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\Admin\StoreBrandRequest;
use App\Http\Requests\Admin\UpdateBrandRequest;
use App\Models\Brand;

class BrandAdminController extends Controller
{
    public function index(Request $request)
    {
        $q = trim((string)$request->get('q'));
        $query = Brand::query();
        if ($q !== '') $query->where('name','like',"%$q%");
        $brands = $query->orderBy('name')->paginate(15)->withQueryString();
        return view('admin.brands.index', compact('brands','q'));
    }

    public function create()
    {
        return view('admin.brands.create');
    }

    public function store(StoreBrandRequest $request)
    {
        $data = $request->validated();
        Brand::create($data);
        return redirect()->route('admin.brands.index')->with('success','Đã tạo thương hiệu.');
    }

    public function edit(Brand $brand)
    {
                return view('admin.brands.edit', compact('brand'));
    }

    public function update(UpdateBrandRequest $request, Brand $brand)
    {
                $data = $request->validated();
        $brand->update($data);
        return redirect()->route('admin.brands.index')->with('success','Đã cập nhật thương hiệu.');
    }

    public function destroy(Brand $brand)
    {
                $brand->delete();
        return back()->with('success','Đã xóa thương hiệu.');
    }
}





