<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Shop\Products\Product;

class DashboardController extends Controller
{
    public function index()
    {
        $products = Product::orderby("name")->get();

        return view('admin.dashboard', [
            'products' => $products,
        ]);

    }
}
