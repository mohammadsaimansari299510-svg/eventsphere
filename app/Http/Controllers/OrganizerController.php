<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Category;
use App\Models\Registration;
use App\Models\Attendance;
use App\Models\Certificate;
use App\Models\Announcement;
use App\Models\Notification;
use App\Models\MediaGallery;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class OrganizerController extends Controller
{
    public function dashboard()
    {
        $user = Auth::user();

        $events = Event::with(['category', 'registrations'])
            ->where('organizer_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->get();

        $totalEvents = $events->count();
        $totalRegistrations = Registration::whereIn('event_id', $events->pluck('id'))->count();
        $pendingEventsCount = $events->where('status', 'pending')->count();
        $approvedEventsCount = $events->where('status', 'approved')->count();

        return view('organizer.dashboard', compact(
            'events',
            'totalEvents',
            'totalRegistrations',
            'pendingEventsCount',
            'approvedEventsCount'
        ));
    }

    public function createEvent()
    {
        $categories = Category::all();
        return view('organizer.create_event', compact('categories'));
    }

    public function storeEvent(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'description' => 'required|string',
            'venue' => 'required|string|max:255',
            'capacity' => 'required|integer|min:1',
            'start_date' => 'required|date|after:now',
            'end_date' => 'required|date|after:start_date',
            'registration_deadline' => 'required|date|before:start_date',
            'organizing_department' => 'required|string|max:255',
            'hashtags' => 'nullable|string|max:255',
            'banner_image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',
            'rulebook_file' => 'nullable|file|mimes:pdf,doc,docx|max:10240',
        ]);

        $bannerPath = null;
        if ($request->hasFile('banner_image')) {
            $file = $request->file('banner_image');
            $filename = time() . '_banner_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/events'), $filename);
            $bannerPath = 'uploads/events/' . $filename;
        }

        $rulebookPath = null;
        if ($request->hasFile('rulebook_file')) {
            $file = $request->file('rulebook_file');
            $filename = time() . '_rulebook_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/rulebooks'), $filename);
            $rulebookPath = 'uploads/rulebooks/' . $filename;
        }

        $slug = Str::slug($validated['title']) . '-' . Str::random(5);

        Event::create([
            'title' => $validated['title'],
            'slug' => $slug,
            'description' => $validated['description'],
            'category_id' => $validated['category_id'],
            'venue' => $validated['venue'],
            'capacity' => $validated['capacity'],
            'available_slots' => $validated['capacity'],
            'start_date' => $validated['start_date'],
            'end_date' => $validated['end_date'],
            'registration_deadline' => $validated['registration_deadline'],
            'organizer_id' => $user->id,
            'organizing_department' => $validated['organizing_department'],
            'hashtags' => $validated['hashtags'],
            'banner_image' => $bannerPath,
            'rulebook_file' => $rulebookPath,
            'status' => 'pending', // Enters 'Pending Approval' state
        ]);

        return redirect()->route('organizer.dashboard')->with('success', 'Event proposal created! It has been submitted to the Admin for approval.');
    }

    public function editEvent($id)
    {
        $user = Auth::user();
        $event = Event::where('id', $id)
            ->where('organizer_id', $user->id)
            ->firstOrFail();

        $categories = Category::all();
        return view('organizer.edit_event', compact('event', 'categories'));
    }

    public function updateEvent(Request $request, $id)
    {
        $user = Auth::user();
        $event = Event::where('id', $id)
            ->where('organizer_id', $user->id)
            ->firstOrFail();

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'description' => 'required|string',
            'venue' => 'required|string|max:255',
            'capacity' => 'required|integer|min:1',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'registration_deadline' => 'required|date|before:start_date',
            'organizing_department' => 'required|string|max:255',
            'hashtags' => 'nullable|string|max:255',
            'banner_image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',
            'rulebook_file' => 'nullable|file|mimes:pdf,doc,docx|max:10240',
        ]);

        // If capacity increased, adjust available slots accordingly
        $capacityDiff = $validated['capacity'] - $event->capacity;
        $newAvailableSlots = max(0, $event->available_slots + $capacityDiff);

        if ($request->hasFile('banner_image')) {
            $file = $request->file('banner_image');
            $filename = time() . '_banner_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/events'), $filename);
            $event->banner_image = 'uploads/events/' . $filename;
        }

        if ($request->hasFile('rulebook_file')) {
            $file = $request->file('rulebook_file');
            $filename = time() . '_rulebook_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/rulebooks'), $filename);
            $event->rulebook_file = 'uploads/rulebooks/' . $filename;
        }

        $event->update([
            'title' => $validated['title'],
            'category_id' => $validated['category_id'],
            'description' => $validated['description'],
            'venue' => $validated['venue'],
            'capacity' => $validated['capacity'],
            'available_slots' => $newAvailableSlots,
            'start_date' => $validated['start_date'],
            'end_date' => $validated['end_date'],
            'registration_deadline' => $validated['registration_deadline'],
            'organizing_department' => $validated['organizing_department'],
            'hashtags' => $validated['hashtags'],
        ]);

        // Auto-notify registered participants about schedule or venue updates
        $registeredUsers = Registration::where('event_id', $event->id)
            ->where('status', 'registered')
            ->pluck('user_id');

        foreach ($registeredUsers as $userId) {
            Notification::create([
                'user_id' => $userId,
                'title' => 'Event Updated: ' . $event->title,
                'message' => "Details or schedule for '{$event->title}' have been updated by the organizer. Please check the event page for details.",
                'type' => 'event_update',
            ]);
        }

        return redirect()->route('organizer.dashboard')->with('success', 'Event updated successfully! Registered participants have been notified.');
    }

    public function manageRegistrations($eventId)
    {
        $user = Auth::user();
        $event = Event::where('id', $eventId)
            ->where('organizer_id', $user->id)
            ->firstOrFail();

        $registrations = Registration::with('user')
            ->where('event_id', $event->id)
            ->orderBy('registered_at', 'desc')
            ->get();

        $attendances = Attendance::where('event_id', $event->id)->get()->keyBy('user_id');

        return view('organizer.registrations', compact('event', 'registrations', 'attendances'));
    }

    public function showScanner($eventId)
    {
        $user = Auth::user();
        $event = Event::where('id', $eventId)
            ->where('organizer_id', $user->id)
            ->firstOrFail();

        $todayAttendances = Attendance::with('user')
            ->where('event_id', $event->id)
            ->orderBy('checked_in_at', 'desc')
            ->get();

        return view('organizer.scanner', compact('event', 'todayAttendances'));
    }

    public function verifyAttendance(Request $request, $eventId)
    {
        $user = Auth::user();
        $event = Event::where('id', $eventId)
            ->where('organizer_id', $user->id)
            ->firstOrFail();

        $request->validate([
            'qr_token' => 'required|string',
        ]);

        $qrToken = trim($request->input('qr_token'));

        $registration = Registration::with('user')
            ->where('event_id', $event->id)
            ->where('qr_code_token', $qrToken)
            ->first();

        if (!$registration) {
            return back()->with('error', 'Invalid or unrecognized QR token for this event.');
        }

        if ($registration->status === 'cancelled') {
            return back()->with('error', 'This participant registration was cancelled.');
        }

        // Check existing attendance
        $alreadyCheckedIn = Attendance::where('event_id', $event->id)
            ->where('user_id', $registration->user_id)
            ->first();

        if ($alreadyCheckedIn) {
            return back()->with('info', "Participant {$registration->user->name} has ALREADY checked in at {$alreadyCheckedIn->checked_in_at->format('h:i A')}.");
        }

        // Create Attendance record
        Attendance::create([
            'event_id' => $event->id,
            'user_id' => $registration->user_id,
            'checked_in_by' => $user->id,
            'checked_in_at' => now(),
        ]);

        $registration->update(['status' => 'attended']);

        Notification::create([
            'user_id' => $registration->user_id,
            'title' => 'Attendance Marked!',
            'message' => "Your attendance for '{$event->title}' has been successfully scanned and verified.",
            'type' => 'attendance',
        ]);

        return back()->with('success', "SUCCESS! Attendance recorded for {$registration->user->name} ({$registration->user->enrolment_number}).");
    }

    public function issueCertificates(Request $request, $eventId)
    {
        $user = Auth::user();
        $event = Event::where('id', $eventId)
            ->where('organizer_id', $user->id)
            ->firstOrFail();

        // Get attended participants who paid fee
        $eligibleRegistrations = Registration::with('user')
            ->where('event_id', $event->id)
            ->where('status', 'attended')
            ->get();

        $issuedCount = 0;

        foreach ($eligibleRegistrations as $reg) {
            $existingCert = Certificate::where('event_id', $event->id)
                ->where('user_id', $reg->user_id)
                ->first();

            if (!$existingCert) {
                $certNum = 'CERT-' . strtoupper(Str::random(8)) . '-' . date('Y');
                Certificate::create([
                    'event_id' => $event->id,
                    'user_id' => $reg->user_id,
                    'certificate_number' => $certNum,
                    'issued_at' => now(),
                ]);

                Notification::create([
                    'user_id' => $reg->user_id,
                    'title' => 'Certificate Issued!',
                    'message' => "Your participation e-certificate for '{$event->title}' is now ready to download from your dashboard.",
                    'type' => 'certificate',
                ]);

                $issuedCount++;
            }
        }

        return back()->with('success', "Issued {$issuedCount} e-certificates to attended participants.");
    }

    public function sendAnnouncement(Request $request, $eventId)
    {
        $user = Auth::user();
        $event = Event::where('id', $eventId)
            ->where('organizer_id', $user->id)
            ->firstOrFail();

        $request->validate([
            'title' => 'required|string|max:255',
            'message' => 'required|string',
        ]);

        $announcement = Announcement::create([
            'title' => $request->input('title'),
            'message' => $request->input('message'),
            'target_role' => 'student',
            'event_id' => $event->id,
            'created_by' => $user->id,
        ]);

        $registeredUserIds = Registration::where('event_id', $event->id)
            ->whereIn('status', ['registered', 'waitlisted', 'attended'])
            ->pluck('user_id');

        foreach ($registeredUserIds as $uId) {
            Notification::create([
                'user_id' => $uId,
                'title' => 'Organizer Announcement: ' . $announcement->title,
                'message' => $announcement->message,
                'type' => 'announcement',
            ]);
        }

        return back()->with('success', 'Announcement dispatched to all event participants.');
    }
}
