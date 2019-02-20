<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;

class AboutController extends Controller
{

    public function index()
    {

        return view('layouts.front.about');
    }

    public function maintain()
    {

        return view('layouts.front.maintain');
    }
}
