<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ApiLog;
use App\Models\Site;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class AdminDashboardController extends Controller
{
    public function index(): View
    {
        $totalUsers = User::count();
        $totalSites = Site::count();
        $totalApiCalls = ApiLog::count();
        $callsToday = ApiLog::whereDate('created_at', today())->count();

        $recentUsers = User::withCount('sites')
            ->latest()
            ->limit(5)
            ->get();

        $topSites = Site::withCount('apiLogs')
            ->orderByDesc('api_logs_count')
            ->limit(5)
            ->get();

        $callsLast7Days = ApiLog::select(
            DB::raw('DATE(created_at) as date'),
            DB::raw('COUNT(*) as count')
        )
            ->where('created_at', '>=', now()->subDays(7))
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        return view('admin.dashboard', compact(
            'totalUsers',
            'totalSites',
            'totalApiCalls',
            'callsToday',
            'recentUsers',
            'topSites',
            'callsLast7Days'
        ));
    }
}
