<?php

namespace App\Http\Controllers;

use App\Events\NewAnnouncementEvent;
use App\Models\Announcement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class AnnouncementController extends Controller
{
    /**
     * Display a listing of the announcements.
     */
    public function index()
    {
        $announcements = Announcement::with('user')
            ->where('is_active', true)
            ->orderBy('created_at', 'desc')
            ->get();

        return Inertia::render('Announcements/Index', [
            'announcements' => $announcements
        ]);
    }

    /**
     * Show the form for creating a new announcement.
     */
    public function create()
    {
        if (!Auth::user()->isAdmin()) {
            return redirect()->route('announcements.index')
                ->with('error', 'You are not authorized to create announcements.');
        }

        return Inertia::render('Announcements/Create');
    }

    /**
     * Store a newly created announcement in storage.
     */
    public function store(Request $request)
    {
        if (!Auth::user()->isAdmin()) {
            return redirect()->route('announcements.index')
                ->with('error', 'You are not authorized to create announcements.');
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
        ]);

        $announcement = Announcement::create([
            'user_id' => Auth::id(),
            'title' => $validated['title'],
            'content' => $validated['content'],
            'is_active' => true,
        ]);

        // Load the user relationship for broadcasting
        $announcement->load('user');
        
        // Broadcast the new announcement in real-time
        // Using event() to trigger the ShouldBroadcastNow implementation
        event(new NewAnnouncementEvent($announcement));
        
        // Log that we're broadcasting the announcement
        \Log::info('Broadcasting new announcement: ' . $announcement->id);

        return redirect()->route('announcements.index')
            ->with('success', 'Announcement created successfully.');
    }

    /**
     * Display the specified announcement.
     */
    public function show(Announcement $announcement)
    {
        $announcement->load('user');
        
        return Inertia::render('Announcements/Show', [
            'announcement' => $announcement
        ]);
    }

    /**
     * Show the form for editing the specified announcement.
     */
    public function edit(Announcement $announcement)
    {
        if (!Auth::user()->isAdmin()) {
            return redirect()->route('announcements.index')
                ->with('error', 'You are not authorized to edit announcements.');
        }

        return Inertia::render('Announcements/Edit', [
            'announcement' => $announcement
        ]);
    }

    /**
     * Update the specified announcement in storage.
     */
    public function update(Request $request, Announcement $announcement)
    {
        if (!Auth::user()->isAdmin()) {
            return redirect()->route('announcements.index')
                ->with('error', 'You are not authorized to update announcements.');
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'is_active' => 'boolean',
        ]);

        $announcement->update($validated);
        
        // Load the user relationship for broadcasting
        $announcement->load('user');

        // Broadcast the updated announcement in real-time
        event(new NewAnnouncementEvent($announcement));

        return redirect()->route('announcements.index')
            ->with('success', 'Announcement updated successfully.');
    }

    /**
     * Remove the specified announcement from storage.
     */
    public function destroy(Announcement $announcement)
    {
        if (!Auth::user()->isAdmin()) {
            return redirect()->route('announcements.index')
                ->with('error', 'You are not authorized to delete announcements.');
        }

        $announcement->delete();

        return redirect()->route('announcements.index')
            ->with('success', 'Announcement deleted successfully.');
    }
}
