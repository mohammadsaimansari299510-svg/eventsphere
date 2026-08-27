<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Category;
use App\Models\Announcement;
use App\Models\MediaGallery;
use Illuminate\Http\Request;

class PageController extends Controller
{
    public function home()
    {
        try {
            $upcomingEvents = Event::with(['category', 'organizer'])
                ->where('status', 'approved')
                ->where('start_date', '>=', now())
                ->orderBy('start_date', 'asc')
                ->take(6)
                ->get();

            $ongoingEvents = Event::with(['category', 'organizer'])
                ->where('status', 'approved')
                ->where('start_date', '<=', now())
                ->where('end_date', '>=', now())
                ->orderBy('start_date', 'asc')
                ->get();

            $pastEvents = Event::with(['category', 'organizer'])
                ->where('status', 'approved')
                ->where('end_date', '<', now())
                ->orderBy('end_date', 'desc')
                ->take(4)
                ->get();

            $announcements = Announcement::where('target_role', 'all')
                ->orderBy('created_at', 'desc')
                ->take(5)
                ->get();

            $categories = Category::withCount(['events' => function ($q) {
                $q->where('status', 'approved');
            }])->get();

            $featuredGallery = MediaGallery::orderBy('created_at', 'desc')->take(6)->get();

            $totalEvents       = Event::where('status', 'approved')->count();
            $totalStudents     = \App\Models\User::where('role', 'student')->count();
            $totalOrganizers   = \App\Models\User::where('role', 'organizer')->count();
            $totalCertificates = \App\Models\Certificate::count();
        } catch (\Throwable $e) {
            $upcomingEvents = collect();
            $ongoingEvents = collect();
            $pastEvents = collect();
            $announcements = collect();
            $categories = collect();
            $featuredGallery = collect();
            $totalEvents = 0;
            $totalStudents = 0;
            $totalOrganizers = 0;
            $totalCertificates = 0;
        }

        return view('home', compact(
            'upcomingEvents', 'ongoingEvents', 'pastEvents',
            'announcements', 'categories', 'featuredGallery',
            'totalEvents', 'totalStudents', 'totalOrganizers', 'totalCertificates'
        ));
    }

    public function about()
    {
        return view('pages.about');
    }

    public function contact()
    {
        return view('pages.contact');
    }

    public function submitContact(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'subject' => 'required|string|max:255',
            'message' => 'required|string',
        ]);

        return back()->with('success', 'Thank you for reaching out! Your message has been submitted to the EventSphere team.');
    }

    public function faq()
    {
        return view('pages.faq');
    }
}
