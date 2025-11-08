<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\Admin\StoreProductRequest;
use App\Http\Requests\Admin\UpdateProductRequest;
use Illuminate\Support\Facades\DB;
use App\Models\Product;

class ProductAdminController extends Controller
{
    public function index(Request $request)
    {
        $q = trim((string) $request->get('q'));
        $category = $request->get('category');
        $brand = $request->get('brand');
        $sort = $request->get('sort'); // price_asc, price_desc, qty_asc, qty_desc, created_desc

        $query = Product::query();
        if ($q !== '') $query->where('name', 'like', "%$q%");
        if ($category) $query->where('category_id', $category);
        if ($brand) $query->where('brand_id', $brand);
        if ($sort === 'price_asc') $query->orderBy('price', 'asc');
        elseif ($sort === 'price_desc') $query->orderBy('price', 'desc');
        elseif ($sort === 'qty_asc') $query->orderBy('quantity', 'asc');
        elseif ($sort === 'qty_desc') $query->orderBy('quantity', 'desc');
        else $query->orderByDesc('id');

        $products = $query->paginate(12)->withQueryString();
        $categories = DB::table('categories')->orderBy('name')->get();
        $brands = DB::table('brands')->orderBy('name')->get();
        return view('admin.products.index', compact('products', 'q', 'category', 'brand', 'sort', 'categories', 'brands'));
    }

    public function create()
    {
        $categories = DB::table('categories')->orderBy('name')->get();
        $brands = DB::table('brands')->orderBy('name')->get();
        return view('admin.products.create', compact('categories', 'brands'));
    }

    public function store(StoreProductRequest $request)
    {
        $data = $request->validated();

        if ($request->hasFile('image_file')) {
            $file = $request->file('image_file');
            $name = time().'_'.preg_replace('/[^A-Za-z0-9_\.-]/', '_', $file->getClientOriginalName());
            $dest = public_path('assets/img/products');
            if (!is_dir($dest)) { @mkdir($dest, 0777, true); }
            $file->move($dest, $name);
            $data['image'] = $name;
        }

        unset($data['image_file']);
        Product::create($data);
        return redirect()->route('admin.products.index')->with('success', 'Đã tạo sản phẩm.');
    }

    public function edit(Product $product)
    {
        $categories = DB::table('categories')->orderBy('name')->get();
        $brands = DB::table('brands')->orderBy('name')->get();
        return view('admin.products.edit', compact('product', 'categories', 'brands'));
    }

    public function update(UpdateProductRequest $request, Product $product)
    {
        $data = $request->validated();
        if ($request->hasFile('image_file')) {
            $file = $request->file('image_file');
            $name = time().'_'.preg_replace('/[^A-Za-z0-9_\.-]/', '_', $file->getClientOriginalName());
            $dest = public_path('assets/img/products');
            if (!is_dir($dest)) { @mkdir($dest, 0777, true); }
            $file->move($dest, $name);
            $data['image'] = $name;
        }
        unset($data['image_file']);
        $product->update($data);
        return redirect()->route('admin.products.index')->with('success', 'Đã cập nhật sản phẩm.');
    }

    public function destroy(Product $product)
    {
        $product->delete();
        return redirect()->route('admin.products.index')->with('success', 'Đã xóa sản phẩm.');
    }

    // Tạo lịch giảm giá nhanh cho sản phẩm
    public function setDiscount(Request $request, Product $product)
    {
        $data = $request->validate([
            'percent' => 'required|integer|min:1|max:90',
            'duration' => 'required|integer|min:1|max:168', // tối đa 7 ngày tính theo giờ
            'unit' => 'required|in:hours,days',
            'note' => 'nullable|string|max:120',
        ]);

        $hours = $data['unit'] === 'days' ? ($data['duration'] * 24) : $data['duration'];
        $start = now();
        $end = now()->addHours($hours);

        \App\Models\ProductDiscount::create([
            'product_id' => $product->id,
            'percent' => $data['percent'],
            'start_at' => $start,
            'end_at' => $end,
            'note' => $data['note'] ?? null,
        ]);

        return back()->with('success', 'Đã thiết lập giảm giá '.$data['percent'].'% cho sản phẩm trong '.($data['unit']==='days' ? $data['duration'].' ngày' : $data['duration'].' giờ').'.');
    }
}

