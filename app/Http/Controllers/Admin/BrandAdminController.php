<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
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

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:120|unique:brands,name',
            'slug' => 'nullable|string|max:140|unique:brands,slug',
        ]);
        Brand::create($data);
        return redirect()->route('admin.brands.index')->with('success','Đã tạo thương hiệu.');
    }

    public function edit($id)
    {
        $brand = Brand::findOrFail($id);
        return view('admin.brands.edit', compact('brand'));
    }

    public function update(Request $request, $id)
    {
        $brand = Brand::findOrFail($id);
        $data = $request->validate([
            'name' => 'required|string|max:120|unique:brands,name,'.$brand->id,
            'slug' => 'nullable|string|max:140|unique:brands,slug,'.$brand->id,
        ]);
        $brand->update($data);
        return redirect()->route('admin.brands.index')->with('success','Đã cập nhật thương hiệu.');
    }

    public function destroy($id)
    {
        $brand = Brand::findOrFail($id);
        $brand->delete();
        return back()->with('success','Đã xóa thương hiệu.');
    }
}

