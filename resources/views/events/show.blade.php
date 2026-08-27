@extends('layouts.app')

@section('title', $event->title . ' - EVENTSPHERE')

@section('content')
<div style="max-width: 1320px; margin: 3rem auto 5rem; padding: 0 1.5rem;">

    <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 2.5rem; align-items: start;" class="event-details-layout">
        
        <!-- Left / Main Area -->
        <div>
            <!-- Large Event Banner Card -->
            <div class="card reveal" style="overflow: hidden; margin-bottom: 2.25rem;">
                <div style="height: 380px; width: 100%; background-image: url('{{ $event->banner_image ? asset($event->banner_image) : 'https://images.unsplash.com/photo-1540575467063-178a50c2df87?auto=format&fit=crop&w=1200&q=80' }}'); background-size: cover; background-position: center; position: relative;">
                    <div style="position: absolute; inset: 0; background: linear-gradient(to top, #121826 15%, rgba(7,9,14,0.65) 65%, rgba(7,9,14,0.3) 100%);"></div>
                    
                    <div style="position: absolute; top: 1.5rem; left: 1.5rem; display: flex; gap: 0.75rem; flex-wrap: wrap;">
                        <span class="event-badge" style="position: static;"><i class="fa-solid fa-tag"></i> {{ $event->category->name }}</span>
                        <span class="event-badge" style="position: static; background: rgba(99,102,241,0.85); border-color: rgba(99,102,241,0.5);"><i class="fa-solid fa-building-columns"></i> {{ $event->organizing_department }}</span>
                    </div>

                    <div style="position: absolute; bottom: 1.75rem; left: 1.75rem; right: 1.75rem;">
                        <h1 style="font-size: clamp(1.8rem, 3.5vw, 2.5rem); font-weight: 900; color: #FFFFFF; line-height: 1.2; margin-bottom: 0.75rem; text-shadow: 0 2px 10px rgba(0,0,0,0.8);">
                            {{ $event->title }}
                        </h1>
                        <div style="display: flex; gap: 1.75rem; color: var(--text-muted); font-size: 0.92rem; flex-wrap: wrap;">
                            <span><i class="fa-regular fa-user" style="color: var(--primary-light); margin-right: 0.3rem;"></i> Organized by {{ $event->organizer->name }}</span>
                            <span><i class="fa-solid fa-star" style="color: var(--warning); margin-right: 0.3rem;"></i> {{ number_format($event->averageRating(), 1) }} / 5.0 ({{ $event->feedbacks->count() }} reviews)</span>
                        </div>
                    </div>
                </div>

                <div style="padding: 2.25rem;">
                    <h3 style="font-size: 1.35rem; font-weight: 800; color: #FFFFFF; margin-bottom: 1.25rem; border-bottom: 1px solid var(--border-subtle); padding-bottom: 0.75rem;">
                        <i class="fa-solid fa-circle-info" style="color: var(--primary-light); margin-right: 0.4rem;"></i>
                        Event Description & Schedule
                    </h3>
                    
                    <div style="line-height: 1.8; color: var(--text-main); font-size: 1rem; margin-bottom: 2rem;">
                        {!! nl2br(e($event->description)) !!}
                    </div>

                    @if($event->rulebook_file)
                        <div style="padding: 1.5rem; background: var(--bg-surface); border-radius: var(--radius-lg); border: 1px solid var(--border-color); display: flex; align-items: center; justify-content: space-between; margin-bottom: 2.25rem; flex-wrap: wrap; gap: 1rem;">
                            <div style="display: flex; align-items: center; gap: 1.1rem;">
                                <div style="width: 48px; height: 48px; border-radius: var(--radius-md); background: rgba(239,68,68,0.15); display: flex; align-items: center; justify-content: center; color: #F87171; font-size: 1.4rem; border: 1px solid rgba(239,68,68,0.3);">
                                    <i class="fa-solid fa-file-pdf"></i>
                                </div>
                                <div>
                                    <strong style="color: #FFFFFF; font-size: 1rem; display: block;">Official Guidelines & Rulebook</strong>
                                    <span style="font-size: 0.85rem; color: var(--text-muted);">Eligibility criteria, round breakdown, and judging rubrics</span>
                                </div>
                            </div>
                            <a href="{{ asset($event->rulebook_file) }}" target="_blank" class="btn btn-secondary btn-sm">
                                <i class="fa-solid fa-download"></i> Download PDF
                            </a>
                        </div>
                    @endif

                    <!-- Social Sharing & Calendar Sync -->
                    <div style="border-top: 1px solid var(--border-subtle); padding-top: 1.75rem; display: flex; flex-wrap: wrap; justify-content: space-between; align-items: center; gap: 1.5rem;">
                        <div>
                            <span style="font-weight: 700; font-size: 0.82rem; color: var(--text-dim); display: block; margin-bottom: 0.6rem; text-transform: uppercase; letter-spacing: 0.06em;">Share Event</span>
                            <div style="display: flex; gap: 0.6rem;">
                                <button onclick="shareEvent('whatsapp', '{{ addslashes($event->title) }}', '{{ url()->current() }}', '{{ $event->hashtags }}')" class="btn btn-sm" style="background: #25D366; color: #fff;" title="Share on WhatsApp"><i class="fa-brands fa-whatsapp"></i></button>
                                <button onclick="shareEvent('twitter', '{{ addslashes($event->title) }}', '{{ url()->current() }}', '{{ $event->hashtags }}')" class="btn btn-sm" style="background: #1DA1F2; color: #fff;" title="Share on X"><i class="fa-brands fa-x-twitter"></i></button>
                                <button onclick="shareEvent('facebook', '{{ addslashes($event->title) }}', '{{ url()->current() }}', '{{ $event->hashtags }}')" class="btn btn-sm" style="background: #4267B2; color: #fff;" title="Share on Facebook"><i class="fa-brands fa-facebook-f"></i></button>
                                <button onclick="shareEvent('linkedin', '{{ addslashes($event->title) }}', '{{ url()->current() }}', '{{ $event->hashtags }}')" class="btn btn-sm" style="background: #0077b5; color: #fff;" title="Share on LinkedIn"><i class="fa-brands fa-linkedin-in"></i></button>
                                <button onclick="shareEvent('email', '{{ addslashes($event->title) }}', '{{ url()->current() }}', '{{ $event->hashtags }}')" class="btn btn-sm btn-secondary" title="Share via Email"><i class="fa-solid fa-envelope"></i></button>
                            </div>
                        </div>

                        <div>
                            <span style="font-weight: 700; font-size: 0.82rem; color: var(--text-dim); display: block; margin-bottom: 0.6rem; text-transform: uppercase; letter-spacing: 0.06em;">Sync Calendar</span>
                            <a href="{{ route('events.calendar', $event->id) }}" class="btn btn-secondary btn-sm">
                                <i class="fa-regular fa-calendar-plus" style="color: var(--primary-light);"></i> Add to Calendar (.ics)
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Participant Feedback Section -->
            <div class="card reveal" style="padding: 2.25rem;">
                <h3 style="font-size: 1.35rem; font-weight: 800; color: #FFFFFF; margin-bottom: 1.5rem; border-bottom: 1px solid var(--border-subtle); padding-bottom: 0.75rem;">
                    <i class="fa-regular fa-comments" style="color: var(--primary-light); margin-right: 0.4rem;"></i>
                    Participant Ratings & Reviews
                </h3>

                @auth
                    @if(isset($userRegistration) && in_array($userRegistration->status, ['registered', 'attended']))
                        <div style="background: var(--bg-surface); border-radius: var(--radius-lg); padding: 1.75rem; margin-bottom: 2rem; border: 1px solid var(--border-color);">
                            <h4 style="font-size: 1.1rem; font-weight: 800; color: #FFFFFF; margin-bottom: 1rem;"><i class="fa-regular fa-comment-dots" style="color: var(--primary-light);"></i> Submit Your Event Review</h4>
                            <form action="{{ route('events.feedback', $event->id) }}" method="POST">
                                @csrf
                                <div class="form-group">
                                    <label class="form-label" style="font-size: 0.82rem;">Your Participation Role</label>
                                    <select name="user_role_title" class="form-select" required>
                                        <option value="Student Participant">Student Participant</option>
                                        <option value="Student Attendee / Viewer">Student Attendee / Viewer</option>
                                        <option value="Event Volunteer">Event Volunteer</option>
                                        <option value="Faculty Delegate">Faculty Delegate</option>
                                    </select>
                                </div>

                                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(130px, 1fr)); gap: 1rem; margin-bottom: 1.25rem;">
                                    <div>
                                        <label class="form-label" style="font-size: 0.78rem;">Overall</label>
                                        <select name="overall_rating" class="form-select">
                                            <option value="5">5 ★★★★★</option>
                                            <option value="4">4 ★★★★☆</option>
                                            <option value="3">3 ★★★☆☆</option>
                                            <option value="2">2 ★★☆☆☆</option>
                                            <option value="1">1 ★☆☆☆☆</option>
                                        </select>
                                    </div>
                                    <div>
                                        <label class="form-label" style="font-size: 0.78rem;">Venue</label>
                                        <select name="venue_rating" class="form-select">
                                            <option value="5">5 ★★★★★</option>
                                            <option value="4">4 ★★★★☆</option>
                                            <option value="3">3 ★★★☆☆</option>
                                        </select>
                                    </div>
                                    <div>
                                        <label class="form-label" style="font-size: 0.78rem;">Coordination</label>
                                        <select name="coordination_rating" class="form-select">
                                            <option value="5">5 ★★★★★</option>
                                            <option value="4">4 ★★★★☆</option>
                                            <option value="3">3 ★★★☆☆</option>
                                        </select>
                                    </div>
                                    <div>
                                        <label class="form-label" style="font-size: 0.78rem;">Tech Support</label>
                                        <select name="tech_rating" class="form-select">
                                            <option value="5">5 ★★★★★</option>
                                            <option value="4">4 ★★★★☆</option>
                                            <option value="3">3 ★★★☆☆</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="form-label" style="font-size: 0.82rem;">Your Comments</label>
                                    <textarea name="comments" class="form-textarea" rows="3" placeholder="Share your experience, highlights, or suggestions for the organizers..." required></textarea>
                                </div>

                                <button type="submit" class="btn btn-primary btn-sm">
                                    <i class="fa-solid fa-paper-plane"></i> Submit Review
                                </button>
                            </form>
                        </div>
                    @endif
                @endauth

                @if($event->feedbacks->count() > 0)
                    <div style="display: flex; flex-direction: column; gap: 1rem;">
                        @foreach($event->feedbacks as $feedback)
                            <div style="background: var(--bg-surface); padding: 1.25rem; border-radius: var(--radius-md); border: 1px solid var(--border-subtle);">
                                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.5rem; flex-wrap: wrap; gap: 0.5rem;">
                                    <div>
                                        <strong style="color: #FFFFFF; font-size: 0.95rem;">{{ $feedback->user->name }}</strong>
                                        <span style="font-size: 0.8rem; color: var(--text-dim); margin-left: 0.5rem;">({{ $feedback->user_role_title }})</span>
                                    </div>
                                    <div style="color: var(--warning); font-size: 0.9rem;">
                                        @for($i = 1; $i <= 5; $i++)
                                            <i class="fa-{{ $i <= $feedback->overall_rating ? 'solid' : 'regular' }} fa-star"></i>
                                        @endfor
                                    </div>
                                </div>
                                <p style="font-size: 0.9rem; color: var(--text-muted); line-height: 1.6;">{{ $feedback->comments }}</p>
                                <span style="font-size: 0.76rem; color: var(--text-dim); margin-top: 0.4rem; display: block;">{{ $feedback->created_at->diffForHumans() }}</span>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p style="color: var(--text-dim); font-size: 0.95rem; text-align: center; padding: 1.5rem 0;">No reviews published for this event yet.</p>
                @endif
            </div>
        </div>

        <!-- Right / Side Panel (Sticky Booking Summary) -->
        <div style="position: sticky; top: 94px;">
            <div class="card reveal" style="padding: 2.25rem; border-top: 4px solid var(--primary); box-shadow: var(--shadow-lg);">
                
                <h3 style="font-size: 1.35rem; font-weight: 900; color: #FFFFFF; margin-bottom: 1.5rem;">Event Information</h3>

                <!-- Key Details List -->
                <div style="display: flex; flex-direction: column; gap: 1.25rem; margin-bottom: 1.75rem;">
                    
                    <div style="display: flex; align-items: flex-start; gap: 1rem;">
                        <div style="width: 38px; height: 38px; border-radius: var(--radius-md); background: var(--primary-subtle); color: var(--primary-light); display: flex; align-items: center; justify-content: center; font-size: 1.1rem; flex-shrink: 0;">
                            <i class="fa-regular fa-calendar"></i>
                        </div>
                        <div>
                            <span style="font-size: 0.78rem; font-weight: 700; color: var(--text-dim); text-transform: uppercase;">Date & Schedule</span>
                            <strong style="display: block; color: #FFFFFF; font-size: 0.95rem;">{{ $event->start_date->format('M d, Y') }} - {{ $event->end_date->format('M d, Y') }}</strong>
                            <span style="font-size: 0.82rem; color: var(--text-muted);">{{ $event->start_date->format('h:i A') }} to {{ $event->end_date->format('h:i A') }}</span>
                        </div>
                    </div>

                    <div style="display: flex; align-items: flex-start; gap: 1rem;">
                        <div style="width: 38px; height: 38px; border-radius: var(--radius-md); background: var(--primary-subtle); color: var(--primary-light); display: flex; align-items: center; justify-content: center; font-size: 1.1rem; flex-shrink: 0;">
                            <i class="fa-solid fa-location-dot"></i>
                        </div>
                        <div>
                            <span style="font-size: 0.78rem; font-weight: 700; color: var(--text-dim); text-transform: uppercase;">Venue Location</span>
                            <strong style="display: block; color: #FFFFFF; font-size: 0.95rem;">{{ $event->venue }}</strong>
                        </div>
                    </div>

                    <div style="display: flex; align-items: flex-start; gap: 1rem;">
                        <div style="width: 38px; height: 38px; border-radius: var(--radius-md); background: var(--primary-subtle); color: var(--primary-light); display: flex; align-items: center; justify-content: center; font-size: 1.1rem; flex-shrink: 0;">
                            <i class="fa-solid fa-clock-rotate-left"></i>
                        </div>
                        <div>
                            <span style="font-size: 0.78rem; font-weight: 700; color: var(--text-dim); text-transform: uppercase;">Registration Cutoff</span>
                            <strong style="display: block; color: #FFFFFF; font-size: 0.95rem;">{{ $event->registration_deadline->format('M d, Y • h:i A') }}</strong>
                        </div>
                    </div>

                    <div style="display: flex; align-items: flex-start; gap: 1rem;">
                        <div style="width: 38px; height: 38px; border-radius: var(--radius-md); background: var(--primary-subtle); color: var(--primary-light); display: flex; align-items: center; justify-content: center; font-size: 1.1rem; flex-shrink: 0;">
                            <i class="fa-solid fa-user-tie"></i>
                        </div>
                        <div>
                            <span style="font-size: 0.78rem; font-weight: 700; color: var(--text-dim); text-transform: uppercase;">Organizer & Host</span>
                            <strong style="display: block; color: #FFFFFF; font-size: 0.95rem;">{{ $event->organizer->name }}</strong>
                            <span style="font-size: 0.82rem; color: var(--text-muted);">{{ $event->organizing_department }}</span>
                        </div>
                    </div>
                </div>

                <!-- Seat Capacity Progress -->
                <div style="padding: 1.25rem; background: var(--bg-surface); border-radius: var(--radius-lg); border: 1px solid var(--border-color); margin-bottom: 1.75rem;">
                    <div style="display: flex; justify-content: space-between; font-size: 0.85rem; font-weight: 700; margin-bottom: 0.5rem;">
                        <span style="color: var(--text-dim); text-transform: uppercase;">Capacity Status</span>
                        <span style="color: #FFFFFF;">{{ $event->capacity - $event->available_slots }} / {{ $event->capacity }} booked</span>
                    </div>
                    <div style="height: 8px; background: rgba(255,255,255,0.08); border-radius: 4px; overflow: hidden; margin-bottom: 0.5rem;">
                        <div style="height: 100%; background: var(--gradient-primary); width: {{ (($event->capacity - $event->available_slots) / max(1, $event->capacity)) * 100 }}%;"></div>
                    </div>
                    <span style="font-size: 0.8rem; color: {{ $event->available_slots > 0 ? '#34D399' : '#F87171' }}; font-weight: 700;">
                        <i class="fa-solid fa-circle" style="font-size: 0.5rem; vertical-align: middle;"></i>
                        {{ $event->available_slots > 0 ? $event->available_slots . ' slots available' : 'Maximum capacity reached (Waitlist)' }}
                    </span>
                </div>

                <!-- Registration Action Section -->
                @auth
                    @if(isset($userRegistration))
                        <div style="padding: 1.25rem; background: rgba(59,130,246,0.1); border: 1px solid rgba(59,130,246,0.3); border-radius: var(--radius-md); text-align: center; margin-bottom: 1.25rem;">
                            <span class="status-pill status-{{ $userRegistration->status }}" style="margin-bottom: 0.5rem;">
                                Status: {{ strtoupper($userRegistration->status) }}
                            </span>
                            <p style="font-size: 0.85rem; color: var(--text-muted); margin-top: 0.4rem;">
                                QR Token: <strong style="color: #FFFFFF;">{{ $userRegistration->qr_code_token }}</strong>
                            </p>
                        </div>

                        <div style="display: flex; flex-direction: column; gap: 0.75rem;">
                            <a href="{{ route('student.dashboard') }}" class="btn btn-primary w-full">
                                <i class="fa-solid fa-qrcode"></i> View QR Pass on Dashboard
                            </a>
                            <form action="{{ route('events.register.cancel', $userRegistration->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-secondary w-full" onclick="return confirm('Are you sure you want to cancel your event registration?');">
                                    <i class="fa-solid fa-ban"></i> Cancel Registration
                                </button>
                            </form>
                        </div>
                    @else
                        @if(now()->greaterThan($event->registration_deadline))
                            <button class="btn btn-secondary w-full" disabled>
                                <i class="fa-solid fa-lock"></i> Registration Closed
                            </button>
                        @elseif($event->available_slots > 0)
                            <form action="{{ route('events.register', $event->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-primary w-full btn-lg" style="box-shadow: 0 0 25px rgba(59, 130, 246, 0.5);">
                                    <i class="fa-solid fa-ticket"></i> REGISTER NOW
                                </button>
                            </form>
                        @else
                            <form action="{{ route('events.register', $event->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-primary-light w-full btn-lg">
                                    <i class="fa-solid fa-clock"></i> Join Waitlist Queue
                                </button>
                            </form>
                        @endif
                    @endif
                @else
                    <a href="{{ route('login') }}" class="btn btn-primary w-full btn-lg" style="box-shadow: 0 0 25px rgba(59, 130, 246, 0.5);">
                        <i class="fa-solid fa-right-to-bracket"></i> REGISTER NOW (LOG IN)
                    </a>
                @endauth
            </div>
        </div>
    </div>
</div>

<style>
@media (max-width: 992px) {
    .event-details-layout {
        grid-template-columns: 1fr !important;
    }
}
</style>

@endsection
