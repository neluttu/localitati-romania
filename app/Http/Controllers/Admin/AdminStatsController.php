<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ApiLog;
use App\Models\Site;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class AdminStatsController extends Controller
{
    public function index(): View
    {
        $totalUsers = User::count();
        $totalSites = Site::count();
        $totalApiCalls = ApiLog::count();
        $callsToday = ApiLog::whereDate('created_at', today())->count();

        $callsLast30Days = ApiLog::select(
            DB::raw('DATE(created_at) as date'),
            DB::raw('COUNT(*) as count')
        )
            ->where('created_at', '>=', now()->subDays(30))
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        $topEndpoints = ApiLog::select('endpoint', DB::raw('COUNT(*) as count'))
            ->groupBy('endpoint')
            ->orderByDesc('count')
            ->limit(10)
            ->get();

        $topSites = Site::withCount('apiLogs')
            ->orderByDesc('api_logs_count')
            ->limit(10)
            ->get();

        $callsByStatusCode = ApiLog::select('status_code', DB::raw('COUNT(*) as count'))
            ->groupBy('status_code')
            ->orderBy('status_code')
            ->get();

        $avgResponseTime = ApiLog::avg('response_time_ms');

        return view('admin.stats.index', compact(
            'totalUsers',
            'totalSites',
            'totalApiCalls',
            'callsToday',
            'callsLast30Days',
            'topEndpoints',
            'topSites',
            'callsByStatusCode',
            'avgResponseTime'
        ));
    }
}
