<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Feedback;
use App\Models\Registration;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FeedbackController extends Controller
{
    public function store(Request $request, $eventId)
    {
        $user = Auth::user();
        $event = Event::findOrFail($eventId);

        // Check if user attended or was registered
        $registration = Registration::where('event_id', $event->id)
            ->where('user_id', $user->id)
            ->whereIn('status', ['registered', 'attended'])
            ->first();

        if (!$registration) {
            return back()->with('error', 'Only registered participants can leave feedback for this event.');
        }

        $validated = $request->validate([
            'user_role_title' => 'required|string|max:100',
            'overall_rating' => 'required|integer|min:1|max:5',
            'venue_rating' => 'required|integer|min:1|max:5',
            'coordination_rating' => 'required|integer|min:1|max:5',
            'tech_rating' => 'required|integer|min:1|max:5',
            'hospitality_rating' => 'required|integer|min:1|max:5',
            'comments' => 'nullable|string|max:1000',
        ]);

        Feedback::updateOrCreate(
            ['event_id' => $event->id, 'user_id' => $user->id],
            [
                'user_role_title' => $validated['user_role_title'],
                'overall_rating' => $validated['overall_rating'],
                'venue_rating' => $validated['venue_rating'],
                'coordination_rating' => $validated['coordination_rating'],
                'tech_rating' => $validated['tech_rating'],
                'hospitality_rating' => $validated['hospitality_rating'],
                'comments' => $validated['comments'],
                'is_approved' => true,
            ]
        );

        return back()->with('success', 'Thank you for your valuable feedback!');
    }
}
