<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Event;
use App\Models\Registration;
use App\Models\Attendance;
use App\Models\Certificate;
use App\Models\Feedback;
use App\Models\MediaGallery;
use App\Models\Announcement;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Response;

class AdminController extends Controller
{
    public function dashboard()
    {
        $totalUsers = User::count();
        $studentsCount = User::where('role', 'student')->count();
        $organizersCount = User::where('role', 'organizer')->count();

        $totalEvents = Event::count();
        $pendingEventsCount = Event::where('status', 'pending')->count();
        $approvedEventsCount = Event::where('status', 'approved')->count();

        $totalRegistrations = Registration::count();
        $totalCertificates = Certificate::count();

        $topDepartments = Event::select('organizing_department')
            ->selectRaw('count(*) as count')
            ->whereNotNull('organizing_department')
            ->groupBy('organizing_department')
            ->orderBy('count', 'desc')
            ->take(5)
            ->get();

        $recentPendingEvents = Event::with('organizer')
            ->where('status', 'pending')
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        return view('admin.dashboard', compact(
            'totalUsers',
            'studentsCount',
            'organizersCount',
            'totalEvents',
            'pendingEventsCount',
            'approvedEventsCount',
            'totalRegistrations',
            'totalCertificates',
            'topDepartments',
            'recentPendingEvents'
        ));
    }

    public function pendingEvents()
    {
        $pendingEvents = Event::with(['category', 'organizer'])
            ->where('status', 'pending')
            ->orderBy('created_at', 'asc')
            ->get();

        return view('admin.events_pending', compact('pendingEvents'));
    }

    public function approveEvent($id)
    {
        $event = Event::findOrFail($id);
        $event->update([
            'status' => 'approved',
            'rejection_reason' => null,
        ]);

        Notification::create([
            'user_id' => $event->organizer_id,
            'title' => 'Event Proposal Approved!',
            'message' => "Congratulations! Your event proposal for '{$event->title}' has been approved by the Admin and is now live.",
            'type' => 'event_approved',
        ]);

        return back()->with('success', "Event '{$event->title}' has been approved and published live.");
    }

    public function rejectEvent(Request $request, $id)
    {
        $event = Event::findOrFail($id);
        $reason = $request->input('rejection_reason', 'Proposal does not meet standard criteria.');

        $event->update([
            'status' => 'rejected',
            'rejection_reason' => $reason,
        ]);

        Notification::create([
            'user_id' => $event->organizer_id,
            'title' => 'Event Proposal Returned / Rejected',
            'message' => "Your event proposal for '{$event->title}' was rejected. Reason: {$reason}",
            'type' => 'event_rejected',
        ]);

        return back()->with('warning', "Event proposal for '{$event->title}' has been rejected.");
    }

    public function manageUsers(Request $request)
    {
        $query = User::query();

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('username', 'like', "%{$search}%")
                  ->orWhere('enrolment_number', 'like', "%{$search}%");
            });
        }

        if ($request->filled('role')) {
            $query->where('role', $request->input('role'));
        }

        $users = $query->orderBy('created_at', 'desc')->paginate(15)->withQueryString();

        return view('admin.users', compact('users'));
    }

    public function updateUserRole(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'role' => 'required|in:student,organizer,admin',
        ]);

        $user->update(['role' => $request->input('role')]);

        return back()->with('success', "User '{$user->name}' role updated to " . ucfirst($user->role) . ".");
    }

    public function toggleUserStatus($id)
    {
        $user = User::findOrFail($id);

        if ($user->id === Auth::id()) {
            return back()->with('error', 'You cannot suspend your own admin account.');
        }

        $newStatus = ($user->status === 'active') ? 'suspended' : 'active';
        $user->update(['status' => $newStatus]);

        return back()->with('success', "User '{$user->name}' is now {$newStatus}.");
    }

    public function resetUserPassword(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user->update([
            'password' => Hash::make($request->input('password')),
        ]);

        return back()->with('success', "Password reset successfully for '{$user->name}'.");
    }

    public function contentModeration()
    {
        $feedbacks = Feedback::with(['event', 'user'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        $mediaList = MediaGallery::with(['event', 'uploader'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('admin.content_moderation', compact('feedbacks', 'mediaList'));
    }

    public function deleteFeedback($id)
    {
        $feedback = Feedback::findOrFail($id);
        $feedback->delete();

        return back()->with('success', 'Feedback entry removed by admin moderation.');
    }

    public function deleteMedia($id)
    {
        $media = MediaGallery::findOrFail($id);
        if ($media->file_path && file_exists(public_path($media->file_path))) {
            @unlink(public_path($media->file_path));
        }
        $media->delete();

        return back()->with('success', 'Media item removed from gallery.');
    }

    public function sendAnnouncement(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'message' => 'required|string',
            'target_role' => 'required|in:all,student,organizer',
        ]);

        $announcement = Announcement::create([
            'title' => $request->input('title'),
            'message' => $request->input('message'),
            'target_role' => $request->input('target_role'),
            'created_by' => Auth::id(),
        ]);

        // Send notifications
        $query = User::where('status', 'active');
        if ($announcement->target_role !== 'all') {
            $query->where('role', $announcement->target_role);
        }
        $targetUserIds = $query->pluck('id');

        foreach ($targetUserIds as $uId) {
            Notification::create([
                'user_id' => $uId,
                'title' => 'System Announcement: ' . $announcement->title,
                'message' => $announcement->message,
                'type' => 'system_announcement',
            ]);
        }

        return back()->with('success', 'System announcement dispatched successfully.');
    }

    public function exportReport($type)
    {
        $headers = [
            'Content-Type' => 'text/csv',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0',
        ];

        if ($type === 'participation') {
            $filename = 'EventSphere_Participation_Report_' . date('Y-m-d') . '.csv';
            $headers['Content-Disposition'] = 'attachment; filename="' . $filename . '"';

            $callback = function () {
                $file = fopen('php://output', 'w');
                fputcsv($file, ['Registration ID', 'Event Title', 'Category', 'Participant Name', 'Enrolment No', 'Department', 'Status', 'Registered At']);

                $registrations = Registration::with(['event.category', 'user'])->get();
                foreach ($registrations as $reg) {
                    fputcsv($file, [
                        $reg->id,
                        $reg->event->title ?? 'N/A',
                        $reg->event->category->name ?? 'N/A',
                        $reg->user->name ?? 'N/A',
                        $reg->user->enrolment_number ?? 'N/A',
                        $reg->user->department ?? 'N/A',
                        strtoupper($reg->status),
                        $reg->registered_at->format('Y-m-d H:i:s'),
                    ]);
                }
                fclose($file);
            };

            return Response::stream($callback, 200, $headers);
        } elseif ($type === 'feedback') {
            $filename = 'EventSphere_Feedback_Report_' . date('Y-m-d') . '.csv';
            $headers['Content-Disposition'] = 'attachment; filename="' . $filename . '"';

            $callback = function () {
                $file = fopen('php://output', 'w');
                fputcsv($file, ['Feedback ID', 'Event Title', 'User Name', 'Role Title', 'Overall Rating', 'Venue Rating', 'Coordination', 'Tech', 'Hospitality', 'Comments']);

                $feedbacks = Feedback::with(['event', 'user'])->get();
                foreach ($feedbacks as $fb) {
                    fputcsv($file, [
                        $fb->id,
                        $fb->event->title ?? 'N/A',
                        $fb->user->name ?? 'N/A',
                        $fb->user_role_title,
                        $fb->overall_rating,
                        $fb->venue_rating,
                        $fb->coordination_rating,
                        $fb->tech_rating,
                        $fb->hospitality_rating,
                        $fb->comments,
                    ]);
                }
                fclose($file);
            };

            return Response::stream($callback, 200, $headers);
        } elseif ($type === 'certificates') {
            $filename = 'EventSphere_Certificates_Issued_' . date('Y-m-d') . '.csv';
            $headers['Content-Disposition'] = 'attachment; filename="' . $filename . '"';

            $callback = function () {
                $file = fopen('php://output', 'w');
                fputcsv($file, ['Certificate ID', 'Certificate No', 'Event Title', 'Recipient Name', 'Enrolment No', 'Issued At']);

                $certs = Certificate::with(['event', 'user'])->get();
                foreach ($certs as $cert) {
                    fputcsv($file, [
                        $cert->id,
                        $cert->certificate_number,
                        $cert->event->title ?? 'N/A',
                        $cert->user->name ?? 'N/A',
                        $cert->user->enrolment_number ?? 'N/A',
                        $cert->issued_at->format('Y-m-d H:i:s'),
                    ]);
                }
                fclose($file);
            };

            return Response::stream($callback, 200, $headers);
        }

        return back()->with('error', 'Invalid report type requested.');
    }
}
