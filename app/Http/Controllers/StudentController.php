<?php

namespace App\Http\Controllers;

use App\Models\Registration;
use App\Models\Certificate;
use App\Models\Bookmark;
use App\Models\SavedMedia;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class StudentController extends Controller
{
    public function dashboard()
    {
        $user = Auth::user();

        // Active & Past Registrations
        $registrations = Registration::with(['event.category', 'event.organizer'])
            ->where('user_id', $user->id)
            ->orderBy('registered_at', 'desc')
            ->get();

        // Certificates
        $certificates = Certificate::with('event')
            ->where('user_id', $user->id)
            ->orderBy('issued_at', 'desc')
            ->get();

        // Bookmarks
        $bookmarks = Bookmark::with(['event.category'])
            ->where('user_id', $user->id)
            ->get();

        // Saved Media
        $savedMedia = SavedMedia::with(['media'])
            ->where('user_id', $user->id)
            ->get();

        // Notifications
        $notifications = Notification::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get();

        // Unread Notifications Count
        $unreadNotificationsCount = Notification::where('user_id', $user->id)
            ->where('is_read', false)
            ->count();

        return view('student.dashboard', compact(
            'registrations',
            'certificates',
            'bookmarks',
            'savedMedia',
            'notifications',
            'unreadNotificationsCount'
        ));
    }

    public function markNotificationsRead()
    {
        Notification::where('user_id', Auth::id())
            ->where('is_read', false)
            ->update(['is_read' => true]);

        return back()->with('success', 'Notifications marked as read.');
    }

    public function downloadCertificate($certificateId)
    {
        $user = Auth::user();
        $certificate = Certificate::with('event')
            ->where('id', $certificateId)
            ->where('user_id', $user->id)
            ->firstOrFail();

        // Generates clean downloadable HTML/PDF Certificate content
        $html = view('student.certificate_template', compact('certificate', 'user'))->render();

        return response($html, 200, [
            'Content-Type' => 'text/html; charset=utf-8',
            'Content-Disposition' => 'inline; filename="Certificate-' . $certificate->certificate_number . '.html"',
        ]);
    }

    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'department' => 'required|string|max:255',
            'enrolment_number' => 'required|string|max:100',
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        $updateData = [
            'name' => $validated['name'],
            'phone' => $validated['phone'],
            'department' => $validated['department'],
            'enrolment_number' => $validated['enrolment_number'],
        ];

        if (!empty($validated['password'])) {
            $updateData['password'] = Hash::make($validated['password']);
        }

        $user->update($updateData);

        return back()->with('success', 'Profile updated successfully.');
    }
}
