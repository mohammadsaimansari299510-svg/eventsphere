<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Category;
use App\Models\Bookmark;
use App\Models\Registration;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class EventController extends Controller
{
    public function index(Request $request)
    {
        $query = Event::with(['category', 'organizer'])
            ->where('status', 'approved');

        // Search Keyword
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhere('venue', 'like', "%{$search}%")
                  ->orWhere('organizing_department', 'like', "%{$search}%");
            });
        }

        // Category Filter
        if ($request->filled('category')) {
            $query->where('category_id', $request->input('category'));
        }

        // Department Filter
        if ($request->filled('department')) {
            $query->where('organizing_department', $request->input('department'));
        }

        // Date Range Filters
        if ($request->filled('date_from')) {
            $query->where('start_date', '>=', $request->input('date_from'));
        }
        if ($request->filled('date_to')) {
            $query->where('end_date', '<=', $request->input('date_to') . ' 23:59:59');
        }

        // Event Status Tabs (upcoming / ongoing / past)
        $tab = $request->input('tab', 'all');
        if ($tab === 'upcoming') {
            $query->where('start_date', '>', now());
        } elseif ($tab === 'ongoing') {
            $query->where('start_date', '<=', now())->where('end_date', '>=', now());
        } elseif ($tab === 'past') {
            $query->where('end_date', '<', now());
        }

        $events = $query->orderBy('start_date', 'asc')->paginate(9)->withQueryString();

        $categories = Category::all();
        $departments = Event::whereNotNull('organizing_department')
            ->distinct()
            ->pluck('organizing_department');

        return view('events.index', compact('events', 'categories', 'departments', 'tab'));
    }

    public function show($slug)
    {
        $event = Event::with(['category', 'organizer', 'feedbacks.user', 'mediaGalleries'])
            ->where('slug', $slug)
            ->firstOrFail();

        $userRegistration = null;
        $isBookmarked = false;

        if (Auth::check()) {
            $userRegistration = Registration::where('event_id', $event->id)
                ->where('user_id', Auth::id())
                ->whereIn('status', ['registered', 'waitlisted', 'attended'])
                ->first();

            $isBookmarked = Bookmark::where('event_id', $event->id)
                ->where('user_id', Auth::id())
                ->exists();
        }

        $relatedEvents = Event::where('category_id', $event->category_id)
            ->where('id', '!=', $event->id)
            ->where('status', 'approved')
            ->take(3)
            ->get();

        return view('events.show', compact('event', 'userRegistration', 'isBookmarked', 'relatedEvents'));
    }

    public function downloadCalendar($id)
    {
        $event = Event::findOrFail($id);

        $dtStart = $event->start_date->format('Ymd\THis\Z');
        $dtEnd = $event->end_date->format('Ymd\THis\Z');
        $uid = uniqid() . '@eventsphere.edu';
        $summary = addcslashes($event->title, ",;");
        $description = addcslashes(strip_tags($event->description), ",;");
        $location = addcslashes($event->venue, ",;");

        $ics = "BEGIN:VCALENDAR\r\n";
        $ics .= "VERSION:2.0\r\n";
        $ics .= "PRODID:-//EventSphere//College Event Information System//EN\r\n";
        $ics .= "CALSCALE:GREGORIAN\r\n";
        $ics .= "METHOD:PUBLISH\r\n";
        $ics .= "BEGIN:VEVENT\r\n";
        $ics .= "UID:{$uid}\r\n";
        $ics .= "DTSTAMP:" . date('Ymd\THis\Z') . "\r\n";
        $ics .= "DTSTART:{$dtStart}\r\n";
        $ics .= "DTEND:{$dtEnd}\r\n";
        $ics .= "SUMMARY:{$summary}\r\n";
        $ics .= "DESCRIPTION:{$description}\r\n";
        $ics .= "LOCATION:{$location}\r\n";
        $ics .= "END:VEVENT\r\n";
        $ics .= "END:VCALENDAR\r\n";

        $filename = Str::slug($event->title) . '-event.ics';

        return response($ics, 200, [
            'Content-Type' => 'text/calendar; charset=utf-8',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ]);
    }

    public function toggleBookmark($id)
    {
        if (!Auth::check()) {
            return response()->json(['error' => 'Unauthenticated'], 401);
        }

        $user = Auth::user();
        $bookmark = Bookmark::where('user_id', $user->id)->where('event_id', $id)->first();

        if ($bookmark) {
            $bookmark->delete();
            $bookmarked = false;
            $message = 'Event removed from bookmarks.';
        } else {
            Bookmark::create(['user_id' => $user->id, 'event_id' => $id]);
            $bookmarked = true;
            $message = 'Event saved to bookmarks.';
        }

        return back()->with('success', $message);
    }
}
