<?php

namespace App\Http\Controllers;

use App\Models\Announcement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class DashboardController extends Controller
{
    /**
     * Display the admin dashboard.
     */
    public function index()
    {
        if (!Auth::user()->isAdmin()) {
            return redirect()->route('announcements.index')
                ->with('error', 'You are not authorized to access the admin dashboard.');
        }

        $announcements = Announcement::with('user')
            ->orderBy('created_at', 'desc')
            ->get();

        return Inertia::render('Dashboard/Admin', [
            'announcements' => $announcements
        ]);
    }
}
