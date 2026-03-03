<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Site;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdminSiteController extends Controller
{
    public function index(Request $request): View
    {
        $sites = Site::with('user')
            ->withCount('apiLogs')
            ->when($request->search, function ($query, $search) {
                $query->where('name', 'like', "%{$search}%")
                    ->orWhere('domain', 'like', "%{$search}%")
                    ->orWhereHas('user', function ($q) use ($search) {
                        $q->where('email', 'like', "%{$search}%");
                    });
            })
            ->when($request->status === 'active', fn ($q) => $q->where('is_active', true))
            ->when($request->status === 'inactive', fn ($q) => $q->where('is_active', false))
            ->latest()
            ->paginate(20)
            ->withQueryString();

        return view('admin.sites.index', compact('sites'));
    }

    public function show(Site $site): View
    {
        $site->load('user');
        $site->loadCount('apiLogs');

        $recentLogs = $site->apiLogs()
            ->latest('created_at')
            ->limit(50)
            ->get();

        return view('admin.sites.show', compact('site', 'recentLogs'));
    }

    public function toggleActive(Site $site): RedirectResponse
    {
        $site->update(['is_active' => ! $site->is_active]);

        $status = $site->is_active ? 'activat' : 'dezactivat';

        return back()->with('success', "Site-ul a fost {$status}.");
    }
}
