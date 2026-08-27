@extends('layouts.portal')

@section('title', 'Manage Registrations - ' . $event->title)

@section('content')
<div class="portal-topbar">
    <div class="portal-topbar-left">
        <h1 style="font-size: 1.75rem; font-weight: 800; letter-spacing: -0.02em;">Participant Roster</h1>
        <p style="color: var(--text-muted); font-size: 0.88rem;">
            Event: <strong>{{ $event->title }}</strong>
        </p>
    </div>

    <div style="display: flex; gap: 0.75rem; flex-wrap: wrap;">
        <a href="{{ route('organizer.events.scanner', $event->id) }}" class="btn btn-primary btn-sm">
            <i class="fa-solid fa-qrcode"></i> Launch QR Scanner
        </a>
        <button data-modal-target="announcementModal" class="btn btn-secondary btn-sm">
            <i class="fa-solid fa-bullhorn"></i> Send Announcement
        </button>
        <form action="{{ route('organizer.events.certificates.issue', $event->id) }}" method="POST" style="display: inline;">
            @csrf
            <button type="submit" class="btn btn-outline btn-sm" onclick="return confirm('Issue e-certificates to all participants marked as Attended?');">
                <i class="fa-solid fa-certificate"></i> Issue E-Certificates
            </button>
        </form>
    </div>
</div>

<div class="portal-main">
    <!-- Stats Bar -->
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1.5rem; margin-bottom: 2.5rem;" data-stagger>
        <div class="stat-card stat-primary reveal">
            <div class="stat-card-header">
                <span class="stat-card-label">Registered Students</span>
                <div class="stat-card-icon"><i class="fa-solid fa-user-check"></i></div>
            </div>
            <div class="stat-card-value">{{ $registrations->where('status', 'registered')->count() }}</div>
        </div>

        <div class="stat-card stat-success reveal">
            <div class="stat-card-header">
                <span class="stat-card-label">Attended Check-ins</span>
                <div class="stat-card-icon"><i class="fa-solid fa-award"></i></div>
            </div>
            <div class="stat-card-value">{{ $registrations->where('status', 'attended')->count() }}</div>
        </div>

        <div class="stat-card stat-warning reveal">
            <div class="stat-card-header">
                <span class="stat-card-label">On Waitlist</span>
                <div class="stat-card-icon"><i class="fa-solid fa-clock-rotate-left"></i></div>
            </div>
            <div class="stat-card-value">{{ $registrations->where('status', 'waitlisted')->count() }}</div>
        </div>

        <div class="stat-card stat-accent reveal">
            <div class="stat-card-header">
                <span class="stat-card-label">Available Slots</span>
                <div class="stat-card-icon"><i class="fa-solid fa-ticket"></i></div>
            </div>
            <div class="stat-card-value">{{ $event->available_slots }}</div>
            <div class="stat-card-sub">Max capacity: {{ $event->capacity }}</div>
        </div>
    </div>

    <!-- Participants Table -->
    <div class="card reveal" style="padding: 2rem;">
        <h3 style="font-size: 1.3rem; font-weight: 700; margin-bottom: 1.5rem; border-bottom: 1px solid var(--border-subtle); padding-bottom: 0.6rem;">
            <i class="fa-solid fa-users" style="color: var(--primary-light); margin-right: 0.4rem;"></i>
            Participant Roster & Check-ins
        </h3>

        @if($registrations->count() > 0)
            <div class="data-table-container">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Participant Name</th>
                            <th>Enrolment No</th>
                            <th>Department</th>
                            <th>Status</th>
                            <th>QR Pass Token</th>
                            <th>Check-in Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($registrations as $reg)
                            <tr>
                                <td>
                                    <strong>{{ $reg->user->name }}</strong>
                                    <div style="font-size: 0.78rem; color: var(--text-dim);">{{ $reg->user->email }}</div>
                                </td>
                                <td>{{ $reg->user->enrolment_number }}</td>
                                <td>{{ $reg->user->department }}</td>
                                <td>
                                    @if($reg->status === 'registered')
                                        <span class="badge badge-success">Registered</span>
                                    @elseif($reg->status === 'attended')
                                        <span class="badge badge-info">Attended</span>
                                    @elseif($reg->status === 'waitlisted')
                                        <span class="badge badge-warning">Waitlisted</span>
                                    @else
                                        <span class="badge badge-danger">Cancelled</span>
                                    @endif
                                </td>
                                <td><code style="font-family: monospace; font-size: 0.85rem; color: var(--primary-light);">{{ $reg->qr_code_token }}</code></td>
                                <td>
                                    @if(isset($attendances[$reg->user_id]))
                                        <span class="badge badge-success">
                                            <i class="fa-solid fa-check-circle"></i> {{ $attendances[$reg->user_id]->checked_in_at->format('h:i A') }}
                                        </span>
                                    @else
                                        <span style="color: var(--text-dim); font-size: 0.85rem;">Not Checked In</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="empty-state">
                <i class="fa-solid fa-users-slash empty-state-icon"></i>
                <h3>No registrants for this event yet</h3>
                <p>When students register on the campus portal, their entry tokens will appear here.</p>
            </div>
        @endif
    </div>
</div>

<!-- Send Announcement Modal -->
<div id="announcementModal" class="modal-backdrop">
    <div class="modal-card">
        <div class="modal-header">
            <h3 class="modal-title"><i class="fa-solid fa-bullhorn" style="color: var(--warning);"></i> Send Participant Announcement</h3>
            <button data-modal-close class="modal-close-btn"><i class="fa-solid fa-xmark"></i></button>
        </div>

        <form action="{{ route('organizer.events.announcement', $event->id) }}" method="POST">
            @csrf
            <div class="form-group">
                <label class="form-label required">Announcement Title</label>
                <input type="text" name="title" class="form-control" placeholder="Reporting Venue & Timing Update" required>
            </div>

            <div class="form-group">
                <label class="form-label required">Message Content</label>
                <textarea name="message" class="form-textarea" rows="4" placeholder="Type announcement message to be sent to all registered students..." required></textarea>
            </div>

            <div style="display: flex; gap: 0.75rem; justify-content: flex-end; margin-top: 1.5rem;">
                <button type="button" data-modal-close class="btn btn-secondary btn-sm">Cancel</button>
                <button type="submit" class="btn btn-primary btn-sm"><i class="fa-solid fa-paper-plane"></i> Send Announcement</button>
            </div>
        </form>
    </div>
</div>
@endsection
