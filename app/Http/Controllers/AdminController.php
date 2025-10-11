<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function dashboard()
    {
        return view('admin.dashboard');
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
