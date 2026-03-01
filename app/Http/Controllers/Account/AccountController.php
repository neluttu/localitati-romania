<?php
declare(strict_types=1);
namespace App\Http\Controllers\Account;

use Illuminate\View\View;
use App\Http\Controllers\Controller;

class AccountController extends Controller
{
    public function index(): View
    {
        return view('account.index');
    }
}
