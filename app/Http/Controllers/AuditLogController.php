<?php

namespace App\Http\Controllers;

use App\AuditLog;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuditLogController extends Controller
{
    public function index(Request $request)
    {
        if (!Auth::check() || !Auth::user()->is_admin) {
            abort(403, 'Audit log is restricted to administrators');
        }

        $query = AuditLog::with('user')->orderByDesc('created_at');

        if ($request->filled('action')) {
            $query->where('action', $request->input('action'));
        }

        if ($request->filled('user_id')) {
            $query->where('user_id', (int) $request->input('user_id'));
        }

        if ($request->filled('subject_id')) {
            $query->where('subject_id', (int) $request->input('subject_id'));
        }

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->input('date_from'));
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->input('date_to'));
        }

        if ($request->filled('search')) {
            $term = $request->input('search');
            $query->where(function ($q) use ($term) {
                $q->where('description', 'like', '%' . $term . '%')
                    ->orWhere('properties', 'like', '%' . $term . '%')
                    ->orWhere('action', 'like', '%' . $term . '%');
            });
        }

        $logs = $query->paginate(50)->appends($request->all());

        $actions = AuditLog::select('action')->distinct()->orderBy('action')->pluck('action');
        $users = User::orderBy('first_name')
            ->orderBy('last_name')
            ->get(['id', 'first_name', 'last_name', 'name_initials', 'username']);

        return view('admin.audit-logs.index', [
            'logs' => $logs,
            'filters' => $request->only(['action', 'user_id', 'subject_id', 'search', 'date_from', 'date_to']),
            'actions' => $actions,
            'users' => $users,
        ]);
    }
}
