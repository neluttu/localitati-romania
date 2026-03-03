<?php

declare(strict_types=1);

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class StatsController extends Controller
{
    public function index(Request $request): View
    {
        $user = $request->user();
        $siteIds = $user->sites()->pluck('id');

        $totalCalls = DB::table('api_logs')
            ->whereIn('site_id', $siteIds)
            ->count();

        $callsToday = DB::table('api_logs')
            ->whereIn('site_id', $siteIds)
            ->whereDate('created_at', today())
            ->count();

        $callsLast30Days = DB::table('api_logs')
            ->select(DB::raw('DATE(created_at) as date'), DB::raw('COUNT(*) as count'))
            ->whereIn('site_id', $siteIds)
            ->where('created_at', '>=', now()->subDays(30))
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        $callsBySite = $user->sites()
            ->withCount('apiLogs')
            ->orderByDesc('api_logs_count')
            ->get();

        $callsByEndpoint = DB::table('api_logs')
            ->select('endpoint', DB::raw('COUNT(*) as count'))
            ->whereIn('site_id', $siteIds)
            ->groupBy('endpoint')
            ->orderByDesc('count')
            ->limit(10)
            ->get();

        return view('dashboard.stats.index', compact(
            'totalCalls',
            'callsToday',
            'callsLast30Days',
            'callsBySite',
            'callsByEndpoint'
        ));
    }
}
