@extends('layouts.app')

@section('title', 'Student Dashboard - EventSphere')

@section('content')
<div class="page-header reveal" style="display: flex; justify-content: space-between; align-items: flex-end; flex-wrap: wrap; gap: 1.25rem;">
    <div>
        <span class="section-label">Participant Control Center</span>
        <h1 class="page-title">Student Dashboard</h1>
        <p class="page-subtitle">Manage your active event registrations, check-in QR passes, official certificates, and saved media.</p>
    </div>

    <div style="display: flex; gap: 0.75rem; flex-wrap: wrap;">
        <button data-modal-target="profileModal" class="btn btn-secondary btn-sm"><i class="fa-solid fa-user-gear"></i> Edit Profile</button>
        <form action="{{ route('student.notifications.read') }}" method="POST">
            @csrf
            <button type="submit" class="btn btn-outline btn-sm">
                <i class="fa-regular fa-bell"></i> Mark Notifications Read ({{ $unreadNotificationsCount }})
            </button>
        </form>
    </div>
</div>

<!-- Quick Overview Stat Cards -->
<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 1.5rem; margin-bottom: 3rem;" data-stagger>
    <div class="stat-card stat-primary reveal">
        <div class="stat-card-header">
            <span class="stat-card-label">Registered Events</span>
            <div class="stat-card-icon"><i class="fa-solid fa-calendar-check"></i></div>
        </div>
        <div class="stat-card-value">{{ $registrations->where('status', 'registered')->count() }}</div>
        <div class="stat-card-sub">Active confirmed passes</div>
    </div>

    <div class="stat-card stat-warning reveal">
        <div class="stat-card-header">
            <span class="stat-card-label">On Waitlist</span>
            <div class="stat-card-icon"><i class="fa-solid fa-clock-rotate-left"></i></div>
        </div>
        <div class="stat-card-value">{{ $registrations->where('status', 'waitlisted')->count() }}</div>
        <div class="stat-card-sub">Auto-promoted on cancel</div>
    </div>

    <div class="stat-card stat-success reveal">
        <div class="stat-card-header">
            <span class="stat-card-label">Attended Events</span>
            <div class="stat-card-icon"><i class="fa-solid fa-award"></i></div>
        </div>
        <div class="stat-card-value">{{ $registrations->where('status', 'attended')->count() }}</div>
        <div class="stat-card-sub">QR check-ins verified</div>
    </div>

    <div class="stat-card stat-secondary reveal">
        <div class="stat-card-header">
            <span class="stat-card-label">Certificates Earned</span>
            <div class="stat-card-icon"><i class="fa-solid fa-certificate"></i></div>
        </div>
        <div class="stat-card-value">{{ $certificates->count() }}</div>
        <div class="stat-card-sub">Issued e-credentials</div>
    </div>
</div>

<!-- Registrations & Passes Section -->
<div class="card reveal" style="padding: 2rem; margin-bottom: 3rem;">
    <h3 style="font-size: 1.3rem; font-weight: 700; margin-bottom: 1.5rem; border-bottom: 1px solid var(--border-subtle); padding-bottom: 0.6rem;">
        <i class="fa-solid fa-ticket" style="color: var(--primary-light); margin-right: 0.4rem;"></i>
        My Event Registrations & QR Passes
    </h3>

    @if($registrations->count() > 0)
        <div class="data-table-container">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Event Title</th>
                        <th>Category</th>
                        <th>Date & Time</th>
                        <th>Venue</th>
                        <th>Status</th>
                        <th>QR Pass</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($registrations as $reg)
                        <tr>
                            <td>
                                <strong><a href="{{ route('events.show', $reg->event->slug) }}">{{ $reg->event->title }}</a></strong>
                            </td>
                            <td><span class="badge badge-primary">{{ $reg->event->category->name }}</span></td>
                            <td style="white-space: nowrap;">{{ $reg->event->start_date->format('M d, Y • h:i A') }}</td>
                            <td>{{ $reg->event->venue }}</td>
                            <td>
                                @if($reg->status === 'registered')
                                    <span class="badge badge-success"><i class="fa-solid fa-circle-check"></i> Registered</span>
                                @elseif($reg->status === 'waitlisted')
                                    <span class="badge badge-warning"><i class="fa-solid fa-clock"></i> Waitlisted</span>
                                @elseif($reg->status === 'attended')
                                    <span class="badge badge-info"><i class="fa-solid fa-user-check"></i> Attended</span>
                                @else
                                    <span class="badge badge-danger"><i class="fa-solid fa-ban"></i> Cancelled</span>
                                @endif
                            </td>
                            <td>
                                @if(in_array($reg->status, ['registered', 'attended']))
                                    <button data-modal-target="qrModal-{{ $reg->id }}" class="btn btn-sm btn-secondary">
                                        <i class="fa-solid fa-qrcode" style="color: var(--primary-light);"></i> View Pass
                                    </button>

                                    <!-- QR Pass Modal -->
                                    <div id="qrModal-{{ $reg->id }}" class="modal-backdrop">
                                        <div class="modal-card" style="text-align: center;">
                                            <div class="modal-header">
                                                <h3 class="modal-title">Event Check-in QR Pass</h3>
                                                <button data-modal-close class="modal-close-btn"><i class="fa-solid fa-xmark"></i></button>
                                            </div>

                                            <div class="qr-wrapper" style="margin: 0 auto 1.25rem;">
                                                <img src="https://api.qrserver.com/v1/create-qr-code/?size=180x180&data={{ urlencode($reg->qr_code_token) }}" alt="QR Code">
                                            </div>

                                            <p style="font-weight: 700; color: var(--primary-light); font-size: 1.1rem; margin-bottom: 0.3rem;">{{ $reg->qr_code_token }}</p>
                                            <p style="font-size: 0.85rem; color: var(--text-muted); margin-bottom: 1.25rem;">Present this QR code to organizers at the venue entrance for gate verification.</p>
                                            
                                            <div style="border-top: 1px solid var(--border-subtle); padding-top: 1rem; text-align: left; font-size: 0.85rem; background: var(--bg-surface); padding: 1rem; border-radius: var(--radius-md);">
                                                <p style="margin-bottom: 0.3rem;"><strong>Student:</strong> {{ Auth::user()->name }} ({{ Auth::user()->enrolment_number }})</p>
                                                <p style="margin-bottom: 0.3rem;"><strong>Event:</strong> {{ $reg->event->title }}</p>
                                                <p><strong>Venue:</strong> {{ $reg->event->venue }}</p>
                                            </div>
                                        </div>
                                    </div>
                                @else
                                    <span style="color: var(--text-dim); font-size: 0.85rem;">N/A</span>
                                @endif
                            </td>
                            <td>
                                @if(in_array($reg->status, ['registered', 'waitlisted']))
                                    <form action="{{ route('events.register.cancel', $reg->id) }}" method="POST" style="display: inline;">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Cancel registration?');">Cancel</button>
                                    </form>
                                @else
                                    <span style="color: var(--text-dim); font-size: 0.85rem;">-</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @else
        <div class="empty-state">
            <i class="fa-solid fa-calendar-xmark empty-state-icon"></i>
            <h3>No event registrations found</h3>
            <p>You haven't registered for any events yet. Explore upcoming campus events to get started.</p>
            <a href="{{ route('events.index') }}" class="btn btn-primary btn-sm" style="margin-top: 1rem;">Browse Events</a>
        </div>
    @endif
</div>

<!-- Certificates Section -->
<div class="card reveal" style="padding: 2rem; margin-bottom: 3rem;">
    <h3 style="font-size: 1.3rem; font-weight: 700; margin-bottom: 1.5rem; border-bottom: 1px solid var(--border-subtle); padding-bottom: 0.6rem;">
        <i class="fa-solid fa-award" style="color: var(--secondary); margin-right: 0.4rem;"></i>
        My E-Certificates
    </h3>

    @if($certificates->count() > 0)
        <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 1.5rem;">
            @foreach($certificates as $cert)
                <div class="certificate-card">
                    <div class="certificate-icon">
                        <i class="fa-solid fa-certificate"></i>
                    </div>
                    <div style="flex: 1;">
                        <h4 style="font-size: 1.05rem; font-weight: 700; margin-bottom: 0.2rem;">{{ $cert->event->title }}</h4>
                        <span style="font-size: 0.78rem; color: var(--text-dim); display: block; margin-bottom: 0.5rem;">Ref: {{ $cert->certificate_number }}</span>
                        <span style="font-size: 0.82rem; color: var(--text-muted); display: block; margin-bottom: 0.75rem;">Issued on {{ $cert->issued_at->format('M d, Y') }}</span>
                        <a href="{{ route('student.certificate.download', $cert->id) }}" target="_blank" class="btn btn-primary btn-sm w-full">
                            <i class="fa-solid fa-download"></i> View & Print
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="empty-state">
            <i class="fa-solid fa-certificate empty-state-icon" style="color: var(--text-dim);"></i>
            <h3>No certificates issued yet</h3>
            <p>Attend registered campus events and complete your check-in to receive verified participation credentials!</p>
        </div>
    @endif
</div>

<!-- Profile Edit Modal -->
<div id="profileModal" class="modal-backdrop">
    <div class="modal-card">
        <div class="modal-header">
            <h3 class="modal-title"><i class="fa-solid fa-user-gear" style="color: var(--primary-light);"></i> Update Student Profile</h3>
            <button data-modal-close class="modal-close-btn"><i class="fa-solid fa-xmark"></i></button>
        </div>

        <form action="{{ route('student.profile.update') }}" method="POST">
            @csrf
            <div class="form-group">
                <label class="form-label required">Full Name</label>
                <input type="text" name="name" class="form-control" value="{{ Auth::user()->name }}" required>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label class="form-label required">Department</label>
                    <input type="text" name="department" class="form-control" value="{{ Auth::user()->department }}" required>
                </div>
                <div class="form-group">
                    <label class="form-label required">Enrolment Number</label>
                    <input type="text" name="enrolment_number" class="form-control" value="{{ Auth::user()->enrolment_number }}" required>
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">Phone Number</label>
                <input type="text" name="phone" class="form-control" value="{{ Auth::user()->phone }}">
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">New Password (Optional)</label>
                    <input type="password" name="password" class="form-control" placeholder="Leave blank to keep current">
                </div>
                <div class="form-group">
                    <label class="form-label">Confirm Password</label>
                    <input type="password" name="password_confirmation" class="form-control" placeholder="Repeat new password">
                </div>
            </div>

            <div style="display: flex; gap: 0.75rem; justify-content: flex-end; margin-top: 1.5rem;">
                <button type="button" data-modal-close class="btn btn-secondary btn-sm">Cancel</button>
                <button type="submit" class="btn btn-primary btn-sm"><i class="fa-solid fa-floppy-disk"></i> Save Profile Changes</button>
            </div>
        </form>
    </div>
</div>
@endsection
