<?php

declare(strict_types=1);

namespace App\Http\Controllers\Dashboard;

use App\Events\SiteCreated;
use App\Http\Controllers\Controller;
use App\Http\Requests\Dashboard\SiteRequest;
use App\Models\Site;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class SiteController extends Controller
{
    public function index(Request $request): View
    {
        $sites = $request->user()
            ->sites()
            ->withCount('apiLogs')
            ->latest()
            ->get();

        return view('dashboard.sites.index', compact('sites'));
    }

    private const MAX_SITES_PER_USER = 28;

    public function create(): View|RedirectResponse
    {
        if (auth()->user()->sites()->count() >= self::MAX_SITES_PER_USER) {
            return redirect()
                ->route('dashboard.sites.index')
                ->with('error', 'Ai atins limita maximă de '.self::MAX_SITES_PER_USER.' site-uri.');
        }

        return view('dashboard.sites.create');
    }

    public function store(SiteRequest $request): RedirectResponse
    {
        if ($request->user()->sites()->count() >= self::MAX_SITES_PER_USER) {
            return redirect()
                ->route('dashboard.sites.index')
                ->with('error', 'Ai atins limita maximă de '.self::MAX_SITES_PER_USER.' site-uri.');
        }

        $site = $request->user()->sites()->create([
            'name' => $request->name,
            'domain' => $request->domain,
            'token' => Site::generateToken(),
        ]);

        event(new SiteCreated($site));

        return redirect()
            ->route('dashboard.sites.show', $site)
            ->with('success', 'Site-ul a fost creat cu succes. Token-ul tău API este afișat mai jos.')
            ->with('show_token', true);
    }

    public function show(Request $request, Site $site): View
    {
        $this->authorize('view', $site);

        // Statistics for this site
        $totalCalls = $site->apiLogs()->count();

        $callsToday = $site->apiLogs()
            ->whereDate('created_at', today())
            ->count();

        $callsLast30Days = $site->apiLogs()
            ->select(DB::raw('DATE(created_at) as date'), DB::raw('COUNT(*) as count'))
            ->where('created_at', '>=', now()->subDays(30))
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        $callsByEndpoint = $site->apiLogs()
            ->select('endpoint', DB::raw('COUNT(*) as count'))
            ->groupBy('endpoint')
            ->orderByDesc('count')
            ->limit(10)
            ->get();

        $recentLogs = $site->apiLogs()
            ->latest('created_at')
            ->limit(20)
            ->get();

        return view('dashboard.sites.show', compact(
            'site',
            'totalCalls',
            'callsToday',
            'callsLast30Days',
            'callsByEndpoint',
            'recentLogs'
        ));
    }

    public function regenerateToken(Request $request, Site $site): RedirectResponse
    {
        $this->authorize('update', $site);

        $site->regenerateToken();

        return redirect()
            ->route('dashboard.sites.show', $site)
            ->with('success', 'Token-ul a fost regenerat cu succes.')
            ->with('show_token', true);
    }

    public function destroy(Request $request, Site $site): RedirectResponse
    {
        $this->authorize('delete', $site);

        $site->delete();

        return redirect()
            ->route('dashboard.sites.index')
            ->with('success', 'Site-ul a fost șters cu succes.');
    }
}
