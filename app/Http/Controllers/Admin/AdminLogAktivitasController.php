<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;

class AdminLogAktivitasController extends Controller
{
    public function index()
    {
        $logs = ActivityLog::with('admin')
            ->latest()
            ->paginate(20);

        return view('admin.log.index', compact('logs'));
    }
}   