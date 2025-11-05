<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;

class CategoryAdminController extends Controller
{
    public function index(Request $request)
    {
        $q = trim((string)$request->get('q'));
        $query = Category::query();
        if ($q !== '') $query->where('name','like',"%$q%");
        $categories = $query->orderBy('name')->paginate(15)->withQueryString();
        return view('admin.categories.index', compact('categories','q'));
    }

    public function create()
    {
        return view('admin.categories.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:100|unique:categories,name',
        ]);
        Category::create($data);
        return redirect()->route('admin.categories.index')->with('success','Đã tạo danh mục.');
    }

    public function edit($id)
    {
        $category = Category::findOrFail($id);
        return view('admin.categories.edit', compact('category'));
    }

    public function update(Request $request, $id)
    {
        $category = Category::findOrFail($id);
        $data = $request->validate([
            'name' => 'required|string|max:100|unique:categories,name,'.$category->id,
        ]);
        $category->update($data);
        return redirect()->route('admin.categories.index')->with('success','Đã cập nhật danh mục.');
    }

    public function destroy($id)
    {
        $category = Category::findOrFail($id);
        $category->delete();
        return back()->with('success','Đã xóa danh mục.');
    }
}

