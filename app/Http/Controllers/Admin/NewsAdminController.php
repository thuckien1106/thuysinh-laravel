<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\News;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class NewsAdminController extends Controller
{
    public function index()
    {
        $items = News::orderByDesc('published_at')->orderByDesc('id')->paginate(10);
        return view('admin.news.index', compact('items'));
    }

    public function create()
    {
        return view('admin.news.create');
    }

    public function store(Request $request)
    {
        $data = $this->validateData($request);
        $data['slug'] = $data['slug'] ?: Str::slug($data['title']);
        if (empty($data['published_at']) && $data['is_published']) {
            $data['published_at'] = now();
        }
        News::create($data);
        return redirect()->route('admin.news.index')->with('success', 'Đã tạo tin tức.');
    }

    public function edit(News $news)
    {
        return view('admin.news.edit', compact('news'));
    }

    public function update(Request $request, News $news)
    {
        $data = $this->validateData($request, $news->id);
        $data['slug'] = $data['slug'] ?: Str::slug($data['title']);
        if (empty($data['published_at']) && $data['is_published']) {
            $data['published_at'] = now();
        }
        $news->update($data);
        return redirect()->route('admin.news.index')->with('success', 'Đã cập nhật tin tức.');
    }

    public function destroy(News $news)
    {
        $news->delete();
        return redirect()->route('admin.news.index')->with('success', 'Đã xóa tin tức.');
    }

    protected function validateData(Request $request, $id = null): array
    {
        $idRule = $id ? ',' . $id : '';
        return $request->validate([
            'title'         => 'required|string|max:200',
            'slug'          => 'nullable|string|max:220|unique:news,slug' . $idRule,
            'summary'       => 'nullable|string',
            'content'       => 'nullable|string',
            'banner_image'  => 'nullable|string|max:255',
            'is_published'  => 'sometimes|boolean',
            'published_at'  => 'nullable|date',
        ]) + [
            'is_published' => $request->boolean('is_published'),
        ];
    }
}
