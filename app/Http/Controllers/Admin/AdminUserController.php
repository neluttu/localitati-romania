<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdminUserController extends Controller
{
    public function index(Request $request): View
    {
        $users = User::withCount('sites')
            ->when($request->search, function ($query, $search) {
                $query->where('email', 'like', "%{$search}%");
            })
            ->latest()
            ->paginate(20)
            ->withQueryString();

        return view('admin.users.index', compact('users'));
    }

    public function show(User $user): View
    {
        $user->loadCount('sites');

        $sites = $user->sites()
            ->withCount('apiLogs')
            ->latest()
            ->get();

        $totalApiCalls = $sites->sum('api_logs_count');

        return view('admin.users.show', compact('user', 'sites', 'totalApiCalls'));
    }

    public function toggleActive(User $user): RedirectResponse
    {
        // Prevent admin from deactivating themselves
        if ($user->id === auth()->id()) {
            return back()->with('error', 'Nu te poți dezactiva pe tine însuți.');
        }

        // Toggle all user's sites
        $user->sites()->update(['is_active' => ! $user->sites()->first()?->is_active]);

        return back()->with('success', 'Statusul utilizatorului a fost actualizat.');
    }
}
