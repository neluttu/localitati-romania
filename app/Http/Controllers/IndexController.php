<?php

namespace App\Http\Controllers;

use Illuminate\View\View;
use Illuminate\Http\Request;

class IndexController extends Controller
{
    public function index(): View
    {
        return view('index');
    }
}
