<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;

class PageController extends Controller
{
    public function home()
    {
        $products = Product::orderBy('created_at', 'desc')->paginate(12);
        return view('home', compact('products'));
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
