<?php

namespace App\Http\Controllers\Face;

use App\Http\Controllers\Controller;

class HomeController extends Controller
{
    public function index()
    {
        return view('face');
    }
}
