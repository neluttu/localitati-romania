<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use Illuminate\View\View;

class LegalController extends Controller
{
    public function terms(): View
    {
        return view('legal.terms');
    }

    public function privacy(): View
    {
        return view('legal.privacy');
    }

    public function cookies(): View
    {
        return view('legal.cookies');
    }
}
