<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use Illuminate\Http\Request;

class ActivityLogController extends Controller
{
    public function index(Request $request)
    {
        $query = ActivityLog::with('user')->latest();

        // Pencarian pengguna atau deskripsi aktivitas
        if ($request->filled('search')) {
            $search = $request->search;

            $query->where(function ($q) use ($search) {
                $q->where('description', 'like', "%{$search}%")
                    ->orWhereHas('user', function ($userQuery) use ($search) {
                        $userQuery->where('name', 'like', "%{$search}%")
                            ->orWhere('email', 'like', "%{$search}%");
                    });
            });
        }

        // Filter modul
        if ($request->filled('module')) {
            $query->where('module', $request->module);
        }

        // Filter jenis aktivitas
        if ($request->filled('action')) {
            $query->where('action', $request->action);
        }

        $activityLogs = $query->paginate(15)->withQueryString();

        $modules = ActivityLog::query()
            ->select('module')
            ->distinct()
            ->orderBy('module')
            ->pluck('module');

        return view('admin.activity-logs.index', compact(
            'activityLogs',
            'modules'
        ));
    }
}
