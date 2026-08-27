<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Registration;
use App\Models\Waitlist;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class RegistrationController extends Controller
{
    public function store($eventId)
    {
        $user = Auth::user();
        $event = Event::findOrFail($eventId);

        if ($event->status !== 'approved') {
            return back()->with('error', 'This event is not open for registration.');
        }

        if (now()->greaterThan($event->registration_deadline)) {
            return back()->with('error', 'Registration cutoff date for this event has passed.');
        }

        // Check existing registration
        $existing = Registration::where('event_id', $event->id)
            ->where('user_id', $user->id)
            ->whereIn('status', ['registered', 'waitlisted', 'attended'])
            ->first();

        if ($existing) {
            return back()->with('info', 'You are already registered or waitlisted for this event.');
        }

        // Check Slot Availability
        if ($event->available_slots > 0) {
            $qrToken = 'QR-' . strtoupper(Str::random(12)) . '-' . $user->id;

            $registration = Registration::create([
                'event_id' => $event->id,
                'user_id' => $user->id,
                'status' => 'registered',
                'qr_code_token' => $qrToken,
                'registered_at' => now(),
            ]);

            $event->decrement('available_slots');

            Notification::create([
                'user_id' => $user->id,
                'title' => 'Registration Confirmed!',
                'message' => "You have successfully registered for '{$event->title}'. Access your check-in QR code in your dashboard.",
                'type' => 'registration',
            ]);

            return back()->with('success', "Success! You are registered for {$event->title}. Your QR pass has been generated.");
        } else {
            // Join Waitlist
            $position = Waitlist::where('event_id', $event->id)->count() + 1;

            Waitlist::create([
                'event_id' => $event->id,
                'user_id' => $user->id,
                'position' => $position,
            ]);

            Registration::create([
                'event_id' => $event->id,
                'user_id' => $user->id,
                'status' => 'waitlisted',
                'qr_code_token' => 'WAITLIST-' . strtoupper(Str::random(10)),
                'registered_at' => now(),
            ]);

            Notification::create([
                'user_id' => $user->id,
                'title' => 'Added to Waitlist',
                'message' => "Capacity reached for '{$event->title}'. You are placed at position #{$position} on the waitlist.",
                'type' => 'waitlist',
            ]);

            return back()->with('warning', "Capacity full! You have been added to the waitlist at position #{$position}. You will be automatically enrolled if a slot opens up.");
        }
    }

    public function cancel($registrationId)
    {
        $user = Auth::user();
        $registration = Registration::where('id', $registrationId)
            ->where('user_id', $user->id)
            ->firstOrFail();

        $event = $registration->event;

        if ($registration->status === 'cancelled') {
            return back()->with('info', 'Registration is already cancelled.');
        }

        $wasRegistered = ($registration->status === 'registered');

        $registration->update([
            'status' => 'cancelled',
            'cancelled_at' => now(),
        ]);

        // Remove from waitlist table if was waitlisted
        Waitlist::where('event_id', $event->id)->where('user_id', $user->id)->delete();

        if ($wasRegistered) {
            $event->increment('available_slots');

            // Automatic Waitlist Promotion Engine
            $nextWaitlist = Waitlist::where('event_id', $event->id)
                ->orderBy('position', 'asc')
                ->first();

            if ($nextWaitlist) {
                $promotedUser = $nextWaitlist->user;
                $promotedReg = Registration::where('event_id', $event->id)
                    ->where('user_id', $promotedUser->id)
                    ->where('status', 'waitlisted')
                    ->first();

                if ($promotedReg) {
                    $qrToken = 'QR-' . strtoupper(Str::random(12)) . '-' . $promotedUser->id;
                    $promotedReg->update([
                        'status' => 'registered',
                        'qr_code_token' => $qrToken,
                    ]);

                    $event->decrement('available_slots');
                    $nextWaitlist->delete();

                    // Re-index remaining waitlist positions
                    $remainingWaitlists = Waitlist::where('event_id', $event->id)->orderBy('position', 'asc')->get();
                    foreach ($remainingWaitlists as $idx => $item) {
                        $item->update(['position' => $idx + 1]);
                    }

                    Notification::create([
                        'user_id' => $promotedUser->id,
                        'title' => 'Waitlist Auto-Promoted!',
                        'message' => "Great news! A slot opened up for '{$event->title}'. You have been automatically promoted from waitlist to registered participant!",
                        'type' => 'waitlist_promoted',
                    ]);
                }
            }
        }

        Notification::create([
            'user_id' => $user->id,
            'title' => 'Registration Cancelled',
            'message' => "Your registration for '{$event->title}' has been cancelled.",
            'type' => 'registration_cancelled',
        ]);

        return back()->with('success', 'Your registration has been cancelled successfully.');
    }

    public function payFee(Request $request, $registrationId)
    {
        $user = Auth::user();
        $registration = Registration::where('id', $registrationId)
            ->where('user_id', $user->id)
            ->firstOrFail();

        $request->validate([
            'txn_id' => 'required|string|max:100',
        ]);

        $registration->update([
            'certificate_fee_paid' => true,
            'certificate_fee_txn' => $request->input('txn_id'),
        ]);

        return back()->with('success', 'Certificate fee payment details recorded successfully!');
    }
}
